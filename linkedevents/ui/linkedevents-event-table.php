<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'WP_List_Table' ) ) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\EventsTable' ) ) {
    
    class EventsTable extends \WP_List_Table {
      
      private static $DEFAULT_TIMEZONE = 'Europe/Helsinki';
      private static $DATETIME_FORMAT = 'Y-m-d H:i:s';
      private static $DATE_FORMAT = 'Y-m-d';
      private $perPage = 10;
      
      /**
       * @var \Metatavu\LinkedEvents\Client\EventApi
       */
      private $eventsApi;
      
      public function __construct() {        
        parent::__construct([
          'singular'  => 'event',
          'plural'    => 'events',
          'ajax'      => false  
        ]);
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-dialog', null, ['jquery']);
        wp_enqueue_script('linkedevents-table', plugin_dir_url(__FILE__) . 'linkedevents-table.js', null, ['jquery-ui-dialog' ]);
        
        wp_register_style('jquery-ui', 'https://cdn.metatavu.io/libs/jquery-ui/1.12.1/jquery-ui.min.css');
        wp_enqueue_style('jquery-ui');
        
        $this->eventsApi = \Metatavu\LinkedEvents\Wordpress\Api::getEventApi();
      }
      
      public function prepare_items() {
        $this->_column_headers = [ $this->get_columns(), $this->get_hidden_columns(), $this->get_sortable_columns() ];
        $this->process_bulk_action();
        $events = $this->listEvents($this->get_pagenum(), $this->perPage, true);
        
        $this->items = [];
        $itemCount = $events->getMeta()->getCount();
        
        foreach ($events->getData() as $event) {
          $start = $event['startTime'];
          $end = $event['endTime'];
          
          $this->items[] = [
            "id" => $event['id'],
            "title" => $event['name']['fi'],
            "start" => $this->formatDateTime($start),
            "end" =>  $this->formatDateTime($end),
            "status" => $this->getPublicationStatus($event)
          ];
        }
        
        $this->set_pagination_args([
          'total_items' => $itemCount,
          'per_page'    => $this->perPage,
          'total_pages' => ceil($itemCount/ $this->perPage)
        ]);
      }
       
      public function get_columns() {
        $columns = [
          'id' => 'ID',
          'title' => __('Title', 'linkedevents'),
          'start' => __('Start', 'linkedevents'),
          'end' => __('End', 'linkedevents'),
          'status' => __('Status', 'linkedevents')
        ];

        return $columns;
      }

      public function get_hidden_columns() {
        return ['id'];
      }
      
      public function get_sortable_columns() {
        return [
        ];
      }
      
      public function column_default( $item, $column_name ) {
        return $item[$column_name];
      }
      
      public function column_title($item) {
        $id = $item['id'];
        $title = $item['title'];
        
        $actions = [];
        
        if (current_user_can('linkedevents_edit_events')) {
          $actions['edit'] = sprintf('<a href="?page=linkedevents-edit-event.php&action=%s&event=%s">' . __('Edit', 'linkedevents') . '</a>', 'edit', $id);
        }
        
        if (current_user_can('linkedevents_delete_event')) {
          $dialogTitle = __("Are you sure?", 'linkedevents');
          $dialogContent = sprintf(__("Are you sure that you want to remove event '%s'?", 'linkedevents'), $title);
          $dialogConfirm = __("Delete", 'linkedevents');
          $dialogCancel = __("Cancel", 'linkedevents');
          $actions['delete'] = sprintf('<a data-action="linkedevents_delete_event" data-dialog-title="%s" data-dialog-content="%s" data-dialog-confirm="%s" data-dialog-cancel="%s" class="linkedevents-delete-link" href="#" data-id="' . $id . '">' . __('Delete', 'linkedevents') . '</a>', $dialogTitle, $dialogContent, $dialogConfirm, $dialogCancel, 'delete', $id);
        }
        
        return sprintf('%1$s%2$s',
          $title,
          $this->row_actions($actions)
        );
      }
      
      public function search_box( $text, $input_id ) {
      ?>
        <form method="POST">
          <p class="search-box">
            <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo $input_id ?>" name="search-events" value="<?php _admin_search_query(); ?>" />
            <?php submit_button( $text, 'button', '', false, array('id' => 'search-submit') ); ?>
            </p>
        </form>
      <?php
      }
      
      /**
       * Lists events from API
       * 
       * @param type $page page
       * @param type $pageSize events per page
       * @param type $showAll whether to show also draft events
       * @param type $sort sort by (optional)
       * @return \Metatavu\LinkedEvents\Model\Event[] events
       */
      private function listEvents($page, $pageSize, $showAll, $sort = null) {
        $include = null;
        $text = isset($_REQUEST['search-events']) ? $_REQUEST['search-events'] : null;
        $lastModifiedSince = null;
        $start = null;
        $end = null;
        $bbox = null;
        $dataSource = \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("datasource");
        $location = null;
        $division = null;
        $keyword = null;
        $recurring = null;
        $minDuration = null;
        $maxDuration = null;
        $publisher = null;
        
        try {
          return $this->eventsApi->eventList($include, $text, $lastModifiedSince, $start, $end, $bbox, $dataSource, $location, $showAll, $division, $keyword, $recurring, $minDuration, $maxDuration, $publisher, $sort, $page, $pageSize);
        } catch (\Metatavu\LinkedEvents\ApiException $e) {
          echo '<div class="error notice">';
          if ($e->getResponseBody()) {
            echo print_r($e->getResponseBody());
          } else {
            echo $e;
          }
          echo '</div>';
        }
      }
      
      /**
       * Returns localized event publication status
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       * @return string localized event publication status
       */
      private function getPublicationStatus($event) {
        $publicationStatus = $event->getPublicationStatus();
        if ($publicationStatus === 'public') {
          return __('Public', 'linkedevents');
        } else if ($publicationStatus === 'draft') {
          return __('Draft', 'linkedevents');
        }
        
        return $publicationStatus;
      }
      
      /**
       * Returns date time as string
       * 
       * @param \DateTime $dateTime date time
       * @return string formatted date time
       */
      private function formatDateTime($dateTime) {
        if ($dateTime) {
          $clone = clone $dateTime;
          $clone->setTimezone($this->getTimezone());
          return $clone->format($clone->getHasTime() ? self::$DATETIME_FORMAT : self::$DATE_FORMAT);
        }
        
        return null;
      }
      
      /**
       * Returns time zone
       * 
       * @return \DateTimeZone time zone
       */
      private function getTimezone() {
        $result = \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("timezone");
        return new \DateTimeZone($result ? $result : self::$DEFAULT_TIMEZONE);
      }
      
    }
    
  }
    
?>