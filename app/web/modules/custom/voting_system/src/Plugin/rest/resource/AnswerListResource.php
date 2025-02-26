<?php

namespace Drupal\voting_system\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\voting_system\Entity\Answer;
use Drupal\voting_system\Entity\Vote;
use Drupal\Core\Database\Database;

/**
 * Provides a REST resource to list answers for a question with vote counts.
 *
 * @RestResource(
 *   id = "answer_list_resource",
 *   label = @Translation("Answer List Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/voting/answers/list/{question_id}"
 *   }
 * )
 */
class AnswerListResource extends ResourceBase {

  /**
   * Responds to GET requests.
   *
   * Returns a list of answers for a specific question with vote counts.
   */
  public function get($question_id) {
    $answers = \Drupal::entityTypeManager()->getStorage('answer')->loadByProperties(['question' => $question_id]);

    if (empty($answers)) {
      return new ResourceResponse(['error' => 'Nenhuma resposta encontrada para esta pergunta.'], 404);
    }

    $response = [];
    foreach ($answers as $answer) {
      $vote_count = $this->countVotesForAnswer($answer->id());

      $response[] = [
        'id' => $answer->id(),
        'label' => $answer->label(),
        'description' => $answer->get('description')->value,
        'vote_count' => $vote_count,
      ];
    }

    return new ResourceResponse($response);
  }

  /**
   * Count the number of votes for a specific answer.
   *
   * @param int $answer_id
   *   The ID of the answer.
   *
   * @return int
   *   The number of votes.
   */
  private function countVotesForAnswer($answer_id) {
    $query = \Drupal::database()->select('vote', 'v')
      ->condition('v.answer_id', $answer_id)
      ->countQuery();
    return $query->execute()->fetchField();
  }
}
