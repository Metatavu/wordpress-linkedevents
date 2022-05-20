<?php

  namespace Metatavu\LinkedEvents\Wordpress;
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  require_once( __DIR__ . '/../settings/settings.php');
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\Capabilities' ) ) {
  
    class Capabilities {
      
      private static $capabilities = [
        'linkedevents_edit_events', 
        'linkedevents_edit_places', 
        'linkedevents_edit_keywords', 
        'linkedevents_new_event', 
        'linkedevents_new_keyword', 
        'linkedevents_new_place', 
        'linkedevents_delete_event', 
        'linkedevents_delete_keyword', 
        'linkedevents_delete_place'
      ];
              
      public static function activationHook() {
        self::addCapabilities('administrator');
        self::addCapabilities('editor');
        self::addCapabilities('linkedevents');
      }
      
      public static function deactivationHook() {
        self::removeCapabilities('administrator');
        self::removeCapabilities('editor');
      }
      
      private static function addCapabilities($roleName) {
        $role = get_role($roleName);
        
        foreach (self::$capabilities as $capability) {
          $role->add_cap($capability, true);  
        }
      }
      
      private static function removeCapabilities($roleName) {
        $role = get_role($roleName);        
        foreach (self::$capabilities as $capability) {
          $role->remove_cap($capability);
        }
      }
        
    }
  }

?>