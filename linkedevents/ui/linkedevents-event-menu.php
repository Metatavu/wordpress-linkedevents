<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  require_once( __DIR__ . '/linkedevents-event-table.php');
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\EventMenu' ) ) {
    
    class EventMenu {
      
      public function __construct() {
        add_action( 'admin_menu', function () {
          add_menu_page(__('Events', 'linkedevents'), __('Events', 'linkedevents'), 'manage_options', 'linked-events.php', array($this, 'renderList'), 'dashicons-calendar-alt', 50);
        });
      }
      
      public function renderList() {
        
        $table = new EventsTable();
        $table->prepare_items();
        $table->display();
      }
      
    }
    
  }
  
  add_action('init', function () {
    if (is_admin()) {
      new EventMenu();
    }
  });

  
    
?>