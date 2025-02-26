<?php

namespace Drupal\voting_system\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\voting_system\Entity\Question;
use Symfony\Component\HttpFoundation\Request;

class VotingSystemController extends ControllerBase
{
  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager)
  {
    $this->entityTypeManager = $entityTypeManager;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  public function listQuestions() {
    $questions = $this->entityTypeManager->getStorage('question')->loadMultiple();
  
    $header = ['Pergunta', 'Ações'];
    $rows = [];
  
    foreach ($questions as $question) {
      $rows[] = [
        $question->label(),
        [
          'data' => [
            '#type' => 'operations',
            '#links' => [
              'edit' => [
                'title' => 'Editar',
                'url' => Url::fromRoute('voting_system.question_edit', ['question' => $question->id()]),
              ],
              'add_answer' => [
                'title' => 'Adicionar Resposta',
                'url' => Url::fromRoute('voting_system.answer_add', ['question' => $question->id()]),
              ],
              'view' => [
                'title' => 'Visualizar',
                'url' => Url::fromRoute('voting_system.vote_page', ['question' => $question->id()]),
              ],
            ],
          ],
        ],
      ];
    }

    $build['questions'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => 'Nenhuma pergunta disponível.',
    ];

    $build['add_question'] = [
      '#type' => 'link',
      '#title' => 'Adicionar Pergunta',
      '#url' => Url::fromRoute('voting_system.question_add'),
      '#attributes' => ['class' => ['button', 'button--primary']],
    ];
  
    return $build;
  }

  public function editQuestion(Question $question) {
    $form = \Drupal::formBuilder()->getForm('Drupal\voting_system\Form\QuestionEditForm', $question);
    return $form;
  }

  public function votePage(Question $question)
  {
    $answers = $this->entityTypeManager->getStorage('answer')->loadByProperties(['question' => $question->id()]);
    $votes = [];
    $answer_data = [];
    $total_votes = 0;

    foreach ($answers as $answer) {
      $vote_count = $this->entityTypeManager->getStorage('vote')
        ->getQuery()
        ->accessCheck(FALSE)
        ->condition('answer_id', $answer->id())
        ->count()
        ->execute();
      $votes[$answer->id()] = $vote_count;
      $total_votes += $vote_count;

      $image_field = $answer->get('image')->getValue();
      $image_url = '';
      if (!empty($image_field) && !empty($image_field[0]['target_id'])) {
        $file = \Drupal\file\Entity\File::load($image_field[0]['target_id']);
        if ($file) {
          $image_url = $file->createFileUrl();
        }
      }

      $answer_data[$answer->id()] = [
        'entity' => $answer,
        'image_url' => $image_url,
      ];
    }

    $vote_percentages = [];
    foreach ($votes as $answer_id => $vote_count) {
      $vote_percentages[$answer_id] = $total_votes > 0 ? round(($vote_count / $total_votes) * 100, 2) : 0;
    }

    $config = \Drupal::config('voting_system.settings');
    $disable_voting = $config->get('disable_voting');

    $build = [
      '#theme' => 'voting_system_question',
      '#question' => $question,
      '#answers' => $answer_data,
      '#show_votes' => $question->get('show_votes')->value,
      '#votes' => $votes,
      '#vote_percentages' => $vote_percentages,
      '#total_votes' => $total_votes,
      '#disable_voting' => $disable_voting,
    ];

    return $build;
  }

  public function submitVote(Question $question, Request $request)
  {
    $answer_id = $request->query->get('answer');

    if (!$answer_id) {
      return $this->redirect('voting_system.vote_page', ['question' => $question->id()]);
    }

    $answer = $this->entityTypeManager->getStorage('answer')->load($answer_id);

    if (!$answer || $answer->get('question')->target_id != $question->id()) {
      return $this->redirect('voting_system.vote_page', ['question' => $question->id()]);
    }

    $vote = $this->entityTypeManager->getStorage('vote')->create([
      'user_id' => \Drupal::currentUser()->id(),
      'question_id' => $question->id(),
      'answer_id' => $answer->id(),
    ]);
    $vote->save();

    return $this->redirect('voting_system.vote_page', ['question' => $question->id()]);
  }
}
