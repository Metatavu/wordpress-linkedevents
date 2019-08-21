<?php

  namespace Metatavu\LinkedEvents\Wordpress;
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  require_once( __DIR__ . '/../settings/settings.php');
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\IdRefController' ) ) {
    
    /**
     * LinkedEvents id ref controller 
     */
    class IdRefController {
      
      /**
       * Returns IdRef array for keyword ids
       * 
       * @param type $keywordIds keyword ids
       * @return \Metatavu\LinkedEvents\Model\IdRef[] keyword IdRefs
       */
      public static function getKeywordRefs($keywordIds) {
        $result = [];
        
        foreach ($keywordIds as $keywordId) {
          $result[] = self::getKeywordRef($keywordId);  
        }
        
        return $result;
      }
      
      /**
       * Returns reference into the keyword
       * 
       * @param string $keywordId keyword id
       * @return \Metatavu\LinkedEvents\Model\IdRef reference into the keyword
       */
      public static function getKeywordRef($keywordId) {
        return self::getIdRef(self::getApiUrl() . "/keyword/$keywordId/");
      }
      
      /**
       * Returns reference into the location
       * 
       * @param string $locationId location id
       * @return \Metatavu\LinkedEvents\Model\IdRef reference into the location
       */
      public static function getPlaceRef($locationId) {
        return self::getIdRef(self::getApiUrl() . "/place/$locationId/");
      }
      
      /**
       * Returns reference into the image
       * 
       * @param string $id image id
       * @return \Metatavu\LinkedEvents\Model\IdRef reference into the image
       */
      public static function getImageRef($id) {
        return self::getIdRef(self::getApiUrl() . "/image/$id/");
      }
      
      /**
       * Returns IdRef object for id
       * 
       * @param string $id id
       * @return \Metatavu\LinkedEvents\Model\IdRef IdRef
       */
      public static function getIdRef($id) {
        $idRef = new \Metatavu\LinkedEvents\Model\IdRef();
        $idRef->setId($id);
        return $idRef;
      }
      
      /**
       * Extracts ids from IdRef array
       * 
       * @param \Metatavu\LinkedEvents\Model\IdRef $idRef
       * @return string[] ids
       */
      public static function extractIdRefIds($idRefs) {
        $result = [];
        
        foreach ($idRefs as $idRef) {
          $result[] = self::extractIdRefId($idRef);  
        }
        
        return $result;
      }
      
      /**
       * Extracts id from IdRef
       * 
       * @param \Metatavu\LinkedEvents\Model\IdRef $idRef
       * @return string id
       */
      public static function extractIdRefId($idRef) {
        if (isset($idRef)) {
          $id = rtrim($idRef->getId(), '/');
          $parts = explode("/", $id);
          return $parts[count($parts) - 1];
        }
        
        return null;
      }
      
      /**
       * Returns API URL
       * 
       * @return string API URL
       */
      private static function getApiUrl() {
        return rtrim(\Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("api-url"), "/");
      }
    }  
  }
    
?>