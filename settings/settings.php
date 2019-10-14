<?php
  namespace Metatavu\LinkedEvents\Wordpress\Settings;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once('settings-ui.php');  
  
  define("LINKEDEVENTS_SETTINGS_OPTION", 'linkedevents');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\Settings\Settings' ) ) {

    class Settings {

      /**
       * Returns array of supported language
       * 
       * @return array array of supported language
       */
      public static function getSupportedLangauges() {
        return ["fi", "sv", "en"];
      }

      /**
       * Returns setting value
       * 
       * @param string $name setting name
       * @return string setting value
       */
      public static function getValue($name) {
        $options = get_option(LINKEDEVENTS_SETTINGS_OPTION);
        if ($options) {
          return $options[$name];
        }

        return null;
      }
      
      /**
       * Sets a value for settings
       * 
       * @param string $name setting name
       * @param string $value setting value
       */
      public static function setValue($name, $value) {
        $options = get_option(LINKEDEVENTS_SETTINGS_OPTION);
        if (!$options) {
          $options = [];
        } 
        
        $options[$name] = $value;
        
        update_option(LINKEDEVENTS_SETTINGS_OPTION, $options);
      }
      
    }

  }
  

?>