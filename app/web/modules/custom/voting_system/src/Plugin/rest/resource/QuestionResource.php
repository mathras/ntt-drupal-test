<?php

namespace Drupal\voting_system\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\voting_system\Entity\Question;  
use Drupal\Core\Field\FieldDefinition;

/**
 * Provides a REST resource to get and create questions.
 *
 * @RestResource(
 *   id = "question_resource",
 *   label = @Translation("Question resource"),
 *   uri_paths = {
 *     "canonical" = "/api/voting/questions/{question_id}",
 *     "create" = "/api/voting/questions"  
 *   }
 * )
 */
class QuestionResource extends ResourceBase {


  public function get($question_id) {
    $question = \Drupal::entityTypeManager()->getStorage('question')->load($question_id);

    if (!$question) {
      return new ResourceResponse(['error' => 'Pergunta não encontrada'], 404);
    }

    $response = [
      'id' => $question->id(),
      'label' => $question->label()
    ];

    return new ResourceResponse($response);
  }


  public function post(array $data) {
    if (empty($data['label'])) {
      return new ResourceResponse(['error' => 'O campo "label" é obrigatório.'], 400);
    }

    $question = Question::create([
      'label' => $data['label'],
    ]);

    $question->save();
    $response = [
      'id' => $question->id(),
      'label' => $question->label()
    ];

    return new ResourceResponse($response, 201);
  }
}
