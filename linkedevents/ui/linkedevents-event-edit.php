<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\EventEdit' ) ) {
    
    class EventEdit extends EventEditView {
      
      private $eventsApi;
      
      public function __construct() {
        parent::__construct('linkedevents-edit-event.php', __('Edit Event', 'linkedevents'));
        
        add_action( 'admin_menu', function () {
          add_submenu_page(NULL, __('Edit Event', 'linkedevents'), __('Edit Event', 'linkedevents'), 'manage_options', 'linkedevents-edit-event.php', array($this, 'render'));
        });
      }
      
      public function render() {
        $eventId = $this->getEventId();
        $event = $this->findEvent($eventId);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $validateMessage = $this->validate();
          if ($validateMessage) {
            echo '<div class="notice-error notice">' . $validateMessage . '</div>';
          } else {
            try {
              $this->updateEventName($event);
              $this->updateEventDescription($event);
              $this->updateEventShortDescription($event);
              $this->updateEventKeywords($event);
              $this->updateEventImage($event);
              $this->updateEventLocation($event);
              $this->updateEventStartTime($event);
              $this->updateEventEndTime($event);
              $this->updateEvent($event);
            } catch (\Metatavu\LinkedEvents\ApiException $e) {
              echo '<div class="error notice">';
              if ($e->getResponseBody()) {
                echo print_r($e->getResponseBody());
              } else {
                echo $e;
              }
              echo '</div>';
            }
          }
        }
        
        $this->renderForm('admin.php?page=linkedevents-edit-event.php&event=' . $eventId);
      }
      
      protected function renderFormFields() {
        $eventId = $this->getEventId();
        $event = $this->findEvent($eventId);
        $imageUrl = null;
        $images = $event->getImages();        
        if (count($images) > 0) {
          if (count($images) > 1) {
            echo '<div class="notice-warning notice">' . __('Wordpress plugin does not support multiple images. Only the first image will be used if the event is saved') . '</div>';
          }
          
          $imageUrl = $images[0]->getUrl();
        }
       
        $this->renderLocalizedTextInput(__('Name', 'linkedevents'), "name", $event->getName());
        $this->renderDateTimePicker("start", __('Start', 'linkedevents'), $event->getStartTime() ? $event->getStartTime()->getTimestamp() : null);
        $this->renderDateTimePicker("end", __('End', 'linkedevents'), $event->getEndTime() ? $event->getEndTime()->getTimestamp() : null);
        $this->renderEventLocation($event);
        $this->renderEventKeywords($event);
        $this->renderImageSelector('image', __('Event Image', 'linkedevents'), $imageUrl);
        $this->renderLocalizedMemo(__('Description', 'linkedevents'), 'description', $event->getDescription());
        $this->renderLocalizedMemo(__('Short Description', 'linkedevents'), 'shortDescription', $event->getShortDescription());
      }
      
      private function getEventId() {
        return sanitize_text_field($_GET['event']);
      }
      
    }
    
  }
  
  add_action('init', function () {
    if (is_admin()) {
      new EventEdit();
    }
  });
  
?>