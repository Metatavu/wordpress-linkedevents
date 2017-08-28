<?php
  namespace Metatavu\LinkedEvents\Wordpress\Settings;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  define(LINKEDEVENTS_SETTINGS_OPTION, 'linkedevents');
  define(LINKEDEVENTS_SETTINGS_GROUP, 'linkedevents');
  define(LINKEDEVENTS_SETTINGS_PAGE, 'linkedevents');
  
  if (!class_exists( 'Metatavu\LinkedEvents\Wordpress\SettingsUI' ) ) {

    class SettingsUI {

      public function __construct() {
        add_action('admin_init', array($this, 'adminInit'));
        add_action('admin_menu', array($this, 'adminMenu'));
      }

      public function adminMenu() {
        add_options_page (__( "Linked Events Settings", 'linkedevents' ), __( "Linked Events", 'linkedevents' ), 'manage_options', LINKEDEVENTS_SETTINGS_OPTION, [$this, 'settingsPage']);
      }

      public function adminInit() {
        register_setting(LINKEDEVENTS_SETTINGS_GROUP, LINKEDEVENTS_SETTINGS_PAGE);
        add_settings_section('api', __( "API Settings", 'linkedevents' ), null, LINKEDEVENTS_SETTINGS_PAGE);
        add_settings_section('gmaps', __( "Google Maps", 'linkedevents' ), null, LINKEDEVENTS_SETTINGS_PAGE);
        $this->addOption('api', 'url', 'api-url', __( "API URL", 'linkedevents'));
        $this->addOption('api', 'text', 'api-key', __( "API Key", 'linkedevents' ));
        $this->addOption('api', 'text', 'datasource', __( "Datasource", 'linkedevents' ));
        $this->addOption('api', 'text', 'publisher', __( "Publisher Organization", 'linkedevents' ));
        $this->addOption('gmaps', 'text', 'google-maps-key', __( "Google Maps Key", 'linkedevents' ));
      }

      private function addOption($group, $type, $name, $title) {
        add_settings_field($name, $title, [$this, 'createFieldUI'], LINKEDEVENTS_SETTINGS_PAGE, $group, [
          'name' => $name, 
          'type' => $type
        ]);
      }

      public function createFieldUI($opts) {
        $name = $opts['name'];
        $type = $opts['type'];
        $value = Settings::getValue($name);
        echo "<input id='$name' name='" . LINKEDEVENTS_SETTINGS_PAGE . "[$name]' size='42' type='$type' value='$value' />";
      }

      public function settingsPage() {
        if (!current_user_can('manage_options')) {
          wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        echo '<div class="wrap">';
        echo "<h2>" . __( "Linked Events", 'linkedevents') . "</h2>";
        echo '<form action="options.php" method="POST">';
        settings_fields(LINKEDEVENTS_SETTINGS_GROUP);
        do_settings_sections(LINKEDEVENTS_SETTINGS_PAGE);
        submit_button();
        echo "</form>";
        echo "</div>";
      }
    }

  }
  
  if (is_admin()) {
    $settingsUI = new SettingsUI();
  }

?>