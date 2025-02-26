<?php

namespace Drupal\voting_system\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\voting_system\Entity\Question;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Formulário para editar uma pergunta.
 */
class QuestionEditForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'voting_system_question_edit';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Question $question = NULL)
  {
    if ($question) {
      $form['question_id'] = [
        '#type' => 'hidden',
        '#value' => $question->id(),
      ];

      $form['label'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Titulo da Pergunta'),
        '#default_value' => $question->label(),
        '#required' => TRUE,
      ];

      $form['show_votes'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Exibir votos'),
        '#description' => $this->t('Habilite para mostrar o total de votos para esta pergunta.'),
        '#default_value' => $question->get('show_votes')->value,
      ];
    }

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $question_id = $form_state->getValue('question_id');
    $question = Question::load($question_id);

    if ($question) {
      $question->set('label', $form_state->getValue('label'));

      $show_votes = $form_state->getValue('show_votes') ? TRUE : FALSE;
      $question->set('show_votes', $show_votes);

      $question->save();

    } else {
      \Drupal::messenger()->addMessage($this->t('The question could not be loaded.'), MessengerInterface::TYPE_ERROR);
    }
  }
}