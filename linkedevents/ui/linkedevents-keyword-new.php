<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/linkedevents-keyword-edit-view.php');
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\KeywordNew' ) ) {
    
    class KeywordNew extends KeywordEditView {
      
      public function __construct() {
        parent::__construct('linkedevents-new-keyword.php', __('New Keyword', 'linkedevents'));
        
        add_action( 'admin_menu', function () {
          add_submenu_page("linked-events.php", __('New Keyword', 'linkedevents'),  __('New Keyword', 'linkedevents'), 'manage_options', 'linkedevents-new-keyword.php', array($this, 'render'));
        });
      }
      
      // TODO: validate
      
      public function render() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          try {
            $keyword = $this->getNewKeyword();
            $this->updateKeywordName($keyword);
            $keyword = $this->createKeyword($keyword);
            $newKeywordId = $keyword->getId();
            $this->redirect("admin.php?page=linkedevents-edit-keyword.php&action=edit&keyword=$newKeywordId");
            exit;
          } catch (\Metatavu\LinkedEvents\ApiException $e) {
            echo '<div class="error notice">';
            if ($e->getResponseBody()) {
              echo print_r($e->getResponseBody());
            } else {
              echo $e;
            }
            echo '</div>';
          }
        } else {
          $this->renderForm('admin.php?page=linkedevents-new-keyword.php');
        }
      }
      
      protected function renderFormFields() {
        $this->renderLocalizedTextInput(__('Name', 'linkedevents'), 'name', null);
      }
      
    }
    
  }

  add_action('init', function () {
    if (is_admin()) {
      new KeywordNew();
    }
  });

?>