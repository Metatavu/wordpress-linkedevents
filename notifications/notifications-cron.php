<?php

  namespace Metatavu\LinkedEvents\Wordpress\Notifications;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\Notifications\NotificationCron' ) ) {
    
    class NotificationCron {
      
      private $eventsApi;
      
      public function __construct() {
        $this->eventsApi = \Metatavu\LinkedEvents\Wordpress\Api::getEventApi(true);
        add_action('linkedeventsDraftNotificationUpdateHook', [ $this, 'onUpdateHook' ]);
        if (!wp_next_scheduled('linkedeventsDraftNotificationUpdateHook')) {
          wp_schedule_event(time(), 'hourly', 'linkedeventsDraftNotificationUpdateHook');
        }
      }
      
      /**
       * Action hook executed on hourly interval
       */
      public function onUpdateHook() {
        $events = $this->listEvents();
        foreach ($events->getData() as $event) {
          if ($event->getPublicationStatus() === 'draft') {
            \Metatavu\LinkedEvents\Wordpress\Notifications\Notifier::notifyDraft($event);
          }
        }
      }
      
      /**
       * Lists events from API
       * 
       * @return \Metatavu\LinkedEvents\Model\Event[] events
       */
      private function listEvents() {
        $include = null;
        $text = null;
        $lastModifiedSince = new \DateTime();
        $start = null;
        $end = null;
        $bbox = null;
        $dataSource = \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("datasource");
        $location = null;
        $showAll = false;
        $division = null;
        $keyword = null;
        $recurring = null;
        $minDuration = null;
        $maxDuration = null;
        $publisher = null;
        $sort = null;
        $page = null;
        $pageSize = null;
        $lastModifiedSince->sub(new \DateInterval('PT02H00M'));
        
        return $this->eventsApi->eventList($include, $text, $lastModifiedSince, $start, $end, $bbox, $dataSource, $location, $showAll, $division, $keyword, $recurring, $minDuration, $maxDuration, $publisher, $sort, $page, $pageSize);
      }
    }
    
  }
  
  new NotificationCron();
    
?>