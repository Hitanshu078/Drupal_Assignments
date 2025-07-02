<?php

namespace Drupal\my_custom_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;


class CustomFormController extends ControllerBase {

  public function show() {
    $form = \Drupal::formBuilder()->getForm('Drupal\my_custom_form\Form\CustomNodeForm');
    return $form;
  }

  public function getAllNodes(Request $request) {
    \Drupal::logger('my_custom_form')->notice('getAllNodes() called');
    
    try {
      $nids = \Drupal::entityQuery('node')
        ->accessCheck(TRUE)
        ->condition('type', 'custom_article')
        ->execute();
  
      $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
      $data = [];
  
      foreach ($nodes as $node) {
        $data[] = [
          'nid' => $node->id(),
          'title' => $node->getTitle(),
          'body' => $node->hasField('body') ? $node->get('body')->value : '',
        ];
      }
  
      return new \Symfony\Component\HttpFoundation\JsonResponse($data);
  
    } catch (\Exception $e) {
      \Drupal::logger('my_custom_form')->error($e->getMessage());
      return new \Symfony\Component\HttpFoundation\JsonResponse([
        'status' => 'error',
        'message' => $e->getMessage(),
      ], 500);
    }
  }
  


  public function createNode(Request $request) {
    $data = json_decode($request->getContent(), true);
  
    if (!isset($data['title'])) {
      return new JsonResponse(['error' => 'Title is required'], 400);
    }
  
    $node = Node::create([
      'type' => 'custom_article',
      'title' => $data['title'],
      'body' => $data['body'] ?? '',
    ]);
    $node->save();
  
    return new JsonResponse(['status' => 'Node created', 'nid' => $node->id()], 201);
  }
  
  
}
