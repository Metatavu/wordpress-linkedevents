<?php

  namespace Metatavu\LinkedEvents\Wordpress\UI;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once( __DIR__ . '/../linkedevents-api.php');
  
  add_action('wp_ajax_linkedevents_places', function () {
    // TODO: Check permissions
    
    $filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi();
    
    $search = $_GET['q'];
    $placeResponse = $filterApi->placeList(null, null, true, null, $search);
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
    // TODO: Check permissions
    
    $filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi();
    
    $search = $_GET['q'];
    $keywordResponse = $filterApi->keywordList(null, null, null, null, null, $search);
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
    // TODO: Check permissions
    
    $eventApi = \Metatavu\LinkedEvents\Wordpress\Api::getEventApi();
    $id = $_GET['id'];
    
    $eventApi->eventDelete($id);

    wp_die();
  });
  
  add_action('wp_ajax_linkedevents_delete_place', function () {
    // TODO: Check permissions
    
    $filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi();
    $id = $_GET['id'];
    $filterApi->placeDelete($id);

    wp_die();
  });
  
  add_action('wp_ajax_linkedevents_delete_keyword', function () {
    // TODO: Check permissions
    
    $filterApi = \Metatavu\LinkedEvents\Wordpress\Api::getFilterApi();
    $id = $_GET['id'];
    $filterApi->keywordDelete($id);

    wp_die();
  });
  
?>