<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\KeywordEditView' ) ) {
    
    class KeywordEditView extends AbstractEditView {
      
      private $filterApi;
      
      public function __construct($pageTitle) {
        parent::__construct($pageTitle);
        $this->filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi();
      }
      
      /**
       * Creates new keyword
       * 
       * @param \Metatavu\LinkedEvents\Model\Keyword $keyword
       * @return \Metatavu\LinkedEvents\Model\Keyword created keyword
       */
      protected function createKeyword($keyword) {
        return $this->filterApi->keywordCreate($keyword);
      }
      
      /**
       * Updates keyword
       * 
       * @param \Metatavu\LinkedEvents\Model\Keyword $keyword
       * @return \Metatavu\LinkedEvents\Model\Keyword updated keyword
       */
      protected function updateKeyword($keyword) {
        return $this->filterApi->keywordUpdate($keyword->getId(), $keyword);
      }
      
      /**
       * Creates new prefilled keyword object 
       * 
       * @return \Metatavu\LinkedEvents\Model\Keyword created event object
       */
      protected function getNewKeyword() {
        $keyword = new \Metatavu\LinkedEvents\Model\Keyword();
        $keyword->setName(new \Metatavu\LinkedEvents\Model\KeywordName());
        return $keyword; 
      }
      
      /**
       * Updates keyword name into model
       * 
       * @param \Metatavu\LinkedEvents\Model\Keyword $keyword
       */
      protected function updateKeywordName($keyword) {
        $name = $keyword->getName();
        $name->setFi($this->getLocalizedPostString('name', "fi"));
        $name->setSv($this->getLocalizedPostString('name', "sv"));
        $name->setEn($this->getLocalizedPostString('name', "en"));
        $keyword->setName($name);
      }

    }
  }
    
?>

      
      