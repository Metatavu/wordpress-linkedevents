<?php
  $result = "";

  $result .= '<article>';
  $language = $data->language;

  $eventName = $event["name"][$language];
  $eventLink = $event["externalLinks"][0]["link"];

  $result .= sprintf('<div><a href="%s">%s</a></div>', $eventLink, $eventName);
  
  $result .= '</article>';

  echo $result;
?>