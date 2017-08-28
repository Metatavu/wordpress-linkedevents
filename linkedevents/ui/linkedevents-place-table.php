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
      
      public function prepare_items() {
        $this->_column_headers = [ $this->get_columns(), $this->get_hidden_columns(), $this->get_sortable_columns() ];
        $this->process_bulk_action();
        $places = $this->listPlaces($this->get_pagenum(), $this->perPage);
        
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
        
        $actions = [
          'edit' => sprintf('<a href="?page=linkedevents-edit-place.php&action=%s&event=%s">' . __('Edit', 'linkedevents') . '</a>', 'edit', $id),
          'delete' => sprintf('<a data-action="linkedevents_delete_place" data-dialog-title="%s" data-dialog-content="%s" data-dialog-confirm="%s" data-dialog-cancel="%s" class="linkedevents-delete-link" href="#" data-id="' . $id . '">' . __('Delete', 'linkedevents') . '</a>', $dialogTitle, $dialogContent, $dialogConfirm, $dialogCancel, 'delete', $id),
        ];
        
        return sprintf('%1$s%2$s',
          $title,
          $this->row_actions($actions)
        );
      }
      
      private function listPlaces($page, $pageSize, $sort = null) {
        $showAllPlaces = true;
        $division = null;
        $text = null;
        
        return $this->filterApi->placeList($page, $pageSize, $showAllPlaces, $division, $text, $sort);
      }
    }
    
  }
    
?>