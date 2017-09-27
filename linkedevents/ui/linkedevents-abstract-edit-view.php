<?php
  
  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\AbstractEditView' ) ) {
    
    class AbstractEditView {
      
      private static $DATE_FORMAT = 'Y-m-d';
      private static $TIME_FORMAT = 'H:i';
      private static $DEFAULT_TIMEZONE = 'Europe/Helsinki';
      
      private $pageTitle;
      private $supportedLanguages = ["fi", "sv", "en"];
      private $filterApi;
      private $imageApi;
        
      public function __construct($targetPage, $pageTitle) {
        $this->pageTitle = $pageTitle;
        
        if ($this->isPageActive($targetPage)) {
          $this->initializeForm();
        }
      }
      
      protected function initializeForm() {
        $this->filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi();
        $this->imageApi = \Metatavu\LinkedEvents\Wordpress\Api::getImageApi();
        $googleMapsKey = \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("google-maps-key");
        wp_enqueue_media();
         
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-autocomplete', null, ['jquery']);
        wp_enqueue_script('jquery-ui-datepicker', null, ['jquery']);
        
        wp_register_style('flatpickr', '//cdn.metatavu.io/libs/flatpickr/2.6.1/flatpickr.min.css');
        wp_register_script('flatpickr', '//cdn.metatavu.io/libs/flatpickr/2.6.1/flatpickr.min.js');
        wp_register_script('flatpickr-fi', '//cdn.metatavu.io/libs/flatpickr/2.6.1/l10n/fi.js');
                
        wp_register_script('googlemaps', '//maps.google.com/maps/api/js?libraries=places&key=' . $googleMapsKey);
        wp_register_script('locationpicker', '//cdn.metatavu.io/libs/jquery-locationpicker/0.1.12/locationpicker.jquery.min.js');
        
        wp_enqueue_script('flatpickr');
        wp_enqueue_script('flatpickr-fi');
        wp_enqueue_style('flatpickr');
        
        wp_enqueue_script('linkedevents-editors', plugin_dir_url(__FILE__) . 'linkedevents-editors.js', ['jquery-ui-autocomplete', 'flatpickr', 'flatpickr-fi']);
        wp_enqueue_style('linkedevents-editors', plugin_dir_url(__FILE__) . 'linkedevents-editors.css');
        
        wp_enqueue_script('googlemaps', ['jquery']);
        wp_enqueue_script('locationpicker', ['googlemaps']);
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
        echo '<input type="hidden" value="'. get_locale() . '" name="locale"/>';
        echo '<div id="poststuff">';
        wp_nonce_field();
      }
      
      protected function isPageActive($page) {
        return $_GET['page'] == $page;
      }
      
      protected function renderFormPostfix() {
        submit_button();
        
        echo '</form>';
        echo '</div>';
        
        echo '</div>';
      }
      
      protected function getLocalizedPostString($name, $language) {
        return $this->getPostString($name . '-' . $language);
      }
      
      protected function getLocalizedRawPostString($name, $language) {
        return $this->getRawPostString($name . '-' . $language);
      }
      
      protected function getPostString($name) {
        return sanitize_text_field($_POST[$name]);
      }
      
      protected function getRawPostString($name) {
        return $_POST[$name];
      }
      
      protected function getPostFloat($name) {
        return floatval($this->getPostString($name));
      }

      protected function renderHidden($name, $value) {
        echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
      }
      
      protected function renderMemo($label, $name, $value, $language) {
        echo '<h3>' . $label . '</h3>';
        wp_editor($value, $name . '_' . $language, [
          'media_buttons' => false,
          'tinymce' => false
        ]);
      }
      
      /**
       * Renders memo fields for localized property
       * 
       * @param string $label field label
       * @param string $name field name
       * @param \ArrayAccess $values localized values as associative array with locale as key
       */
      protected function renderLocalizedMemo($label, $name, $values) {
        echo '<h3>' . $label . '</h3>';
        foreach ($this->getSupportedLanguages() as $language) {
          $value = $values ? $values[$language] : null;
          $fieldName = "$name-$language";
          echo '<label>' . $this->getLanguageName($language) . '</label>';
          wp_editor($value, $fieldName, [
            'media_buttons' => false,
            'tinymce' => false
          ]);
        }  
      }
      
      protected function renderTextInput($label, $name, $value) {
        echo '<h3>' . $label . '</h3>';
        echo '<input class="linkedevents-input" type="text" name="' . $name . '" value="' . $value . '" />';
      }
      
      /**
       * Renders input fields for localized property
       * 
       * @param string $label field label
       * @param string $name field name
       * @param \ArrayAccess $values localized values as associative array with locale as key
       */
      protected function renderLocalizedTextInput($label, $name, $values) {
        echo '<h3>' . $label . '</h3>';
        foreach ($this->getSupportedLanguages() as $language) {
          $value = $values ? $values[$language] : null;
          $fieldName = "$name-$language";
          echo '<label>' . $this->getLanguageName($language) . '</label>';
          echo '<input class="linkedevents-input linkedevents-localized-input" type="text" name="' . $fieldName . '" value="' . $value . '" />';
        }
      }
      
      /**
       * Renders geo position input
       * 
       * @param type $label label
       * @param type $name name
       * @param \ArrayAccess $streetAddress
       * @param string $postalCode
       * @param \ArrayAccess $addressLocality
       * @param string $addressRegion
       * @param string $poBox
       * @param float[] $coordinates
       */
      protected function renderGeoPositionInput($label, $name, $streetAddress, $postalCode, $addressLocality, $addressRegion, $poBox, $coordinates) {
        echo '<h3>' . $label . '</h3>';
        echo '<div data-input="' . $name . '" class="linkedevents-geoinput">';
        echo '<a href="#" class="linkedevents-search dashicons-before dashicons-search">&nbsp;</a>';
        
        $this->renderGeoPositionInputField('', $name, "search", ''  );
        
        $this->renderLocalizedGeoPositionInputField(__('Street address (%s)', 'linkedevents'), $name, "street-address", $streetAddress);
        $this->renderGeoPositionInputField(__('Postal code', 'linkedevents'), $name, "postal-code", $postalCode);
        $this->renderLocalizedGeoPositionInputField(__('Address locality (%s)', 'linkedevents'), $name, "address-locality", $addressLocality);
        
        $this->renderGeoPositionInputField(__('Address region', 'linkedevents'), $name, "address-region", $addressRegion);
        $this->renderGeoPositionInputField(__('Po box', 'linkedevents'), $name, "po-box", $poBox);
        
        echo '<div><label>Coordinates</label></div>';
        $this->renderGeoPositionInputField('', $name, "latitude", $coordinates ? $coordinates[0] : null);
        $this->renderGeoPositionInputField('', $name, "longitude", $coordinates ? $coordinates[1] : null);
        
        echo '<div class="linkedevents-geoposition-map"></div>';
        echo '</div>';
      }
      
      private function renderGeoPositionInputField($label, $name, $type, $value) {
        $fieldName = "$name-$type";
        $fieldValue = $value != null ? strval($value) : '';
        
        echo "<label>$label</label>";
        echo "<input type=\"text\" class=\"linkedevents-geoinput-$type\" name=\"$fieldName\" value=\"$fieldValue\"/>";
      }
      
      /**
       * Renders geo position input fields for localized property
       * 
       * @param string $label field label
       * @param string $name field name
       * @param \ArrayAccess $values localized values as associative array with locale as key
       */
      private function renderLocalizedGeoPositionInputField($label, $name, $type, $values) {
        foreach ($this->getSupportedLanguages() as $language) {
          $fieldName = "$name-$type-$language";
          $value = $values ? $values[$language] : null;
          echo '<label>' . sprintf($label, $this->getLanguageName($language)) . '</label>';
          echo '<input type="text" class="linkedevents-localized-geoinput linkedevents-geoinput-' . $type . '" name="' . $fieldName . '" value="' . $value . '" />';
        }
      }
      
      /**
       * Renders date picker component
       * 
       * @param string $name name
       * @param string $label label
       * @param string $value value
       */
      protected function renderDatePicker($name, $label, $required, $value = null) {
        $valueAttr = $value ? ' value="' . $this->getDateTimeDate($value) . '"' : '';
        $requiredAttr = $required ? ' required="required"' : '';
        $nameAttr = ' name="' . $name . '"';
        echo '<h3>' . $label . '</h3>';
        echo '<input class="linkedevents-datepicker" type="text"' . $nameAttr . $valueAttr . $requiredAttr . '/>';
      }
      
      /**
       * Renders time picker component
       * 
       * @param string $name name
       * @param string $label label
       * @param string $value value
       */
      protected function renderTimePicker($name, $label, $required, $value = null) {
        $valueAttr = $value ? 'value="' . $this->getDateTimeTime($value) . '"' : '';
        $requiredAttr = $required ? ' required="required"' : '';
        $nameAttr = ' name="' . $name . '"';
        echo '<h3>' . $label . '</h3>';
        echo '<input class="linkedevents-timepicker" type="text"' . $nameAttr . $valueAttr . $requiredAttr . '/>';
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
      
      /**
       * Returns IdRef array for keyword ids
       * 
       * @param type $keywordIds keyword ids
       * @return \Metatavu\LinkedEvents\Model\IdRef[] keyword IdRefs
       */
      protected function getKeywordRefs($keywordIds) {
        $result = [];
        
        foreach ($keywordIds as $keywordId) {
          $result[] = $this->getKeywordRef($keywordId);  
        }
        
        return $result;
      }
      
      /**
       * Returns reference into the keyword
       * 
       * @param string $keywordId keyword id
       * @return \Metatavu\LinkedEvents\Model\IdRef reference into the keyword
       */
      protected function getKeywordRef($keywordId) {
        return $this->getIdRef($this->getApiUrl() . "/keyword/$keywordId/");
      }
      
      /**
       * Returns reference into the location
       * 
       * @param string $locationId location id
       * @return \Metatavu\LinkedEvents\Model\IdRef reference into the location
       */
      protected function getPlaceRef($locationId) {
        return $this->getIdRef($this->getApiUrl() . "/place/$locationId/");
      }
      
      /**
       * Returns reference into the image
       * 
       * @param string $id image id
       * @return \Metatavu\LinkedEvents\Model\IdRef reference into the image
       */
      protected function getImageRef($id) {
        return $this->getIdRef($this->getApiUrl() . "/image/$id/");
      }
      
      /**
       * Returns IdRef object for id
       * 
       * @param string $id id
       * @return \Metatavu\LinkedEvents\Model\IdRef IdRef
       */
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
       * @return string id
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
       * Returns time zone
       * 
       * @return \DateTimeZone time zone
       */
      protected function getTimezone() {
        $result = \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("timezone");
        return new \DateTimeZone($result ? $result : self::$DEFAULT_TIMEZONE);
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
      
      /**
       * Redirects user into specified url
       * 
       * @param string $redirectUrl redirect url
       */
      protected function redirect($redirectUrl) {
        echo '<script type="text/javascript">window.location="' . $redirectUrl . '";</script>"';
      }
      
      protected function getLanguageName($language) {
        switch ($language) {
          case 'fi':
            return __('Finnish', 'linkedevents');
          case 'sv':
            return __('Swedish', 'linkedevents');
          case 'en':
            return __('English', 'linkedevents');
        }
      }
      
      /**
       * Returns ISO formatted date
       * 
       * @param \DateTime $dateTime
       * @return string date is ISO format
       */
      protected function getDateTimeDate($dateTime) {
        if (!$dateTime) {
          return;
        }
        
        return $dateTime->format(self::$DATE_FORMAT);
      }
      
      /**
       * Returns iso formatted time in configured time zone
       * 
       * @param \DateTime $dateTime
       * @return string time in configured time zone
       */
      protected function getDateTimeTime($dateTime) {
        if (!$dateTime) {
          return null;
        }
        
        if (!$dateTime->getHasTime()) {
          return null;
        }
        
        $dateTime->setTimezone($this->getTimezone());
        return $dateTime->format(self::$TIME_FORMAT);
      }
      
      /**
       * Parses date time from ISO formatted date and time strings.
       * 
       * Used TimeZone is the one configured in settings
       * 
       * @return \DateTime parsed datetime
       */
      protected function parseDateTime($dateString, $timeString) {
        if (!$dateString) {
          return null;
        }
        
        $timeZone = $this->getTimezone();
        $format = $timeString ? self::$DATE_FORMAT . '\T' . self::$TIME_FORMAT : self::$DATE_FORMAT;
        $value = $timeString ? $dateString . 'T' . $timeString : $dateString;
        $result = \DateTime::createFromFormat($format, $value, $timeZone);
        
        if (!$timeString) {
          $result->setTime(0, 0);
        }
        
        return $result;
      }
    }
  }
    
?>