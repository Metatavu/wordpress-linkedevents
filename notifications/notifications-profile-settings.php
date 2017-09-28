<?php

  namespace Metatavu\LinkedEvents\Wordpress\Notifications;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\Notifications\ProfileSettings' ) ) {
    
    class ProfileSettings {
      
      public function __construct() {
        add_action('edit_user_profile', [ $this, "onEditUserProfile" ]);
        add_action('show_user_profile', [ $this, "onEditUserProfile" ]);
        add_action('personal_options_update', [ $this, "onUserOptionsUpdate" ]);
        add_action('edit_user_profile_update', [ $this, "onUserOptionsUpdate" ]);
      }
      
      /**
       * Action hook executed when user profile page is displayed
       * 
       * @param \WP_User $user user object
       */
      public function onEditUserProfile($user) {
        $notifications = get_user_meta($user->ID, 'linkedevents_notifications', true);
        $checkedAttr = $notifications ? ' checked="checked"' : ''; 
        
        echo '<h3>'. __('Linked Events', 'linkedevents') . '</h3>';
        
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="user_avatar">' . __('Notify about draft events', 'linkedevents') . '</label></th>';
        echo '<td>';
        echo '<input id="linkedevents_notifications" name="linkedevents_notifications" type="checkbox" value="1"' . $checkedAttr . '/>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
      }
      
      /**
       * Action hook executed when user options are updated
       * 
       * @param int $userId user's id
       * @return boolean 
       */
      public function onUserOptionsUpdate($userId) {
        if (!current_user_can('edit_user', $userId)) { 
          return;
        }

        update_usermeta($userId, 'linkedevents_notifications', $this->getPostBoolean('linkedevents_notifications'));
      }
      
      /**
       * Returns boolean value from HTTP POST body
       * 
       * @param type $name parameter name
       * @return bool boolean value from HTTP POST body
       */
      private function getPostBoolean($name) {
        return sanitize_text_field($_POST[$name]) === '1';
      }
      
    }
  }
  
  add_action('init', function () {
    if (is_admin()) {
      new ProfileSettings();
    }
  });
    
?>