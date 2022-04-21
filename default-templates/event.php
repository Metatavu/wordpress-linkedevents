<?php
  $language = $data->language;
  if (!$language) {
    $language = "fi";
  }

  $result = '<article>';
  $eventName = $event["name"][$language];
  $eventLink = $event["externalLinks"][0]["link"];
  $result .= sprintf('<div><a href="%s">%s</a></div>', $eventLink, $eventName);
  $result .= '</article>';

  echo $result;
?>