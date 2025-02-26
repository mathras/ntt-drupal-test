<?php

namespace Drupal\voting_system\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for creating answers.
 */
class AnswerForm extends FormBase {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  public function getFormId() {
    return 'voting_system_answer_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Resposta'),
      '#required' => TRUE,
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
    ];

    $form['image'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Image'),
      '#upload_location' => 'public://voting-system/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
      ],
    ];

    $form['question'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Pergunta'),
      '#target_type' => 'question',
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Salvar resposta '),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $answer = $this->entityTypeManager->getStorage('answer')->create([
      'label' => $form_state->getValue('label'),
      'description' => $form_state->getValue('description'),
      'image' => $form_state->getValue('image'),
      'question' => $form_state->getValue('question'),
    ]);
    $answer->save();

    $this->messenger()->addMessage($this->t('Answer "@label" has been created.', [
      '@label' => $form_state->getValue('label'),
    ]));

    $form_state->setRedirect('voting_system.question_list');
  }
}