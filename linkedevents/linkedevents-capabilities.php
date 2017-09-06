<?php

  namespace Metatavu\LinkedEvents\Wordpress;
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  require_once( __DIR__ . '/../settings/settings.php');
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\Capabilities' ) ) {
  
    class Capabilities {
        
      public function __construct() {
        error_log("Hopsanssaaa!");
        
        $this->addCapabilities('administrator');
        $this->addCapabilities('editor');
      }
      
      private function addCapabilities($roleName) {
        $role = get_role($roleName);
        $role->add_cap('linkedevents_edit_events', true);  
        $role->add_cap('linkedevents_edit_places', true);  
        $role->add_cap('linkedevents_edit_keywords', true);
        $role->add_cap('linkedevents_new_event', true);
        $role->add_cap('linkedevents_new_keyword', true);
        $role->add_cap('linkedevents_new_place', true);
      }
        
    }
  }

  new Capabilities();

?>