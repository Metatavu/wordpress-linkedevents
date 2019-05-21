<?php
  $result = "";

  $result .= '<article>';

  $eventName = $event["name"]["fi"];
  $eventLink = $event["externalLinks"][0]["link"];

  $result .= sprintf('<div><a href="%s">%s</a></div>', $eventLink, $eventName);
  
  $result .= '</article>';

  echo $result;
?>