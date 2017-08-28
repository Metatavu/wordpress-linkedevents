<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  require_once( __DIR__ . '/linkedevents-event-table.php');
  require_once( __DIR__ . '/linkedevents-place-table.php');
  require_once( __DIR__ . '/linkedevents-keywords-table.php');
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\EventMenu' ) ) {
    
    class EventMenu {
      
      public function __construct() {
        add_action( 'admin_menu', function () {
          add_menu_page(__('Events', 'linkedevents'), __('Events', 'linkedevents'), 'manage_options', 'linked-events.php', array($this, 'renderEventList'), 'dashicons-calendar-alt', 50);
          add_submenu_page('linked-events.php', __('Places', 'linkedevents'),  __('Places', 'linkedevents'), 'manage_options', 'linkedevents-places.php', array($this, 'renderPlaceList'));
          add_submenu_page('linked-events.php', __('Keywords', 'linkedevents'),  __('Keywords', 'linkedevents'), 'manage_options', 'linkedevents-keywords.php', array($this, 'renderKeywordList'));
        });
      }
      
      public function renderEventList() {
        $table = new EventsTable();
        $table->prepare_items();
        $table->display();
      }
      
      public function renderPlaceList() {
        $table = new PlacesTable();
        $table->prepare_items();
        $table->display();
      }
      
      public function renderKeywordList() {
        $table = new KeywordsTable();
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