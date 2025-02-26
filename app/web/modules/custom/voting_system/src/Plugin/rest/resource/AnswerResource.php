<?php

namespace Drupal\voting_system\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\voting_system\Entity\Answer; 

/**
 * Provides a REST resource to get and create answers.
 *
 * @RestResource(
 *   id = "answer_resource",
 *   label = @Translation("Answer resource"),
 *   uri_paths = {
 *     "canonical" = "/api/voting/answers/{answer_id}",
 *     "create" = "/api/voting/answers"
 *   }
 * )
 */
class AnswerResource extends ResourceBase {

  /**
   * Responds to GET requests.
   *
   * Returns details of a specific answer.
   */
  public function get($answer_id) {
    $answer = \Drupal::entityTypeManager()->getStorage('answer')->load($answer_id);

    if (!$answer) {
      return new ResourceResponse(['error' => 'Resposta não encontrada'], 404);
    }

    $response = [
      'id' => $answer->id(),
      'label' => $answer->label(),
      'description' => $answer->description(),
      'image' => $answer->image->entity ? $answer->image->entity->uri->value : null,
      'question_id' => $answer->question->id(),
    ];

    return new ResourceResponse($response);
  }

  /**
   * Responds to POST requests.
   *
   * Creates a new answer.
   */
  public function post(array $data) {
    // Valida os dados recebidos
    if (empty($data['label']) || empty($data['question_id'])) {
      return new ResourceResponse(['error' => 'Os campos "label" e "question_id" são obrigatórios.'], 400);
    }

    // Cria a nova resposta
    $answer = Answer::create([
      'label' => $data['label'],
      'description' => isset($data['description']) ? $data['description'] : '',
      'question' => $data['question_id'],
    ]);

    // Salva a nova resposta
    $answer->save();

    // Responde com os dados da resposta criada
    $response = [
      'id' => $answer->id(),
      'label' => $answer->label(),
      'description' => $answer->description(),
      'question_id' => $answer->question->id(),
    ];

    return new ResourceResponse($response, 201);
  }
}
