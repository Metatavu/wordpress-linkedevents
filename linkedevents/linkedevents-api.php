<?php

  namespace Metatavu\LinkedEvents\Wordpress;
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  require_once( __DIR__ . '/../settings/settings.php');
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\Api' ) ) {
    
     class Api {
      
      public static function getEventApi() {
        $client = self::getClient();
        return new \Metatavu\LinkedEvents\Client\EventApi($client);
      }
      
      public static function getFilterApi() {
        $client = self::getClient();
        return new \Metatavu\LinkedEvents\Client\FilterApi($client);
      }
      
      public static function getImageApi() {
        $client = self::getClient();
        return new \Metatavu\LinkedEvents\Client\ImageApi($client);
      }
      
      public static function getLanguageApi() {
        $client = self::getClient();
        return new \Metatavu\LinkedEvents\Client\LanguageApi($client);
      }
      
      public static function getSearchApi() {
        $client = self::getClient();
        return new \Metatavu\LinkedEvents\Client\SearchApi($client);
      }
      
      private function getClient() {
        return new \Metatavu\LinkedEvents\ApiClient(self::getConfiguration());
      }
      
      private function getConfiguration() {
       $result = \Metatavu\LinkedEvents\Configuration::getDefaultConfiguration();
       $result->setHost(\Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("api-url"));
       $result->addDefaultHeader('apikey', \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("api-key"));
       return $result;
      }
      
    }
  }

?>