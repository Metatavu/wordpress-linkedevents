<?php
  $result = "";

  $result .= '<article>';
  $language = $data->language;
  $fieldConfig = $data->fieldConfig;
  $fieldsToShow = [];
  if( !empty($fieldConfig) ) {
    error_log($fieldConfig);
    $fieldsToShow = explode(',', $fieldConfig);
  }

  $eventName = $event["name"][$language];
  $eventLink = $event["externalLinks"][0]["link"];
  $shortDescription = $event["shortDescription"][$language];
  $description = $event["description"][$language];

  if( empty($fieldsToShow) ) {
    $result .= sprintf('<div><a href="%s">%s</a></div>', $eventLink, $eventName);
  } else {
    $result .= sprintf('<ol>');
    foreach($fieldsToShow as $field) {
      $result .= sprintf("<li>%s</li>", $event[$field][$language]);
    }
    $result .= sprintf('</ol>');
  }
  $result .= '</article>';

  echo $result;
?>