<?php

namespace Drupal\my_custom_form\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class ApiController extends ControllerBase {
  public function getNodes() {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'custom_article')
      ->sort('created', 'DESC');
      
    $nid = $query->execute();
    $nodes = Node::loadMultiple($nid);

    $data = [];
    foreach ($nodes as $node) {
      $data[] = [
        'nid' => $node->id(),
        'title' => $node->getTitle(),
        'description' => $node->get('body')->value,
      ];
    }

    return new JsonResponse($data);
  }
}