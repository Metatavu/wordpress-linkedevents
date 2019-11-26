<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\EventEditView' ) ) {
    
    class EventEditView extends AbstractEditView {
      
      private $eventsApi;
      
      public function __construct($targetPage, $pageTitle) {
        parent::__construct($targetPage, $pageTitle);
        $this->eventsApi = \Metatavu\LinkedEvents\Wordpress\Api::getEventApi();
      }
        
      /**
       * Renders autocomplete component for editing event location
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      protected function renderEventLocation($event) {
        $value = $_REQUEST['location'];
        $label = null;
        
        if (!$value && $event) {
          $location = $event->getLocation();
          $value = $this->extractIdRefId($location);
        }

        if ($value) {
          $place = $this->findPlace($value);
          $label = $place->getName()->getFi();
        }

        $this->renderAutocomplete("location", __('Location', 'linkedevents'), 'linkedevents_places', [
          label => $label,
          value => $value
        ]);
      }
      
      /**
       * Renders multiselect component for editing event keywords
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      protected function renderEventKeywords($event) {
        $values = [];
        $requestValues = $_REQUEST['keywords'];
        if (isset($requestValues)) {
          $keywordIds = $requestValues ? explode(",", $requestValues) : [];
        } else {
          $keywordIds = isset($event) ? $this->extractIdRefIds($event->getKeywords()) : [];
        }

        foreach ($keywordIds as $keywordId) {
          $keyword = $this->findKeyword($keywordId);
          $values[] = [
            label => $keyword->getName()->getFi(),
            value => $keyword->getId()
          ];
        }
        
        $this->renderMultivalueAutocomplete('keywords', __('Keywords', 'linkedevents'), 'linkedevents_keywords', $values);
      }
      
      /**
       * Renders publication status field
       * 
       * @param type $publicationStatus publication status
       */
      protected function renderPublicationStatus($publicationStatus) {
        $this->renderSelect('publication-status', __('Publication Status', 'linkedevents'), [
          [
            'value' => 'draft',
            'label' => __('Draft', 'linkedevents'),
            'selected' => $publicationStatus === 'draft'
          ], 
          [
            'value' => 'public',
            'label' => __('Public', 'linkedevents'),
            'selected' => $publicationStatus === 'public'
          ]
        ]);
      }
      
      /**
       * Updates custom_data into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      protected function updateCustomData($event) {
        $customData = $event->getCustomData();
        foreach($customData as $key => $value) {
          $fieldName = "custom_data-$key";
          $customData[$key] = $this->getPostString($fieldName);
        }
        $event->setCustomData($customData);
      }


      /**
       * Updates event name into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      protected function updateEventName($event) {
        $name = $event->getName();
        $name->setFi($this->getLocalizedPostString('name', "fi"));
        $name->setSv($this->getLocalizedPostString('name', "sv"));
        $name->setEn($this->getLocalizedPostString('name', "en"));
        $event->setName($name);
      }
      
      /**
       * Updates event description into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      protected function updateEventDescription($event) {
        $description = $event->getDescription();
        
        foreach ($this->getSupportedLanguages() as $language) {
          $description[$language] = $this->getLocalizedRawPostString('description', $language);
        }
        
        $event->setDescription($description);
      }
      
      /**
       * Updates event short description into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      protected function updateEventShortDescription($event) {
        $shortDescription = $event->getShortDescription();
        
        foreach ($this->getSupportedLanguages() as $language) {
          $shortDescription[$language] = $this->getLocalizedRawPostString('shortDescription', $language);
        }
        
        $event->setShortDescription($shortDescription);
      }
      
      /**
       * Updates event image into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      protected function updateEventImage($event) {
        $imageUrl = $this->getPostString('image');
        $images = $event->getImages();
        $imageFound = false;
        $imageRefs = [];
        
        foreach ($images as $image) {
          if ($image->getUrl() == $imageUrl) {
            $imageFound = true;
          }
          
          $imageRefs[] = $this->getImageRef($image->getId());
        }
        
        if ($imageFound) {
          $event->setImages($imageRefs);
          return;
        }
        
        if (!empty($imageUrl)) {
          $wordpressImageId = attachment_url_to_postid($imageUrl);
          if ($wordpressImageId) {
            $linkedEventsImageId = get_post_meta($wordpressImageId, 'linkedevents-imageid', true);
            if ($linkedEventsImageId) {
              $event->setImages([$this->getImageRef($linkedEventsImageId)]);
              return;
            }
          }
          
          $image = $this->createImage($imageUrl);
          $linkedEventsImageId = $image['id'];
          if ($wordpressImageId) {
            update_post_meta($wordpressImageId, 'linkedevents-imageid', $linkedEventsImageId);
          }
          
          $event->setImages([$this->getImageRef($linkedEventsImageId)]);
        } else {
          $event->setImages([]);
        }
      }
      
      protected function updateEventKeywords($event) {
        $keywordIds = explode(",", $this->getPostString("keywords"));
        $keywordRefs = $this->getKeywordRefs($keywordIds);
        $event->setKeywords($keywordRefs);
      }
      
      /**
       * Updates publication status from the post
       * 
       * @param type $event
       */
      protected function updateEventPublicationStatus($event) {
        $event->setPublicationStatus($this->getPostString("publication-status"));
      }
      
      protected function updateEventLocation($event) {
        $event->setLocation($this->getPlaceRef($this->getPostString("location")));
      }
      
      /*
       * Validates event
       */
      public function validate() {
        $message = $this->validateStartTime();
        if ($message) {
          return $message;
        }
        
        $message = $this->validateLocation();
        if ($message) {
          return $message;
        }
        
        $message = $this->validateKeywords();
        if ($message) {
          return $message;
        }
        
        return null;
      }
      
      /*
       * Validates event start time
       */
      protected function validateStartTime() {
        $startTime = $this->getStartTime();
        $endTime = $this->getEndTime();
        
        $now = new \DateTime();
        
        if (!$startTime) {
          return __('Event start time cannot be empty', 'linkedevents');
        }
        
        if ($startTime->getTimestamp() < $now->getTimestamp()) {
          return __('Event start time cannot be in the past', 'linkedevents');
        }
        
        if ($endTime) {
          if ($startTime->getTimestamp() > $endTime->getTimestamp()) {
            return __('Event start time cannot be after event end time', 'linkedevents');
          }
        }
        
        return null;
      }
      
      /**
       * Validatess event's location
       */
      protected function validateLocation() {
        if (!$this->getPostString("location")) {
          return __('Event location is required', 'linkedevents');
        }
        
        return null;
      }
      
      /**
       * Validates event's keywords
       */
      protected function validateKeywords() {
        if (!$this->getPostString("keywords")) {
          return __('At least one keyword is required', 'linkedevents');
        }
        
        return null;
      }
      
      /**
       * Updates event start time from http request
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      protected function updateEventStartTime($event) {
        $event->setStartTime($this->getStartTime());
        $event->setHasStartTime($this->getHasStartTime());
      }
      
      /**
       * Updates event start time from http request
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      protected function updateEventEndTime($event) {
        $endTime = $this->getEndTime();
        if (!$endTime) {
          $endTime = $this->getStartTime();
        }
        
        $event->setEndTime($endTime);
        $event->setHasEndTime($this->getHasEndTime());
      }
      
      /**
       * Creates new prefilled event object 
       * 
       * @return \Metatavu\LinkedEvents\Model\Event created event object
       */
      protected function getNewEvent() {
        $event = new \Metatavu\LinkedEvents\Model\Event();
        $this->ensureEventRequiredFields($event);
        return $event; 
      }
      
      /**
       * Creates event into Linked Events
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event event object
       * @return \Metatavu\LinkedEvents\Model\Event created event object
       */
      protected function createEvent($event) {
        try {
          return $this->eventsApi->eventCreate($event);
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
      
      /**
       * Finds event by id
       * 
       * @param string $eventId
       * @return \Metatavu\LinkedEvents\Model\Event
       */
      protected function findEvent($eventId) {
        $event = $this->eventsApi->eventRetrieve($eventId);
        $this->ensureEventRequiredFields($event);
        return $event;
      }
      
      protected function updateEvent($event) {
        return $this->eventsApi->eventUpdate($event->getId(), $event);
      }
      
      protected function ensureEventRequiredFields($event) {
        $this->ensureName($event);
        $this->ensureDescriptions($event);
        $this->ensureEventOffers($event);
      }
      
      protected function ensureName($event) {
        $name = $event->getName();
        
        if (!isset($name)) {
          $name = new \Metatavu\LinkedEvents\Model\EventName();
          $event->setName($name);
        }
      }
      
      protected function ensureDescriptions($event) {
        $description = $event->getDescription();
        $shortDescription = $event->getShortDescription();
          
        if (!isset($description)) {
          $description = [];
          $event->setDescription($description);
        }
        
        if (!isset($shortDescription)) {
          $shortDescription = [];
          $event->setShortDescription($shortDescription);
        }
      }
      
      protected function ensureEventOffers($event) {
        $offers = $event->getOffers();
        if (!isset($offers) || count($offers) == 0) {
          $event->setOffers([
            [
              is_free => true,
              price => null,
              info_url => null,
              description => null
            ]
          ]);
        }
      }
      
      /**
       * Returns event's start time from form
       * 
       * @return \DateTime event's start time
       */
      private function getStartTime() {
        return $this->parseDateTime($this->getPostString("start-date"), $this->getPostString("start-time"));
      }
      
      /**
       * Returns whether event has a start time or not
       * 
       * @return bool whether event has a start time or not
       */
      private function getHasStartTime() {
        return !!$this->getPostString("start-time");
      }
      
      /**
       * Returns event's end time from form
       * 
       * @return \DateTime event's end time
       */
      private function getEndTime() {
        return $this->parseDateTime($this->getPostString("end-date"), $this->getPostString("end-time"));
      }
      
      /**
       * Returns whether event has a end time or not
       * 
       * @return bool whether event has a end time or not
       */
      private function getHasEndTime() {
        return !!$this->getPostString("end-time");
      }
    }
  }
    
?>