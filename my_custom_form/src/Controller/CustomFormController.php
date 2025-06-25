<?php

namespace Drupal\my_custom_form\Controller;

use Drupal\Core\Controller\ControllerBase;

class CustomFormController extends ControllerBase {
  public function show() {
    $form = \Drupal::formBuilder()->getForm('Drupal\my_custom_form\Form\CustomNodeForm');
    return $form;
  }
}

