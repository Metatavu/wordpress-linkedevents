<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/linkedevents-place-edit-view.php');
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\PlaceNew' ) ) {
    
    class PlaceNew extends PlaceEditView {
      
      public function __construct() {
        parent::__construct('linkedevents-new-place.php', __('New Place', 'linkedevents'));
        
        add_action( 'admin_menu', function () {
          add_submenu_page('linked-events.php', __('New Place', 'linkedevents'),  __('New Place', 'linkedevents'), 'manage_options', 'linkedevents-new-place.php', array($this, 'render'));
        });
      }
      
      // TODO: validate
      
      public function render() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          try {
            $place = $this->getNewPlace();

            $this->updatePlaceName($place);
            $this->updatePlaceDescription($place);
            $this->updatePlaceHomePage($place);
            $this->updatePlaceAddress($place);
            $this->updatePlaceEmail($place);
            $this->updatePlaceTelephone($place);
            $this->updatePlaceContactType($place);

            $newPlace = $this->createPlace($place);
            $newPlaceId = $newPlace->getId();
            
            $this->redirect("admin.php?page=linkedevents-edit-place.php&action=edit&place=$newPlaceId");
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
          $this->renderForm('admin.php?page=linkedevents-new-place.php');
        }
      }
      
      protected function renderFormFields() {
        $this->renderLocalizedTextInput(__('Name', 'linkedevents'), 'name', null);
        $this->renderLocalizedMemo(__('Description', 'linkedevents'), 'description', null);
        $this->renderLocalizedTextInput(__('Home page', 'linkedevents'), 'homepage', null);
        $this->renderGeoPositionInput(__('Address', 'linkedevents'), 'position', null);
        $this->renderTextInput(__('Email', 'linkedevents'), 'email', null);
        $this->renderLocalizedTextInput(__('Telephone', 'linkedevents'), 'telephone', null);
        $this->renderTextInput(__('Contact type', 'linkedevents'), 'contact-type', null);
      }
    }
    
  }

  add_action('init', function () {
    if (is_admin()) {
      new PlaceNew();
    }
  });

?>