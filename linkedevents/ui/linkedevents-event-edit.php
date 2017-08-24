<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\EventEdit' ) ) {
    
    class EventEdit extends EventEditView {
      
      private $eventsApi;
      private $supportedLanguages = ["fi"];
      
      public function __construct() {
        parent::__construct(__('Edit Event', 'linkedevents'));
        
        add_action( 'admin_menu', function () {
          add_submenu_page(NULL, __('Edit Event', 'linkedevents'), __('Edit Event', 'linkedevents'), 'manage_options', 'linked-events-edit-event.php', array($this, 'render'));
        });
      }
      
      public function validate() {
        $message = $this->validateStartTime();
        if ($message) {
          return $message;
        }
        
        return null;
      }
      
      public function render() {
        $eventId = $this->getEventId();
        $event = $this->findEvent($eventId);
        $language = 'fi';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $validateMessage = $this->validate();
          if ($validateMessage) {
            echo '<div class="notice-error notice">' . $validateMessage . '</div>';
          } else {
            $this->updateEventName($event, $language);
            $this->updateEventDescription($event, $language);
            $this->updateEventShortDescription($event, $language);
            $this->updateEventKeywords($event);
            $this->updateEventLocation($event);
            $this->updateEventStartTime($event);
            $this->updateEventEndTime($event);
            $this->updateEvent($event);
          }
        }
        
        $this->renderForm('admin.php?page=linked-events-edit-event.php&event=' . $eventId);
      }
      
      protected function renderFormFields() {
        $eventId = $this->getEventId();
        $event = $this->findEvent($eventId);
        $language = 'fi';
        
        $this->renderEventName($event, $language);
        $this->renderDateTimePicker("start", __('Start', 'linkedevents'), $event->getStartTime() ? $event->getStartTime()->getTimestamp() : null);
        $this->renderDateTimePicker("end", __('End', 'linkedevents'), $event->getEndTime() ? $event->getEndTime()->getTimestamp() : null);
        $this->renderEventLocation($event);
        $this->renderEventKeywords($event);
        $this->renderMemo(__('Description', 'linkedevents'), 'description', $event, $language);
        $this->renderMemo(__('Short Description', 'linkedevents'), 'shortDescription', $event, $language);
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