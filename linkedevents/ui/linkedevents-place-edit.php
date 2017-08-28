<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/linkedevents-place-edit-view.php');
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\PlaceEdit' ) ) {
    
    class PlaceEdit extends PlaceEditView {
      
      public function __construct() {
        parent::__construct(__('Edit Place', 'linkedevents'));
        
        add_action( 'admin_menu', function () {
          add_submenu_page(null, __('Edit Place', 'linkedevents'),  __('Edit Place', 'linkedevents'), 'manage_options', 'linkedevents-edit-place.php', array($this, 'render'));
        });
      }
      
      // TODO: validate
      
      public function render() {
        $placeId = $this->getPlaceId();
            
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          try {
            $place = $this->findPlace($placeId);
            $place->setDataSource(null);
            $place->setPublisher(null);
            $this->updatePlaceName($place);
            $this->updatePlaceDescription($place);
            $this->updatePlaceHomePage($place);
            $this->updatePlaceAddress($place);
            $this->updatePlaceEmail($place);
            $this->updatePlaceTelephone($place);
            $this->updatePlaceContactType($place);
            $this->updatePlace($place);
            $this->redirect("admin.php?page=linkedevents-edit-place.php&action=edit&place=$placeId");
            exit;
          } catch (\Metatavu\LinkedEvents\ApiException $e) {
            echo '<div class="error notice">';
            if ($e->getResponseBody()) {
              echo print_r($e->getResponseBody());
            } else {
              echo $e;
            }
            echo '</div>';
          }
        } else {
          $this->renderForm('admin.php?page=linkedevents-edit-place.php&place=' . $placeId);
        }
      }
      
      protected function renderFormFields() {
        $placeId = $this->getPlaceId();
        $place = $this->findPlace($placeId);
         
        $this->renderLocalizedTextInput(__('Name', 'linkedevents'), 'name', $place->getName());
        $this->renderLocalizedMemo(__('Description', 'linkedevents'), 'description', $place->getDescription());
        $this->renderLocalizedTextInput(__('Home page', 'linkedevents'), 'homepage', $place->getInfoUrl());
        
        $this->renderGeoPositionInput(__('Address', 'linkedevents'), 'position', 
          $place->getStreetAddress(), $place->getPostalCode(), $place->getAddressLocality(), $place->getAddressRegion(), 
          $place->getPostOfficeBoxNum(), $place->getPosition() ? $place->getPosition()->getCoordinates() : null);
        
        $this->renderTextInput(__('Email', 'linkedevents'), 'email', $place->getEmail());
        $this->renderLocalizedTextInput(__('Telephone', 'linkedevents'), 'telephone', $place->getTelephone());
        $this->renderTextInput(__('Contact type', 'linkedevents'), 'contact-type', $place->getContactType());
      }
      
      private function getPlaceId() {
        return sanitize_text_field($_GET['place']);
      }
    }
    
  }

  add_action('init', function () {
    if (is_admin()) {
      new PlaceEdit();
    }
  });

?>