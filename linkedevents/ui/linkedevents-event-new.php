<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\EventNew' ) ) {
    
    class EventNew extends EventEditView {
      
      public function __construct() {
        parent::__construct('linkedevents-new-event.php', __('New Event', 'linkedevents'));
        
        add_action( 'admin_menu', function () {
          add_submenu_page('linked-events.php', __('New Event', 'linkedevents'),  __('New Event', 'linkedevents'), 'linkedevents_new_event', 'linkedevents-new-event.php', array($this, 'render'));
        });
      }
      
      /**
       * Renders view
       */
      public function render() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $validateMessage = $this->validate();
          if ($validateMessage) {
            echo '<div class="notice-error notice">' . $validateMessage . '</div>';
          } else {
            $event = $this->getNewEvent();
            $this->updateEventName($event);
            $this->updateEventDescription($event);
            $this->updateEventShortDescription($event);
            $this->updateEventStartTime($event);
            $this->updateEventPublicationStatus($event);
            $this->updateEventKeywords($event);
            $this->updateEventImage($event);
            $this->updateEventLocation($event);
            $this->updateEventStartTime($event);
            $this->updateEventEndTime($event);
            $newEvent = $this->createEvent($event);
            
            if ($newEvent && $newEvent->getId()) {
              $newEventId = $newEvent->getId();
              $this->addNotificationSuppressedEventId($newEventId);
              $redirectUrl = "admin.php?page=linkedevents-edit-event.php&action=edit&event=$newEventId";
              echo '<script type="text/javascript">window.location="' . $redirectUrl . '";</script>"';
              exit;
            }
            
          }
        }
         
        $this->renderForm('admin.php?page=linkedevents-new-event.php');
      }
      
      /**
       * Renders form fields
       */
      protected function renderFormFields() {
        $this->renderPublicationStatus(null);
        $this->renderLocalizedTextInput(__('Name', 'linkedevents'), "name", null);
        $this->renderDatePicker("start-date", __('Start Date', 'linkedevents'), true);
        $this->renderTimePicker("start-time", __('Start Time', 'linkedevents'), false);
        $this->renderDatePicker("end-date", __('End Date', 'linkedevents'), false);
        $this->renderTimePicker("end-time", __('End Time', 'linkedevents'), false);
        $this->renderEventLocation(null);
        $this->renderEventKeywords(null);
        $this->renderImageSelector('image', __('Event Image', 'linkedevents'), null);
        $this->renderLocalizedMemo(__('Description', 'linkedevents'), 'description', null);
        $this->renderLocalizedMemo(__('Short Description', 'linkedevents'), 'shortDescription', null);
      }
      
      /**
       * Suppresses notifications for the event id
       * 
       * @param string $eventId event id
       */
      private function addNotificationSuppressedEventId($eventId) {
        $eventIds = \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("notification-suppressed-events");
        if (!$eventIds) {
          $eventIds = [];
        }
        
        $eventIds[] = $eventId;
        \Metatavu\LinkedEvents\Wordpress\Settings\Settings::setValue("notification-suppressed-events", $eventIds);
      }
      
    }
    
  }

  add_action('init', function () {
    if (is_admin()) {
      new EventNew();
    }
  });

?>