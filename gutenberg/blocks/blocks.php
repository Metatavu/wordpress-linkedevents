<?php
namespace Metatavu\LinkedEvents\Wordpress\Gutenberg\Blocks;

require_once(__DIR__ . '/../../templates/template-loader.php');
require_once(__DIR__ . '/../../linkedevents/id-ref-controller.php');

defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );

use \Metatavu\LinkedEvents\Wordpress\IdRefController;

if (!class_exists( 'Metatavu\LinkedEvents\Wordpress\Gutenberg\Blocks\Blocks' ) ) {

  /**
   * Class for handling Gutenberg blocks
   */
  class Blocks {

    private static $DATE_FORMAT = 'Y-m-d';
    private static $LANGUAGES = ["fi", "sv", "en"];

    /**
     * Constructor
     */
    public function __construct() {
      add_action('init', [$this, "onInit"]);
    }

    /**
     * Action executed on init
     */
    public function onInit() {
      wp_register_script('linkedevents-blocks', plugins_url( 'js/linkedevents-blocks.js', __FILE__ ), ['wp-blocks', 'wp-element', 'wp-i18n']);      
      wp_set_script_translations("linkedevents-blocks", "linkedevents", dirname(__FILE__) . '/lang/');
      add_filter("block_categories", [ $this, "blockCategoriesFilter"], 10, 2);
      wp_enqueue_style('linkedevents', plugin_dir_url(dirname(__FILE__)) . 'css/linkedevents.css');

      wp_localize_script('linkedevents-blocks', 'linkedEventsOptions', [ 
        "apiUrl" => \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("api-url"),
        "language" => $this->getCurrentLanguage()
      ]);

      register_block_type('linkedevents/event-search-block', [
        'editor_script' => 'linkedevents-blocks',
        'render_callback' => [ $this, "renderEventSearchBlock" ],
        'attributes' => [ 
          "label" => [
            'type' => 'string'
          ],
          "buttonText" => [
            'type' => 'string'
          ],
          "textPlaceholder" => [
            'type' => 'string'
          ],
          "dateFilterVisible" => [
            'type' => 'boolean'
          ],
          "dateFilterLabel" => [
            'type' => 'string'
          ],
          "sortVisible" => [
            'type' => 'boolean'
          ],
          "sortLabel" => [
            'type' => 'string'
          ],
          "keywordsVisible" => [
            'type' => 'boolean'
          ],
          "keywordsLabel" => [
            'type' => 'string'
          ],
          "locationVisible" => [
            'type' => 'boolean'
          ],
          "locationLabel" => [
            'type' => 'string'
          ],
          "locations" => [
            'type' => 'array'
          ],
          "audienceVisible" => [
            'type' => 'boolean'
          ],
          "audienceLabel" => [
            'type' => 'string'
          ],
          "categoriesVisible" => [
            'type' => 'boolean'
          ],
          "categoriesLabel" => [
            'type' => 'string'
          ]
        ]
      ]);

      register_block_type('linkedevents/list-block', [
        'attributes' => [ 
          "filter-start" => [
            'type' => 'string'
          ],
          "filter-end" => [
            'type' => 'string'
          ],
          "filter-bbox" => [
            'type' => 'string'
          ],
          "filter-location" => [
            'type' => 'string'
          ],
          "filter-division" => [
            'type' => 'string'
          ],
          "filter-keywords" => [
            'type' => 'string'
          ],
          "filter-recurring" => [
            'type' => 'string'
          ],
          "filter-min-duration" => [
            'type' => 'string'
          ],
          "filter-max-duration" => [
            'type' => 'string'
          ],
          "filter-locality-fi" => [
            'type' => 'string'
          ],
          "filter-language" => [
            'type' => 'string'
          ],
          "field-config" => [
            'type' => 'string'
          ],
          "sort" => [
            'type' => 'string'
          ],
          "page-size" => [
            'type' => 'string'
          ]
        ],
        'editor_script' => 'linkedevents-blocks',
        'render_callback' => [ $this, "renderListBlock" ]
      ]);
    }

    /**
     * Renders an event search block
     * 
     * @return string the form HTML
     */
    public function renderEventSearchBlock($attributes) {
      global $wp;
      static $instanceId = 0;

      $filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi();

      $label = $attributes["label"];
      $textPlaceholder = $attributes["textPlaceholder"];
      $buttonText = $attributes["buttonText"];
      $dateFilterVisible = $attributes["dateFilterVisible"];
      $dateFilterLabel = $attributes["dateFilterLabel"];
      $sortVisible = $attributes["sortVisible"];
      $sortLabel = $attributes["sortLabel"];
      $keywordsVisible = $attributes["keywordsVisible"];
      $keywordsLabel = $attributes["keywordsLabel"];
      $locationVisible = $attributes["locationVisible"];
      $locationLabel = $attributes["locationLabel"];
      $locations = explode(",", $attributes["locations"]);
      $audienceVisible = $attributes["audienceVisible"];
      $audienceLabel = $attributes["audienceLabel"];
      $categoriesVisible = $attributes["categoriesVisible"];
      $categoriesLabel = $attributes["categoriesLabel"];
      $actionUrl = $_SERVER['REQUEST_URI'];

      $text = $this->getSearchParam("text");
      $start = $this->getSearchParam("start");
      $end = $this->getSearchParam("end");
      $sort = $this->getSearchParam("sort");
      $keywordIds = $this->getSearchParams("keywords", []);
      $locationIds = $this->getSearchParams("address_locality_fi", []);

      $labelHtml = sprintf('<label class="linkedevents-events-search-label">%s</label>', $label);
      $inputHtml = sprintf('<input type="search" id="%s-text" class="linkedevents-events-text-input" name="les-text" value="%s" placeholder="%s" />', 'linkedevents-events-search-input-' . esc_attr(++$instanceId), esc_attr($text), esc_attr($textPlaceholder));
      $filterHtmls = "";

      if ($dateFilterVisible) {
        $startDateId = sprintf('linkedevents-events-search-date-start-%d', $instanceId);
        $startPlaceHolder = __("Start Time", "linkedevents");
        $endDateId = sprintf('linkedevents-events-search-date-end-%d', $instanceId);
        $endPlaceHolder = __("End Time", "linkedevents");
        $dateFilterLabelHtml = sprintf("<label>%s</label>", $dateFilterLabel);
        $startInputHtml = $this->renderDateInput($startDateId, "les-start", $start, "linkedevents-events-date-input");
        $endInputHtml = $this->renderDateInput($endDateId, "les-end", $end, "linkedevents-events-date-input");
        $dateInputsHtml = sprintf('%s<span class="linkedevents-events-date-sep">-</span>%s', $startInputHtml, $endInputHtml);
        $filterHtmls .= sprintf("<div>%s</div><div>%s</div>", $dateFilterLabelHtml, $dateInputsHtml);
      }

      if ($sortVisible) {
        $sortId = sprintf('linkedevents-events-search-sort-%d', $instanceId);
        $sortLabelHtml = sprintf("<label>%s</label>", $sortLabel);
        
        $sortSelectHtml = $this->renderSelectInput($sortId, "les-sort", $sort, "linkedevents-events-sort", [
          [
            "value" => null,
            "label" => __("Last modification time", "linkedevents")
          ], [
            "value" => "start_time",
            "label" => __("Start time", "linkedevents")
          ], [
            "value" => "end_time",
            "label" => __("End time", "linkedevents")
          ], [
            "value" => "days_left",
            "label" => __("Days left", "linkedevents")
          ]
        ]);

        $filterHtmls .= sprintf("<div>%s</div><div>%s</div>", $sortLabelHtml, $sortSelectHtml);
      }

      if ($keywordsVisible) {
        $keywordsId = sprintf('linkedevents-events-search-sort-%d', $instanceId);
        $keywordsLabelHtml = sprintf("<label>%s</label>", $keywordsLabel);
        
        $keywordsSelectHtml = $this->renderChecklistInput($keywordsId, "les-keywords", $keywordIds, "linkedevents-events-keyword-container", "linkedevents-events-keyword", array_map(function ($keyword) {
          return [
            "value" => $keyword->getId(),
            "label" => $this->getLocalizedValue($keyword->getName())
          ];
        }, $filterApi->keywordList()->getData()));

        $filterHtmls .= sprintf("<div>%s</div><div class=\"%s\">%s</div>", $keywordsLabelHtml, "linkedevents-events-keyword-section", $keywordsSelectHtml);
      }

      if ($locationVisible) {
        $locationId = sprintf('linkedevents-events-search-sort-%d', $instanceId);
        $locationsLabelHtml = sprintf("<label>%s</label>", $locationLabel);
        
        $locationsSelectHtml = $this->renderChecklistInput($locationId, "les-address_locality_fi", $locationIds, "linkedevents-events-keyword-container", "linkedevents-events-keyword", array_map(function ($location) {
          return [
            "value" => $location,
            "label" => $location
          ];
        }, $locations));

        $filterHtmls .= sprintf("<div>%s</div><div class=\"%s\">%s</div>", $locationsLabelHtml, "linkedevents-events-keyword-section", $locationsSelectHtml);
      }

      if ($audienceVisible) {
        $audienceId = sprintf('linkedevents-events-search-sort-%d', $instanceId);
        $audiencesLabelHtml = sprintf("<label>%s</label>", $audienceLabel);
        $keywordSets = $filterApi->keywordSetList(null, null, 'keywords')->getData();
        
        foreach($keywordSets as $keywordSet) {
          if ($keywordSet->getUsage() == "audience") {
              $audiences = $keywordSet->getKeywords();
              break;
          }
        }

        $audiencesSelectHtml = $this->renderChecklistInput($audienceId, "les-keywords", $keywordIds, "linkedevents-events-keyword-container", "linkedevents-events-keyword", array_map(function ($audience) {
          return [
            "value" => $audience->getId(),
            "label" => $this->removeTextInParens($this->getLocalizedValue($audience->getName()))
          ];
        }, $audiences));
        $filterHtmls .= sprintf("<div>%s</div><div class=\"%s\">%s</div>", $audiencesLabelHtml, "linkedevents-events-keyword-section", $audiencesSelectHtml);
      }

      if ($categoriesVisible) {
        $categoryId = sprintf('linkedevents-events-search-sort-%d', $instanceId);
        $categoriesLabelHtml = sprintf("<label>%s</label>", $categoriesLabel);
        $keywordSets = $filterApi->keywordSetList(null, null, 'keywords')->getData();
        
        foreach($keywordSets as $keywordSet) {
          if ($keywordSet->getUsage() == "any") {
              $categories = $keywordSet->getKeywords();
              break;
          }
        }

        $categoriesSelectHtml = $this->renderChecklistInput($categoryId, "les-keywords", $keywordIds, "linkedevents-events-keyword-container", "linkedevents-events-keyword", array_map(function ($category) {
          return [
            "value" => $category->getId(),
            "label" => $this->removeTextInParens($this->getLocalizedValue($category->getName()))
          ];
        }, $categories));
        $filterHtmls .= sprintf("<div>%s</div><div class=\"%s\">%s</div>", $categoriesLabelHtml, "linkedevents-events-keyword-section", $categoriesSelectHtml);
      }

      $buttonHtml = sprintf('<div><button type="submit" class="linkedevents-events-search-button">%s</button></div>', $buttonText);

      $html = sprintf('%s%s%s%s', $labelHtml, $inputHtml, $filterHtmls, $buttonHtml);

      return sprintf('<form class="linkedevents-events-search" role="search" method="get" action="%s">%s</form>', esc_url($actionUrl), $html);
    }
    
    /**
     * Renders a list block
     *
     * Return a HTML representation of events
     *
     * @property array $attributes {
     *   block attributes
     * 
     *   @type string $text Search (case insensitive) through all multilingual text fields (name, description, short_description, info_url) of an event (every language). Multilingual fields contain the text that users are expected to care about, thus multilinguality is useful discriminator. (optional)
     *   @type string $lastModifiedSince Search for events that have been modified since or at this time. (optional)
     *   @type string $start Search for events beginning or ending after this time. Dates can be specified using ISO 8601 (\&quot;2016-01-12\&quot;) and additionally \&quot;today\&quot;. (optional)
     *   @type string $end Search for events beginning or ending before this time. Dates can be specified using ISO 8601 (\&quot;2016-01-12\&quot;) and additionally \&quot;today\&quot;. (optional)
     *   @type string[] $bbox Search for events that are within this bounding box. Decimal coordinates are given in order west, south, east, north. Period is used as decimal separator. (optional)
     *   @type string $dataSource Search for events that come from the specified source system (optional)
     *   @type int[] $location Search for events in given locations as specified by id. Multiple ids are separated by comma (optional)
     *   @type bool $showAll Show all events (optional) (optional)
     *   @type string $division You may filter places by specific OCD division id, or by division name. The latter query checks all divisions with the name, regardless of division type. (optional)
     *   @type string $keyword Search for events with given keywords as specified by id. Multiple ids are separated by comma (optional)
     *   @type string $recurring Search for events based on whether they are part of recurring event set. &#39;super&#39; specifies recurring, while &#39;sub&#39; is non-recurring. (optional)
     *   @type int $minDuration Search for events that are longer than given time in seconds (optional)
     *   @type int $maxDuration Search for events that are shorter than given time in seconds (optional)
     *   @type string $publisher Search for events published by the given organization (optional)
     *   @type string $sort Sort the returned events in the given order. Possible sorting criteria are &#39;start_time&#39;, &#39;end_time&#39;, &#39;days_left&#39; and &#39;last_modified_time&#39;. The default ordering is &#39;-last_modified_time&#39;. (optional)
     *   @type int $page request particular page in paginated results (optional)
     *   @type int $pageSize request that server delivers page_size results in response (optional)
     *   @type string $addressLocalityFi Search for events in given address localities (fi). Multiple localities can be entered by separating them by a comma (optional)
     *   @type string $addressLocalitySv Search for events in given address localities (sv). Multiple localities can be entered by separating them by a comma (optional)
     *   @type string $addressLocalityEn Search for events in given address localities (en). Multiple localities can be entered by separating them by a comma (optional)
     *   @type string $language Search for events in given language (optional)
     *   @type string $publicationStatus Filter events by publication status (either draft or public) (optional)
     * }
     */
    public function renderListBlock($attributes) {
      $result = '';
      $eventsApi = \Metatavu\LinkedEvents\Wordpress\Api::getEventApi();
      $filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi();
      
      $include = null;
      $text = $this->getSearchParam("text");
      $lastModifiedSince = null;
      $start = $this->parseDateFilter($this->getSearchParam("start", $attributes["filter-start"]));
      $end = $this->parseDateFilter($this->getSearchParam("end", $attributes["filter-end"]));
      $bbox = $this->parseBBoxFilter($attributes["filter-bbox"]);
      $dataSource = \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("datasource");
      $location = $this->parseIds($attributes["filter-location"]);
      $showAll = false;
      $division = $attributes["filter-division"];
      $keywords = $this->getSearchParamsCDT("keywords", $attributes["filter-keywords"]);
      $recurring = $attributes["filter-recurring"];
      $minDuration = $this->parseInt($attributes["filter-min-duration"]);
      $maxDuration = $this->parseInt($attributes["filter-max-duration"]);
      $publisher = null;
      $sort = $this->getSearchParam("sort", $attributes["sort"]);
      $page = $this->getSearchParam("page"); 
      $pageSize = $this->parseInt($attributes["page-size"]);
      $addressLocalityFi = $this->getSearchParamsCDT("address_locality_fi", $attributes["filter-locality-fi"]);
      $addressLocalitySv = null;
      $addressLocalityEn = null;
      $language = $attributes["filter-language"];
      $fieldConfig = $attributes["field-config"];
      $publicationStatus = null;

      try {
        $events = $eventsApi->eventList(
          $include, 
          $text, 
          $lastModifiedSince, 
          $start, 
          $end, 
          $bbox, 
          $dataSource, 
          $location,
          $showAll,
          $division, 
          $keywords,
          $recurring, 
          $minDuration, 
          $maxDuration, 
          $publisher, 
          $sort,
          $page, 
          $pageSize, 
          $addressLocalityFi, 
          $addressLocalitySv, 
          $addressLocalityEn,
          $language, 
          $publicationStatus);

        $locationIds = array_unique(array_filter(array_map(function ($event) {
          if (!$event["location"]) {
            return null;
          }

          return IdRefController::extractIdRefId($event["location"]);
        }, $events->getData())));

        $locations = [];

        foreach ($locationIds as $locationId) {
          $refId = IdRefController::getPlaceRef($locationId);
          $place = $filterApi->placeRetrieve($locationId);
          $locations[$refId->getId()] = $place;
        }

        if ($events->valid()) {
          $templateData = [
            "events" => $events->getData(),
            "locations" => $locations,
            "language" => $language,
            "fieldConfig" => $fieldConfig
          ];

          $templateLoader = new \Metatavu\LinkedEvents\TemplateLoader();

          ob_start();
          $templateLoader->set_template_data($templateData)->get_template_part('events');
          $result = ob_get_contents();
          ob_end_clean();

          if (empty($result) && $_GET["preview"]) {
            return __("No events found", "linkedevents");
          }

        } else {
          $result .= '<div class="error notice">Invalid response</div>';
        }

      } catch (\Metatavu\LinkedEvents\ApiException $e) {

        $result .= '<div class="error notice">';
        if ($e->getResponseBody()) {
          $result .= print_r($e->getResponseBody());
        } else {
          $result .= $e;
        }
        $result .= '</div>';
      }

      return $result;
    }

    /**
     * Filter method for block categories. Used to add custom category for Kunta API
     * 
     * @param array $categories categories
     * @param \WP_Post post being loaded
     */
    public function blockCategoriesFilter($categories, $post) {
      $categories[] = [
        'slug' => 'linkedevents',
        'title' => __( 'LinkedEvents', 'linkedevents' ),
      ];

      return $categories;
    }

    /**
     * Renders date input field
     * 
     * @param string $id field id
     * @param string $name field name
     * @param string $value field value
     * @param string $class field class
     * 
     * @return string generated HTML
     */
    private function renderDateInput($id, $name, $value, $class) {
      return sprintf('<input name="%s" id="%s" value="%s" class="%s" type="date" placeholder="YYYY-MM-DD" pattern="\d{4}-\d{2}-\d{2}"/>', esc_attr($name), esc_attr($id), esc_attr($value ? $value : ""), esc_attr($class));
    }

    /**
     * Renders select field
     * 
     * @param string $id field id
     * @param string $name field name
     * @param string $value field value
     * @param string $class field class
     * @param array options options
     * 
     * @return string generated HTML
     */
    private function renderSelectInput($id, $name, $value, $class, $options) {
      $optionsHtml = implode("", array_map(function ($option) use ($value) {
        return sprintf('<option value="%s"%s>%s</option>', esc_html($option["value"]), $option["value"] == $value ? ' selected="selected"' : "", esc_attr($option["label"]));
      }, $options));

      return sprintf('<select name="%s" id="%s" class="%s">%s</select>', esc_attr($name), esc_attr($id), esc_attr($class), $optionsHtml);
    }

    /**
     * Renders select field
     * 
     * @param string $idPrefix field id prefix
     * @param string $name field name
     * @param string[] values selected values
     * @param string $wrapperClass input field wrapper class
     * @param string $inputClass input field class
     * @param array options options
     * 
     * @return string generated HTML
     */
    private function renderChecklistInput($idPrefix, $name, $values, $wrapperClass, $inputClass, $options) {
      return implode("", array_map(function ($option, $index) use ($values, $idPrefix, $name, $value, $wrapperClass, $inputClass) {
        $id = "$idPrefix-$index";
        $checked = in_array($option["value"], $values);
        return sprintf('<div class="%s"><input type="checkbox" name="%s[]" id="%s" value="%s" class="%s"%s/><label for="%s">%s</label></div>', esc_attr($wrapperClass), esc_attr($name), esc_attr($id), esc_attr($option["value"]), esc_attr($inputClass), $checked ? ' checked=checked' : "", esc_attr($id), esc_html($option["label"]));
      }, $options, array_keys($options)));
    }
    
    /**
     * Returns search parameter value from request
     * 
     * @param string $name name of the parameter
     * @param string default default value
     * @return string parameter or default if not found 
     */
    private function getSearchParam($name, $default = null) {
      $result = strip_tags($_REQUEST["les-$name"]);
      $result = $result ? trim($result) : null;
      return $result ? $result : $default;
    }
    
    /**
     * Returns search parameter values from request
     * 
     * @param string $name name of the parameter
     * @param string default default value
     * @return string[] parameters or default if not found 
     */
    private function getSearchParams($name, $default) {
      $result = $_REQUEST["les-$name"];
      return is_array($result) ? $result : $default;
    }
    
    /**
     * Returns search parameter values from request as CDT
     * 
     * @param string $name name of the parameter
     * @param string default default value
     * @return string parameter values from request as CDT
     */
    private function getSearchParamsCDT($name, $default) {
      $result = implode(",", $this->getSearchParams($name, []));
      return $result ? $result : $default;
    }

    /**
     * Returns localized value with given language
     * 
     * @param array $values values
     * @param string $locale locale, if null given method finds best match for current locale 
     * @return string value or empty string if not defined
     */
    private function getLocalizedValue($values, $locale = null) {
      if ($locale == null) {
        $sortedLocales = \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getSupportedLangauges();
        $currentLocale = $this->getCurrentLanguage();

        usort($sortedLocales, function ($supportedLocale) use ($currentLocale) {
          return $supportedLocale == $currentLocale ? -1 : 0;
        });
      
        foreach ($sortedLocales as $sortedLocale) {
          $result = $this->getLocalizedValue($values, $sortedLocale);
          if ($result) {
            return $result;
          }
        }

        return "";
      }

      $result = $values[$locale];
      return $result ? $result : "";
    }

    /**
     * Resoles place name in current locale 
     */
    private function getCurrentLanguage() {
      $locale = get_locale();
      return substr($locale, 0, 2);
    }

    /**
     * Parses bbox from filter value.
     * 
     * @return string[] parsed bbox
     */
    private function parseBBoxFilter($string) {
      if (!$string) {
        return null;
      }

      return explode(",", preg_replace('/\s+/', '', $string));
    }

    /**
     * Parses ids from filter value.
     * 
     * @return string[] parsed location
     */
    private function parseIds($string) {
      if (!$string) {
        return null;
      }

      return explode(",", preg_replace('/\s+/', '', $string));
    }
    
    /**
     * Parses int from filter value
     * 
     * @return int int value
     */
    private function parseInt($string) {
      if  (!$string) {
        return null;
      }

      return \intval($string);
    }

    /**
     * Parses date from date filter value.
     * 
     * @return \DateTime parsed datetime
     */
    private function parseDateFilter($dateString) {
      if (!$dateString) {
        return null;
      }
      
      if ($dateString == "today") {
        $result = new \DateTime(); 
      } else {
        $dateString = \substr($dateString, 0, 10);
        $format = self::$DATE_FORMAT;
        $result = \DateTime::createFromFormat($format, $dateString);
      }

      if (!$result) {
        error_log("Failed to parse: " .$dateString);
        return null;
      }

      $result->setTime(0, 0);
      return $result;
    }

    /**
     * Removes part(s) of text that is in parens
     * 
     * @return e.g. input "Something (in parens)" -> "Something"
     */
    private function removeTextInParens($string) {
      $pattern = '/\w*[(]\S*[)]/';
      $replaced = preg_replace($pattern, '', $string);
      return trim($replaced);
    }

  }

}

new Blocks();

?>