<?php

  namespace Metatavu\LinkedEvents\Wordpress\Notifications;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( '\Metatavu\LinkedEvents\Wordpress\Notifications\Notifier' ) ) {
    
    class Notifier {
      
      /**
       * Notify notification enabled users about a draft events
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       */
      public static function notifyDraft($event) {
        $users = self::listUsers();
        $emails = [];
        $userIds = [];
        
        foreach ($users as $user) {
          if (!self::isUserNotifiedDraftEvent($user->ID, $event->getId())) {
            $emails[] = $user->user_email;
            $userIds[] = $user->ID;
          }
        }
        
        if (count($emails) > 0) {
          if (self::sendEmails($emails, self::getMailSubject(), self::getMailContents($event))) {
            self::addUsersNotifiedDraftEvent($userIds, $event->getId());
          } else {
           error_log("Failed to send notification emails about draft events");
          }
        }
      }
      
      /**
       * Sends emails to specified addresess
       * 
       * @param string[] $emails email addresses
       * @param string $subject subject
       * @param string $contents content
       * @return bool whether emails were sent successfully
       */
      private static function sendEmails($emails, $subject, $contents) {
        add_filter('wp_mail_content_type', ['\Metatavu\LinkedEvents\Wordpress\Notifications\Notifier', 'htmlMailContentType']);
        $result = wp_mail($emails, $subject, $contents);
        remove_filter('wp_mail_content_type', ['\Metatavu\LinkedEvents\Wordpress\Notifications\Notifier', 'htmlMailContentType']);
        
        return $result;
      }
      
      /**
       * Filter for allowing html mails
       * 
       * @return string html mime type
       */
      public static function htmlMailContentType() {
        return 'text/html';
      }
      
      /**
       * Returns email subject for the notification mail
       * 
       * @return string email subject
       */
      private static function getMailSubject() {
        $siteName = get_bloginfo('name');
        return sprintf(__('A new event proposal on %s', 'linkedevents'), $siteName);
      }
      
      /**
       * Returns email content for the notification mail
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       * @return string email content
       */
      private static function getMailContents($event) {
        $adminUrl = admin_url();
        $siteName = get_bloginfo('name');
        $eventName = self::getEventName($event);
        $eventDescription =  self::getEventDescription($event);
        
        $result = sprintf(__('<p>A new event has been proposed for your site %s.</p>', 'linkedevents'), $siteName);
        $result .= __('<p>The event is currently in draft mode and does not appear publicly before approval.</p>', 'linkedevents');
        $result .= __('<p>Event details:</p>', 'linkedevents');
        $result .= sprintf(__('<p>Name: %s<br/>Description: %s</p>', 'linkedevents'), $eventName, $eventDescription);
        $result .= sprintf(__('<p>Login at <a href="%s">%s</a> to publish or modify the event</p>', 'linkedevents'), $adminUrl, $adminUrl);
        $result .= __('<hr/><p>This is an automatic message, do not respond</p>', 'linkedevents');
        
        return $result;
      }
      
      /**
       * Returns event's name
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       * @return string event's name
       */
      private static function getEventName($event) {
        $eventName = $event->getName();
        if (!$eventName) {
          return null;
        }
        
        if (!empty($eventName->getFi())) {
          return $eventName->getFi();
        }
        
        if (!empty($eventName->getSv())) {
          return $eventName->getSv();
        }
        
        return $eventName->getEn();
      }
      
      /**
       * Returns event's description
       * 
       * @param \Metatavu\LinkedEvents\Model\Event $event
       * @return string event's description
       */
      private static function getEventDescription($event) {
        $eventDescription = $event->getDescription();
        if (!$eventDescription) {
          return null;
        }
        
        if (!empty($eventDescription['fi'])) {
          return $eventDescription['fi'];
        }
        
        if (!empty($eventDescription['sv'])) {
          return $eventDescription['sv'];
        }
        
        return $eventDescription['en'];
      }
      
      /**
       * Returns list of users with draft notification enabled
       * 
       * @return \WP_User[] users with draft notification enabled
       */
      private static function listUsers() {
        return get_users([
          'meta_key'     => 'linkedevents_notifications',
          'meta_value'   => true,
          'number'       => -1
        ]);
      }
      
      /**
       * Returns whether user has been already notified about draft event
       * 
       * @param int $userId user id
       * @param string $eventId event id
       * @return boolean whether user has been already notified about draft event
       */
      private static function isUserNotifiedDraftEvent($userId, $eventId) {
        $notified = get_user_meta($userId, 'linkedevents_events_notified', true);
        if (!$notified) {
          return false;
        }
        
        return in_array($eventId, $notified);
      }
      
      /**
       * Sets draft notified setting for list of users
       * 
       * @param int $userIds user ids
       * @param string $eventId event id
       */
      private static function addUsersNotifiedDraftEvent($userIds, $eventId) {
        foreach ($userIds as $userId) {
          self::addUserNotifiedDraftEvent($userId, $eventId);
        }
      }
      
      /**
       * Sets draft notified setting for an user
       * 
       * @param int $userId user id
       * @param string $eventId event id
       */
      private static function addUserNotifiedDraftEvent($userId, $eventId) {
        $notified = get_user_meta($userId, 'linkedevents_events_notified', true);
        if (!$notified) {
          $notified = [];
        }
        
        $notified[] = $eventId;
        
        update_usermeta($userId, 'linkedevents_events_notified', array_unique($notified));
      }
            
    }
  }
    
?>