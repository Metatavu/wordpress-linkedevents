<?php
/*
 * Created on Aug 22, 2018
 * Plugin Name: Linked Events
 * Description: Wordpress plugin to manage Linked Events API
 * Version: 1.0.1
 * Author: Metatavu Oy
 */

  defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );

  require_once( __DIR__ . '/linkedevents/linkedevents.php');
  require_once( __DIR__ . '/settings/settings.php');

  add_action('plugins_loaded', function() {
    load_plugin_textdomain('linkedevents', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
  });
  
?>
