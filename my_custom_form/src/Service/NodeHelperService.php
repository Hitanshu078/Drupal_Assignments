<?php

namespace Drupal\my_custom_form\Service;

use Drupal\node\Entity\Node;

class NodeHelperService {

  public function validateTitle($title) {
    return is_string($title) && strlen(trim($title)) >= 2;
  }


  public function validateBody($body) {
    return is_string($body) && strlen(trim($body)) > 0;
  }


  public function getCustomNodes($content_type = 'custom_article') {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', $content_type)
      ->sort('created', 'DESC')
      ->execute();

    return Node::loadMultiple($nids);
  }
  
  public function createNode($title, $body, $content_type = 'custom_article') {
    $node = Node::create([
      'type' => $content_type,
      'title' => $title,
      'body' => [
        'value' => $body,
        'format' => 'basic_html',
      ],
    ]);
    $node->save();

    return $node;
  }
}
