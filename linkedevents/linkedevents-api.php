<?php

  namespace Metatavu\LinkedEvents\Wordpress;
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  require_once( __DIR__ . '/../settings/settings.php');
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\Api' ) ) {
    
    /**
     * LinkedEvents Api class  
     */
    class Api {
      
      /**
       * Creates new Events API instance
       * 
       * @return \Metatavu\LinkedEvents\Client\EventApi API instance
       */
      public static function getEventApi() {
        $client = self::getClient();
        return new \Metatavu\LinkedEvents\Client\EventApi($client);
      }
      
      /**
       * Creates new Filter API instance
       * 
       * @return \Metatavu\LinkedEvents\Client\FilterApi API instance
       */
      public static function getFilterApi() {
        $client = self::getClient();
        return new \Metatavu\LinkedEvents\Client\FilterApi($client);
      }
      
      /**
       * Creates new Image API instance
       * 
       * @return \Metatavu\LinkedEvents\Client\ImageApi API instance
       */
      public static function getImageApi() {
        $client = self::getClient();
        return new \Metatavu\LinkedEvents\Client\ImageApi($client);
      }
      
      /**
       * Creates new Language API instance
       * 
       * @return \Metatavu\LinkedEvents\Client\LanguageApi API instance
       */
      public static function getLanguageApi() {
        $client = self::getClient();
        return new \Metatavu\LinkedEvents\Client\LanguageApi($client);
      }
      
      /**
       * Creates new Search API instance
       * 
       * @return \Metatavu\LinkedEvents\Client\SearchApi API instance
       */
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