<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\EventNew' ) ) {
    
    class EventNew extends EventEditView {
      
      public function __construct() {
        parent::__construct(__('New Event', 'linkedevents'));
        
        add_action( 'admin_menu', function () {
          add_submenu_page('linked-events.php', __('New Event', 'linkedevents'),  __('New Event', 'linkedevents'), 'manage_options', 'linkedevents-new-event.php', array($this, 'render'));
        });
      }
      
      // TODO: validate
      
      public function render() {
        $language = 'fi';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $event = $this->getNewEvent();
          $this->updateEventName($event, $language);
          $this->updateEventDescription($event,  $language);
          $this->updateEventShortDescription($event, $language);
          $this->updateEventStartTime($event, $language);
          $this->updateEventPublicationStatus($event, $language);
          $this->updateEventKeywords($event, $language);
          $this->updateEventImage($event);
          $this->updateEventLocation($event, $language);
          $this->updateEventStartTime($event);
          $this->updateEventEndTime($event);
          $newEvent = $this->createEvent($event);
          $newEventId = $newEvent->getId();
          $redirectUrl = "admin.php?page=linkedevents-edit-event.php&action=edit&event=$newEventId";
          echo '<script type="text/javascript">window.location="' . $redirectUrl . '";</script>"';
          exit;
        } else {
          $this->renderForm('admin.php?page=linkedevents-new-event.php');
        }
      }
      
      protected function renderFormFields() {
        $this->renderLocalizedTextInput(__('Name', 'linkedevents'), "name", null);
        $this->renderDateTimePicker("start", __('Start', 'linkedevents'));
        $this->renderDateTimePicker("end", __('End', 'linkedevents'));
        $this->renderEventLocation(null);
        $this->renderEventKeywords(null);
        $this->renderImageSelector('image', __('Event Image', 'linkedevents'));
        $this->renderLocalizedMemo(__('Description', 'linkedevents'), 'description', null);
        $this->renderLocalizedMemo(__('Short Description', 'linkedevents'), 'shortDescription', null);
      }
      
    }
    
  }

  add_action('init', function () {
    if (is_admin()) {
      new EventNew();
    }
  });

?>