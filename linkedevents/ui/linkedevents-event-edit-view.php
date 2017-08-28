<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\EventEditView' ) ) {
    
    class EventEditView extends AbstractEditView {
      
      private $eventsApi;
      
      public function __construct($pageTitle) {
        parent::__construct($pageTitle);
        $this->eventsApi = \Metatavu\LinkedEvents\Wordpress\Api::getEventApi();
      }
        
      /**
       * Renders event name field
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event event
       * @param string $language language
       */
      protected function renderEventName($event, $language) {
        $value = isset($event) ? $event['name'][$language] : '';
        echo '<div id="titlediv">';
	      echo '<div id="titlewrap">';
	      echo '<input name="name_' . $language . '" size="30" value="' . $value . '" id="title" spellcheck="true" autocomplete="off" type="text">';
        echo '</div>';
        echo '</div>';
      }
      
      /**
       * Renders autocomplete component for editing event location
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      protected function renderEventLocation($event) {
        $value = null;
        $label = null;
        
        if ($event) {
          $location = $event->getLocation();
          $value = $this->extractIdRefId($location);
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
        $keywordIds = isset($event) ? $this->extractIdRefIds($event->getKeywords()) : [];
        
        foreach ($keywordIds as $keywordId) {
          $keyword = $this->findKeyword($keywordId);
          $values[] = [
            label => $keyword->getName()->getFi(),
            value => $keyword->getId()
          ];
        }
        
        $this->renderMultivalueAutocomplete('keywords', __('Keywords', 'linkedevents'), 'linkedevents_keywords', $values);
      }
      
      protected function updateEventName($event, $language) {
        $name = $event->getName();
        $name->setFi($this->getLocalizedPostString('name', $language));
        $event->setName($name);
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
      
      protected function updateEventDescription($event, $language) {
        $description = $event->getDescription();
        $description[$language] = $this->getLocalizedPostString('description', $language);
        $event->setDescription($description);
      }

      protected function updateEventShortDescription($event, $language) {
        $shortDescription = $event->getShortDescription();
        $shortDescription[$language] = $this->getLocalizedPostString('shortDescription', $language);
        $event->setShortDescription($shortDescription);
      }
      
      protected function updateEventKeywords($event) {
        $keywordIds = explode(",", $this->getPostString("keywords"));
        $keywordRefs = $this->getKeywordRefs($keywordIds);
        $event->setKeywords($keywordRefs);
      }
      
      protected function updateEventPublicationStatus($event) {
        // draft
        $event->setPublicationStatus("public");
      }
      
      protected function updateEventLocation($event) {
        $event->setLocation($this->getPlaceRef($this->getPostString("location")));
      }
      
      protected function validateStartTime() {
        $now = new \DateTime();
        
        if ($this->getStartTime()->getTimestamp() < $now->getTimestamp()) {
          return __('Event start time cannot be in the past', 'linkedevents');
        }
        
        if ($this->getStartTime()->getTimestamp() > $this->getEndTime()->getTimestamp()) {
          return __('Event start time cannot be after event end time', 'linkedevents');
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
      }
      
      /**
       * Updates event start time from http request
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      protected function updateEventEndTime($event) {
        $event->setEndTime($this->getEndTime());
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
        
        foreach ($this->getSupportedLanguages() as $supportedLanguage) {
          if (!isset($name[$supportedLanguage])) {
            $name[$supportedLanguage] = 'Nimi';
          }
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
        
        foreach ($this->getSupportedLanguages() as $supportedLanguage) {
          if (!isset($description[$supportedLanguage])) {
            $description[$supportedLanguage] = 'Kuvaus';
            $event->setDescription($shortDescription);
          }
          
          if (!isset($shortDescription[$supportedLanguage])) {
            $shortDescription[$supportedLanguage] = 'Lyhyt kuvaus';
            $event->setShortDescription($shortDescription);
          }
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
      
      private function getStartTime() {
        $dateTime = new \DateTime();
        $dateTime->setTimestamp(intval($this->getPostString("start")));
        return $dateTime;
      }
      
      private function getEndTime() {
        $dateTime = new \DateTime();
        $dateTime->setTimestamp(intval($this->getPostString("end")));
        return $dateTime;
      }
    }
  }
    
?>