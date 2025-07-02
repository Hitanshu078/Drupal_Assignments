<?php

namespace Drupal\my_custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class CustomNodeForm extends FormBase {

  public function getFormId() {
    return 'custom_node_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['title'] = [ 
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#required' => TRUE,
    ];

    $form['body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Hello'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create Node'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $title = $form_state->getValue('title');
    $body = $form_state->getValue('body');

    $node = Node::create([
      'type' => 'custom_article',
      'title' => $title,
      'body' => [
        'value' => $body,
        'format' => 'plain_text',
      ],
    ]);
    

    $node->save();
    $this->messenger()->addMessage($this->t('Node created with ID @id', ['@id' => $node->id()]));
  }
}