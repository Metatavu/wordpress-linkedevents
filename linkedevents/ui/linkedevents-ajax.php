<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  add_action('wp_ajax_linkedevents_places', function () {
    $filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi(true);
    
    $search = $_GET['q'];
    $placeResponse = $filterApi->placeList(null, null, true, null, null, $search);
    $places = $placeResponse->getData();
    $responce = [];
    
    foreach ($places as $place) {
      $responce[] = [
        value => $place->getId(),
        label => $place->getName()->getFi()
      ];
    }
    
    echo json_encode($responce);
    
    wp_die();
  });
  
  add_action('wp_ajax_linkedevents_keywords', function () {
    $filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi(true);
    
    $search = $_GET['q'];
    $keywordResponse = $filterApi->keywordList(null, null, null, true, null, $search);
    $keywords = $keywordResponse->getData();
    $responce = [];
    
    foreach ($keywords as $keyword) {
      $responce[] = [
        value => $keyword->getId(),
        label => $keyword->getName()->getFi()
      ]; 
    }
    
    echo json_encode($responce);
    
    wp_die();
  });
  
  add_action('wp_ajax_linkedevents_delete_event', function () {
    if (!current_user_can('linkedevents_delete_event')) {
      wp_die("User does not have permission to remove event", 403);
      return;
    }
    
    $eventApi = \Metatavu\LinkedEvents\Wordpress\Api::getEventApi(true);
    $id = $_GET['id'];
    
    $eventApi->eventDelete($id);

    wp_die();
  });
  
  add_action('wp_ajax_linkedevents_delete_place', function () {
    if (!current_user_can('linkedevents_delete_place')) {
      wp_die("User does not have permission to remove place", 403);
      return;
    }
    
    $filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi(true);
    $id = $_GET['id'];
    $filterApi->placeDelete($id);

    wp_die();
  });
  
  add_action('wp_ajax_linkedevents_delete_keyword', function () {
    if (!current_user_can('linkedevents_delete_keyword')) {
      wp_die("User does not have permission to remove keyword", 403);
      return;
    }
    
    $filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi(true);
    $id = $_GET['id'];
    $filterApi->keywordDelete($id);

    wp_die();
  });
  
?>