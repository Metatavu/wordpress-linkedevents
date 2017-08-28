<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\PlaceEditView' ) ) {
    
    class PlaceEditView extends AbstractEditView {
      
      private $filterApi;
      
      public function __construct($pageTitle) {
        parent::__construct($pageTitle);
        $this->filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi();
      }
      
      /**
       * Creates new place
       * 
       * @param \Metatavu\LinkedEvents\Model\Place $place
       * @return \Metatavu\LinkedEvents\Model\Place created place
       */
      protected function createPlace($place) {
        return $this->filterApi->placeCreate($place);
      }
      
      /**
       * Updates place
       * 
       * @param \Metatavu\LinkedEvents\Model\Place $place
       * @return \Metatavu\LinkedEvents\Model\Place updated place
       */
      protected function updatePlace($place) {
        return $this->filterApi->placeUpdate($place->getId(), $place);
      }
      
      /**
       * Creates new prefilled place object 
       * 
       * @return \Metatavu\LinkedEvents\Model\Place created event object
       */
      protected function getNewPlace() {
        $place = new \Metatavu\LinkedEvents\Model\Place();
        $place->setName(new \Metatavu\LinkedEvents\Model\PlaceName());
        $place->setDescription(new \Metatavu\LinkedEvents\Model\PlaceDescription());
        $place->setStreetAddress(new \Metatavu\LinkedEvents\Model\PlaceStreetAddress());
        $place->setAddressLocality(new \Metatavu\LinkedEvents\Model\PlaceAddressLocality());
        $place->setPosition(new \Metatavu\LinkedEvents\Model\PlacePosition());
        $place->setTelephone(new \Metatavu\LinkedEvents\Model\PlaceTelephone());
        $place->setInfoUrl(new \Metatavu\LinkedEvents\Model\PlaceInfoUrl());
        $place->setOriginId(uniqid());
        $place->setDeleted(false);
        
        $place->setDataSource(\Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("datasource"));
        $place->setPublisher(\Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("publisher"));
        
        return $place; 
      }
      
      /**
       * Updates place name into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Place $place
       */
      protected function updatePlaceName($place) {
        $name = $place->getName();
        $name->setFi($this->getLocalizedPostString('name', "fi"));
        $name->setSv($this->getLocalizedPostString('name', "sv"));
        $name->setEn($this->getLocalizedPostString('name', "en"));
        $place->setName($name);
      }
      
      /**
       * Updates place description into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Place $place
       */
      protected function updatePlaceDescription($place) {
        $description = $place->getDescription();
        $description->setFi($this->getLocalizedPostString('description', 'fi'));
        $description->setSv($this->getLocalizedPostString('description', 'sv'));
        $description->setEn($this->getLocalizedPostString('description', 'en'));
        $place->setDescription($description);
      }
      
      /**
       * Updates place home page into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Place $place
       */
      protected function updatePlaceHomePage($place) {
        $place->getInfoUrl()->setFi($this->getLocalizedPostString('homepage', 'fi'));
        $place->getInfoUrl()->setSv($this->getLocalizedPostString('homepage', 'sv'));
        $place->getInfoUrl()->setEn($this->getLocalizedPostString('homepage', 'en'));
      }
      
      /**
       * Updates place address page into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Place $place
       */
      protected function updatePlaceAddress($place) {
        $place->setAddressCountry($this->getPostString('position-street-country'));
        $place->setAddressRegion($this->getPostString('position-address-region'));
        $place->setPostOfficeBoxNum($this->getPostString('position-po-box'));
        $place->setPostalCode($this->getPostString('position-postal-code'));
        
        $place->getStreetAddress()->setFi($this->getLocalizedPostString('position-street-address', 'fi'));
        $place->getStreetAddress()->setSv($this->getLocalizedPostString('position-street-address', 'sv'));
        $place->getStreetAddress()->setEn($this->getLocalizedPostString('position-street-address', 'en'));
        
        $place->getAddressLocality()->setFi($this->getLocalizedPostString('position-address-locality', 'fi'));
        $place->getAddressLocality()->setSv($this->getLocalizedPostString('position-address-locality', 'sv'));
        $place->getAddressLocality()->setEn($this->getLocalizedPostString('position-address-locality', 'en'));
        
        $latitude = $this->getPostFloat('position-latitude');
        $longitude = $this->getPostFloat('position-longitude');
        $place->setPosition(new \Metatavu\LinkedEvents\Model\PlacePosition());
        
        if ($latitude && $longitude) {
          $place->getPosition()->setCoordinates([$latitude, $longitude]);
          $place->getPosition()->setType("Point");
        } else {
          $place->setPosition(null);
        }
      }
      
      /**
       * Updates place email into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Place $place
       */
      protected function updatePlaceEmail($place) {
        $place->setEmail($this->getPostString('email'));
      }
      
      /**
       * Updates place telephone into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Place $place
       */
      protected function updatePlaceTelephone($place) {
        $place->getTelephone()->setFi($this->getLocalizedPostString('telephone', 'fi'));
        $place->getTelephone()->setSv($this->getLocalizedPostString('telephone', 'sv'));
        $place->getTelephone()->setEn($this->getLocalizedPostString('telephone', 'en'));
      }
      
      /**
       * Updates place contact type into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Place $place
       */
      protected function updatePlaceContactType($place) {
        $place->setContactType($this->getPostString('contact-type'));
      }

    }
  }
    
?>

      
      