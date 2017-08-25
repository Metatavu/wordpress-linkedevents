<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  require_once( __DIR__ . '/linkedevents-event-table.php');
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\EventMenu' ) ) {
    
    class EventMenu {
      
      public function __construct() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-dialog', null, ['jquery']);
        wp_enqueue_script('linkedevents-event-table', plugin_dir_url(__FILE__) . 'linkedevents-event-table.js', null, ['jquery-ui-dialog' ]);
        
        wp_register_style('jquery-ui', 'https://cdn.metatavu.io/libs/jquery-ui/1.12.1/jquery-ui.min.css');
        wp_enqueue_style('jquery-ui');
        
        add_action( 'admin_menu', function () {
          add_menu_page(__('Events'), __('Events'), 'manage_options', 'linked-events.php', array($this, 'renderList'));
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