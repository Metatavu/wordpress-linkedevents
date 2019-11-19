<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'WP_List_Table' ) ) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\PlacesTable' ) ) {
    
    class PlacesTable extends \WP_List_Table {
      
      private $perPage = 10;
      private $filterApi;
      
      public function __construct() {        
        parent::__construct([
          'singular'  => 'place',
          'plural'    => 'places',
          'ajax'      => false  
        ]);
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-dialog', null, ['jquery']);
        wp_enqueue_script('linkedevents-table', plugin_dir_url(__FILE__) . 'linkedevents-table.js', null, ['jquery-ui-dialog' ]);
        
        wp_register_style('jquery-ui', 'https://cdn.metatavu.io/libs/jquery-ui/1.12.1/jquery-ui.min.css');
        wp_enqueue_style('jquery-ui');
        
        $this->filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi();
      }
      /**
       * Returns free text search text if any specified 
       * @return string free text search text if any specified
       */
      public function getText() {
        return isset($_REQUEST['s']) ? $_REQUEST['s'] : null;
      }
    
      /**
       * Returns status filter if any specified 
       * @return string status filter if any specified
       */
      public function getStatus() {
        return isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
      }
      
      public function prepare_items() {
        $this->_column_headers = [ $this->get_columns(), $this->get_hidden_columns(), $this->get_sortable_columns() ];
        $this->process_bulk_action();
        $places = $this->listPlaces($this->get_pagenum(), $this->perPage, true, $this->getText(), $this->getStatus());
        
        $this->items = [];
        $itemCount = $places->getMeta()->getCount();
        
        // TODO: Support localization
        foreach ($places->getData() as $place) {
          $this->items[] = [
            "id" => $place['id'],
            "title" => $place['name']['fi']
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
          'title' => 'Title'
        ];

        return $columns;
      }

      public function get_hidden_columns() {
        return ['id'];
      }
      
      public function get_sortable_columns() {
        return [ ];
      }
      
      public function column_default( $item, $column_name ) {
        return $item[$column_name];
      }
      
      public function column_title($item) {
        $id = $item['id'];
        $title = $item['title'];
        
        $dialogTitle = __("Are you sure?", 'linkedevents');
        $dialogContent = sprintf(__("Are you sure that you want to remove place '%s'?", 'linkedevents'), $title);
        $dialogConfirm = __("Delete", 'linkedevents');
        $dialogCancel = __("Cancel", 'linkedevents');
        $actions = [];
        
        if (current_user_can('linkedevents_edit_places')) {
          $actions['edit'] = sprintf('<a href="?page=linkedevents-edit-place.php&action=%s&place=%s">' . __('Edit', 'linkedevents') . '</a>', 'edit', $id);
        }

        if (current_user_can('linkedevents_delete_place')) {
          $actions['delete'] = sprintf('<a data-action="linkedevents_delete_place" data-dialog-title="%s" data-dialog-content="%s" data-dialog-confirm="%s" data-dialog-cancel="%s" class="linkedevents-delete-link" href="#" data-id="' . $id . '">' . __('Delete', 'linkedevents') . '</a>', $dialogTitle, $dialogContent, $dialogConfirm, $dialogCancel, 'delete', $id);
        }
        
        return sprintf('%1$s%2$s',
          $title,
          $this->row_actions($actions)
        );
      }

      /**
       * Renders search box
       */
      public function search_box( $text, $input_id ) {
        $status = $this->getStatus();

      ?>
        <form method="get" class="search-form">
          <p class="search-box">
            <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo esc_attr( $input_id ); ?>" class="wp-filter-search" name="s" value="<?php _admin_search_query(); ?>" placeholder="<?php esc_attr_e( 'Search places...', 'linkedevents'); ?>"/>
            <?php submit_button( $text, 'button', '', false, array('id' => 'search-submit') ); ?>
            <input type="hidden" value="linkedevents-places.php" name="page"/>
            <?php if ($status) { 
              echo '<input type="hidden" value="' . $status . '" name="status"/>';
            } ?>
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
       * @param String $text search by free text
       * @param type $sort sort by (optional)
       * @return \Metatavu\LinkedEvents\Model\Place[] events
       */

      private function listPlaces($page, $pageSize, $showAllPlaces, $text, $sort = null) {
        $division = null;
        $showAllPlaces = true;
        $dataSource = \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("datasource");
        
        try {
          return $this->filterApi->placeList(
            $page, 
            $pageSize, 
            $showAllPlaces, 
            $division, 
            $dataSource, 
            $text, 
            $sort);
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
    }
    
  }
    
?>