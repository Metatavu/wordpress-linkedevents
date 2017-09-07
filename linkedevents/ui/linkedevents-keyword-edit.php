<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/linkedevents-keyword-edit-view.php');
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\UI\KeywordEdit' ) ) {
    
    class KeywordEdit extends KeywordEditView {
      
      public function __construct() {
        parent::__construct('linkedevents-edit-keyword.php', __('Edit Keyword', 'linkedevents'));
        
        add_action( 'admin_menu', function () {
          add_submenu_page(null, __('Edit Keyword', 'linkedevents'),  __('Edit Keyword', 'linkedevents'), 'linkedevents_edit_keywords', 'linkedevents-edit-keyword.php', array($this, 'render'));
        });
      }
      
      // TODO: validate
      
      public function render() {
        $keywordId = $this->getKeywordId();
            
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          try {
            $keyword = $this->findKeyword($keywordId);
            $this->updateKeywordName($keyword);
            $this->updateKeyword($keyword);
            $this->redirect("admin.php?page=linkedevents-edit-keyword.php&action=edit&keyword=$keywordId");
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
          $this->renderForm('admin.php?page=linkedevents-edit-keyword.php&keyword=' . $keywordId);
        }
      }
      
      protected function renderFormFields() {
        $keywordId = $this->getKeywordId();
        $keyword = $this->findKeyword($keywordId);
        $this->renderLocalizedTextInput(__('Name', 'linkedevents'), 'name', $keyword->getName());
      }
      
      private function getKeywordId() {
        return sanitize_text_field($_GET['keyword']);
      }
    }
    
  }

  add_action('init', function () {
    if (is_admin()) {
      new KeywordEdit();
    }
  });

?>