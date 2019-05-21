<?php
namespace Metatavu\LinkedEvents\Wordpress\Gutenberg\Blocks;

require_once(__DIR__ . '/../../templates/template-loader.php');

defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );

if (!class_exists( 'Metatavu\LinkedEvents\Wordpress\Gutenberg\Blocks\Blocks' ) ) {

  /**
   * Class for handling Gutenberg blocks
   */
  class Blocks {
    
    /**
     * Constructor
     */
    public function __construct() {
      add_action('init', [$this, "onInit"]);
      add_action('save_post', [$this, "onSavePost"], 10, 3);
    }

    /**
     * Action executed on init
     */
    public function onInit() {
      wp_register_script('linkedevents-blocks', plugins_url( 'js/linkedevents-blocks.js', __FILE__ ), ['wp-blocks', 'wp-element', 'wp-i18n']);      
      wp_set_script_translations("linkedevents-blocks", "linkedevents", dirname(__FILE__) . '/lang/');
      add_filter("block_categories", [ $this, "blockCategoriesFilter"], 10, 2);

      wp_localize_script('linkedevents-blocks', 'linkedEventsBlocks', [ ]);

      register_block_type('linkedevents/list-block', [
        'attributes' => [ ],
        'editor_script' => 'linkedevents-blocks',
        'render_callback' => [ $this, "renderListBlock" ]
      ]);
    }

    /**
     * Save post action handler
     * 
     * @param int $postId The post ID.
     * @param \WP_Post $post The post object.
     * @param bool $update Whether this is an existing post being updated or not.
     */
    public function onSavePost($pageId, $post, $update) {
      if (has_blocks($post->post_content)) {
        
      }
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
     *   @type string $publicationStatus Filter events by publication status (either draft or public) (optional)
     * }
     */
    public function renderListBlock($attributes) {
      $result = '';
      $eventsApi = \Metatavu\LinkedEvents\Wordpress\Api::getEventApi();

      $include = null;
      $text = $attributes["text"];
      $lastModifiedSince = null;
      $start = $attributes["start"];
      $end = $attributes["end"];
      $bbox = null; // TODO:
      $dataSource = \Metatavu\LinkedEvents\Wordpress\Settings\Settings::getValue("datasource"); // TODO
      $location = $attributes["location"];
      $showAll = false; // TODO
      $division = null;
      $keyword = null;
      $recurring = null;
      $minDuration = null;
      $maxDuration = null;
      $publisher = null;
      $sort = null;
      $page = null; // TODO;
      $pageSize = null; // TODO
      $addressLocalityFi = null;
      $addressLocalitySv = null; 
      $addressLocalityEn = null; 
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
          $keyword,
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
          $publicationStatus);

        if ($events->valid()) {
          $templateData = [
            "events" => $events->getData()
          ];

          $templateLoader = new \Metatavu\LinkedEvents\TemplateLoader();

          ob_start();
          $templateLoader->set_template_data($templateData)->get_template_part('events');
          $result = ob_get_contents();
          ob_end_clean();

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

  }

}

new Blocks();

?>