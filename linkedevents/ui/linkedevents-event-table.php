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
      
      private $eventsApi;
      
      public function __construct() {        
        parent::__construct([
          'singular'  => 'event',
          'plural'    => 'events',
          'ajax'      => false  
        ]);
        
        $this->eventsApi = \Metatavu\LinkedEvents\Wordpress\Api::getEventApi();
      }
      
      public function prepare_items() {
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $events = $this->listEvents($current_page, $per_page);
        
        $data = [];
        $total_items = $events->getMeta()->getCount();
        
        // TODO: Support localization
        foreach ($events->getData() as $event) {
          $start = $event['startTime'];
          $end = $event['endTime'];
          
          $data[] = [
            "id" => $event['id'],
            "title" => $event['name']['fi'],
            "start" => $start ? $start->format('Y-m-d H:i:s') : '',
            "end" => $end ? $end->format('Y-m-d H:i:s') : ''
          ];
        }
        
        $this->items = $data;
        $this->set_pagination_args([
          'total_items' => $total_items,
          'per_page'    => $per_page,
          'total_pages' => ceil($total_items/$per_page)
        ]);
      }
       
      public function get_columns() {
        $columns = [
          'id' => 'ID',
          'title' => 'Title',
          'start' => 'Start',
          'end' => 'End'
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
      
      function column_title($item) {
        $id = $item['id'];
        $title = $item['title'];
        
        $dialogTitle = __("Are you sure?", 'linkedevents');
        $dialogContent = sprintf(__("Are you sure that you want to remove event '%s'?", 'linkedevents'), $title);
        $dialogConfirm = __("Delete", 'linkedevents');
        $dialogCancel = __("Cancel", 'linkedevents');
        
        $actions = [
          'edit' => sprintf('<a href="?page=linked-events-edit-event.php&action=%s&event=%s">' . __('Edit', 'linkedevents') . '</a>', 'edit', $id),
          'delete' => sprintf('<a data-dialog-title="%s" data-dialog-content="%s" data-dialog-confirm="%s" data-dialog-cancel="%s" class="linkedevents-delete-link" href="#" data-id="' . $id . '">' . __('Delete', 'linkedevents') . '</a>', $dialogTitle, $dialogContent, $dialogConfirm, $dialogCancel, 'delete', $id),
        ];
        
        return sprintf('%1$s%2$s',
          $title,
          $this->row_actions($actions)
      );
    }
      
    private function listEvents($page, $pageSize, $sort = null) {
      // TODO: error handling
        
        $include = null;
        $text = null;
        $lastModifiedSince = null;
        $start = null;
        $end = null;
        $bbox = null;
        $dataSource = null;
        $location = null;
        $division = null;
        $keyword = null;
        $recurring = null;
        $minDuration = null;
        $maxDuration = null;
        $publisher = null;
        
        $result = $this->eventsApi->eventList($include, $text, $lastModifiedSince, $start, $end, $bbox, $dataSource, $location, $division, $keyword, $recurring, $minDuration, $maxDuration, $publisher, $sort, $page, $pageSize);
        return $result;
      }
      
    }
    
  }
    
?>