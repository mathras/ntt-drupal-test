<?php

namespace Drupal\voting_system\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class QuestionForm extends FormBase {

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
    return 'voting_system_question_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Titulo da Pergunta'),
      '#required' => TRUE,
    ];

    $form['identifier'] = [
      '#type' => 'machine_name',
      '#title' => $this->t('Identifier'),
      '#machine_name' => [
        'exists' => [$this, 'checkIdentifierExists'],
        'source' => ['label'],
      ],
      '#required' => TRUE,
    ];

    $form['show_votes'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Exibir votos'),
      '#default_value' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Salvar pergunta'),
    ];

    return $form;
  }

  public function checkIdentifierExists($identifier) {
    $question = $this->entityTypeManager->getStorage('question')->loadByProperties(['identifier' => $identifier]);
    return !empty($question);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $question = $this->entityTypeManager->getStorage('question')->create([
      'label' => $form_state->getValue('label'),
      'identifier' => $form_state->getValue('identifier'),
      'show_votes' => $form_state->getValue('show_votes'),
    ]);
    $question->save();

    $this->messenger()->addMessage($this->t('Question "@label" has been created.', [
      '@label' => $form_state->getValue('label'),
    ]));

    $form_state->setRedirect('voting_system.question_list');
  }
}