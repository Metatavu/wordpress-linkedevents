<?php
  namespace Metatavu\LinkedEvents;
  
  if (!defined('ABSPATH')) { 
    exit;
  }

  require_once(constant("LINKEDEVENTS_PLUGIN_DIR") . '/dependencies/classes/gamajo/template-loader/class-gamajo-template-loader.php');
  
  if (!class_exists( 'Metatavu\LinkedEvents\TemplateLoader' ) ) {
    
    /**
     * Template loader for LinkedEvents
     */
    class TemplateLoader extends \LinkedEvents_Gamajo_Template_Loader {

      /**
       * Constructor
       */
      public function __construct() {
        $this->filter_prefix = 'linkedevents';
        $this->theme_template_directory = 'linkedevents';
        $this->plugin_directory = LINKEDEVENTS_PLUGIN_DIR;
        $this->plugin_template_directory = 'default-templates';
      }
    }
  }
  
?>
