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
       * @param boolean $authenticated if true, prints public and draft events with false prints only public
       *
       * @return \Metatavu\LinkedEvents\Client\EventApi API instance
       */
      public static function getEventApi($authenticated) {
        $client = self::getClient($authenticated);
        return new \Metatavu\LinkedEvents\Client\EventApi($client);
      }
      
      /**
       * Creates new Filter API instance
       * 
       * @param boolean $authenticated if true, prints public and draft events with false prints only public
       *
       * @return \Metatavu\LinkedEvents\Client\FilterApi API instance
       */
      public static function getFilterApi($authenticated) {
        $client = self::getClient($authenticated);
        return new \Metatavu\LinkedEvents\Client\FilterApi($client);
      }
      
      /**
       * Creates new Image API instance
       * 
       * @param boolean $authenticated if true, prints public and draft events with false prints only public
       * 
       * @return \Metatavu\LinkedEvents\Client\ImageApi API instance
       */
      public static function getImageApi($authenticated) {
        $client = self::getClient($authenticated);
        return new \Metatavu\LinkedEvents\Client\ImageApi($client);
      }
      
      /**
       * Creates new Language API instance
       * 
       * @param boolean $authenticated if true, prints public and draft events with false prints only public
       * 
       * @return \Metatavu\LinkedEvents\Client\LanguageApi API instance
       */
      public static function getLanguageApi($authenticated) {
        $client = self::getClient($authenticated);
        return new \Metatavu\LinkedEvents\Client\LanguageApi($client);
      }
      
      /**
       * Creates new Search API instance
       * 
       * @param boolean $authenticated if true, prints public and draft events with false prints only public
       * 
       * @return \Metatavu\LinkedEvents\Client\SearchApi API instance
       */
      public static function getSearchApi($authenticated) {
        $client = self::getClient($authenticated);
        return new \Metatavu\LinkedEvents\Client\SearchApi($client);
      }
      
      /**
       * Returns new LinkedEvents client
       * 
       * @param boolean $authenticated if true, prints public and draft events with false prints only public
       *
       * @return \Metatavu\LinkedEvents\ApiClient new LinkedEvents client
       */
      private function getClient($authenticated) {
        return new \Metatavu\LinkedEvents\ApiClient(self::getConfiguration($authenticated));
      }
      
      /**
       * Returns LinkedEvents client configuration
       * 
       * @param boolean $authenticated if true, prints public and draft events with false prints only public
       * 
       * @return \Metatavu\LinkedEvents\Configuration LinkedEvents client configuration
       */
      private function getConfiguration($authenticated) {
        $result = new \Metatavu\LinkedEvents\Configuration();
        $result->setHost(\Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("api-url"));
        
        if ($authenticated) {
          $result->addDefaultHeader('apikey', \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("api-key"));
        }

        return $result;
      }
      
    }
  }

?>