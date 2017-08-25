<?php
  
  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\AbstractEditView' ) ) {
    
    class AbstractEditView {
      
      private $pageTitle;
      private $supportedLanguages = ["fi"];
      private $filterApi;
      private $imageApi;
        
      public function __construct($pageTitle) {
        $this->filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi();
        $this->imageApi = \Metatavu\LinkedEvents\Wordpress\Api::getImageApi();
                
        wp_enqueue_media();
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-autocomplete', null, ['jquery']);
        wp_enqueue_script('jquery-ui-datepicker', null, ['jquery']);
        
        wp_register_style('flatpickr', '//cdn.metatavu.io/libs/flatpickr/2.6.1/flatpickr.min.css');
        wp_register_script('flatpickr', '//cdn.metatavu.io/libs/flatpickr/2.6.1/flatpickr.min.js');
        
        wp_enqueue_script('flatpickr');
        wp_enqueue_style('flatpickr');
        
        wp_enqueue_script('linkedevents-editors', plugin_dir_url(__FILE__) . 'linkedevents-editors.js', ['jquery-ui-autocomplete', 'flatpickr']);
        wp_enqueue_style('linkedevents-editors', plugin_dir_url(__FILE__) . 'linkedevents-editors.css');
        
        $this->pageTitle = $pageTitle;
      }
        
      public function renderForm($formAction) {
        $this->renderFormStart($formAction);
        $this->renderFormFields();
        $this->renderFormPostfix();
      }
      
      protected function renderFormStart($action) {
        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">' . $this->pageTitle . '</h1>';
        echo '<hr class="wp-header-end"/>';
        
        echo '<form method="post" action="' . $action . '">';
        echo '<div id="poststuff">';
        wp_nonce_field();
      }
      
      protected function renderFormPostfix() {
        submit_button();
        
        echo '</form>';
        echo '</div>';
        
        echo '</div>';
      }
      
      protected function getLocalizedPostString($name, $language) {
        return $this->getPostString($name . '_' . $language);
      }
      
      protected function getPostString($name) {
        return sanitize_text_field($_POST[$name]);
      }

      protected function renderHidden($name, $value) {
        echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
      }
      
      protected function renderMemo($label, $name, $event, $language) {
        $value = isset($event) ? $event[$name][$language] : '';
        
        echo '<h3>' . $label . '</h3>';
        wp_editor($value, $name . '_' . $language, [
          'media_buttons' => false,
          'tinymce' => false
        ]);
      }
      
      protected function renderDateTimePicker($name, $label, $value) {
        echo '<h3>' . $label . '</h3>';
        echo '<input class="linkedevents-datetimepicker" name="' . $name . '" value="' . $value . '" type="text">';
      }
      
      protected function renderAutocomplete($name, $label, $searchTarget, $value) {
        echo '<h3>' . $label . '</h3>';
        echo '<input data-search-target="' . $searchTarget . '" data-name="' . $name. '" size="30" value="' . $value['label'] . '" class="linkedevents-autocomplete" type="text">';
        echo '<input name="' . $name . '" value="' . $value['value'] . '" type="hidden">';
      }
      
      protected function renderMultivalueAutocomplete($name, $label, $searchTarget, $values) {
        $valuesAttr = htmlspecialchars(json_encode($values));
        echo '<h3>' . $label . '</h3>';
        echo '<input data-search-target="' . $searchTarget . '" data-name="' . $name . '" size="30" value="" class="linkedevents-multivalue-autocomplete" type="text" data-values="' . $valuesAttr . '"/>';
        echo '<input name="' . $name . '" type="hidden"/>';
      }
      
      protected function renderImageSelector($name, $label, $value) {
        $selectorTitle = __('Select an image', 'linkedevents');
        $selectorButton = __('Select', 'linkedevents');
        $brokenImageText = __('Image could not be loaded', 'linkedevents');
        
        echo '<h3>' . $label . '</h3>';
        echo '<div class="linkedevents-image-selector" data-title="' . $selectorTitle . '" data-button="' . $selectorButton . '">';
        echo '<input type="url" value="' . $value . '" name="' . $name . '"/>';
        echo '<div class="broken-image-text">' . $brokenImageText . '</div>';
        echo '<img src="' . ($value ? $value : 'about:blank') . '"/>';
        echo '<a>' . __('Select an image', 'linkedevents') . '</a>';
        echo '</div>';
      }
      
      protected function getSupportedLanguages() {
        return $this->supportedLanguages;
      }
      
      protected function getKeywordRefs($keywordIds) {
        $result = [];
        
        foreach ($keywordIds as $keywordId) {
          $result[] = $this->getKeywordRef($keywordId);  
        }
        
        return $result;
      }
      
      protected function getKeywordRef($keywordId) {
        return $this->getIdRef($this->getApiUrl() . "/keyword/$keywordId/");
      }
      
      protected function getPlaceRef($locationId) {
        return $this->getIdRef($this->getApiUrl() . "/place/$locationId/");
      }
      
      protected function getImageRef($id) {
        return $this->getIdRef($this->getApiUrl() . "/image/$id/");
      }
      
      protected function getIdRef($id) {
        $idRef = new \Metatavu\LinkedEvents\Model\IdRef();
        $idRef->setId($id);
        return $idRef;
      }
      
      protected function extractIdRefIds($idRefs) {
        $result = [];
        
        foreach ($idRefs as $idRef) {
          $result[] = $this->extractIdRefId($idRef);  
        }
        
        return $result;
      }
      
      /**
       * Extracts id from IdRef
       * 
       * @param \Metatavu\LinkedEvents\Model\IdRef $idRef
       */
      protected function extractIdRefId($idRef) {
        if (isset($idRef)) {
          $id = rtrim($idRef->getId(), '/');
          $parts = explode("/", $id);
          return $parts[count($parts) - 1];
        }
        
        return null;
      }
      
      /**
       * Returns filename from url
       * 
       * @param string $url url
       * @return string filename
       */
      protected function getUrlFile($url) {
        if (!$url) {
          return null;
        }
        
        return basename($url);
      }
      
      protected function getApiUrl() {
        return \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("api-url");
      }
      
      /**
       * Finds a keyword by id
       * 
       * @param string $id
       * @return \Metatavu\LinkedEvents\Model\Keyword
       */
      protected function findKeyword($id) {
        return $this->filterApi->keywordRetrieve($id);
      }
      
      /**
       * Finds a place by id
       * 
       * @param string $id
       * @return \Metatavu\LinkedEvents\Model\Place
       */
      protected function findPlace($id) {
        return $this->filterApi->placeRetrieve($id);
      }
      
      protected function createImage($url) {
        error_log("CREATE IMAGE:" . $url);
        
        return $this->imageApi->imageCreate(null, [
          url => $url
        ]);
      }
      
      /**
       * Finds image by id
       * 
       * @param string $id
       * @return \Metatavu\LinkedEvents\Model\Image
       */
      protected function findImage($id) {
        return $this->imageApi->imageRetrieve($id);
      }
    }
  }
    
?>