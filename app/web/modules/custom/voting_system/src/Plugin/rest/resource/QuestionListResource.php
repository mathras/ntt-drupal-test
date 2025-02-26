<?php

namespace Drupal\voting_system\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides a REST resource to list all questions.
 *
 * @RestResource(
 *   id = "question_list_resource",
 *   label = "Question List Resource",
 *   uri_paths = {
 *     "canonical" = "/api/voting/questions"
 *   }
 * )
 */
class QuestionListResource extends ResourceBase {

  /**
   * Responds to GET requests.
   *
   * Returns a list of all questions.
   */
  public function get() {
    // Carrega todas as perguntas.
    $questions = \Drupal::entityTypeManager()->getStorage('question')->loadMultiple();


    $response = [];
    foreach ($questions as $question) {
      $response[] = [
        'id' => $question->id(),
        'label' => $question->label(),
      ];
    }

    return new ResourceResponse($response);
  }
}