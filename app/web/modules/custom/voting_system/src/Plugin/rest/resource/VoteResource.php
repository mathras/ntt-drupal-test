<?php

namespace Drupal\voting_system\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\voting_system\Entity\Vote;  

/**
 * Provides a REST resource to get and create votes.
 *
 * @RestResource(
 *   id = "vote_resource",
 *   label = @Translation("Vote resource"),
 *   uri_paths = {
 *     "canonical" = "/api/voting/votes/{vote_id}",
 *     "create" = "/api/voting/votes" 
 *   }
 * )
 */
class VoteResource extends ResourceBase {

  /**
   * Responds to GET requests.
   *
   * Returns details of a specific vote.
   */
  public function get($vote_id) {
    $vote = \Drupal::entityTypeManager()->getStorage('vote')->load($vote_id);

    if (!$vote) {
      return new ResourceResponse(['error' => 'Voto não encontrado'], 404);
    }

    $response = [
      'id' => $vote->id(),
      'user_id' => $vote->user_id->id(),
      'question_id' => $vote->question_id->id(),
      'answer_id' => $vote->answer_id->id(),
    ];

    return new ResourceResponse($response);
  }

  
  public function post(array $data) {
    if (empty($data['user_id']) || empty($data['question_id']) || empty($data['answer_id'])) {
      return new ResourceResponse(['error' => 'Os campos "user_id", "question_id" e "answer_id" são obrigatórios.'], 400);
    }

    $vote = Vote::create([
      'user_id' => $data['user_id'],
      'question_id' => $data['question_id'],
      'answer_id' => $data['answer_id'],
    ]);

    $vote->save();

    $response = [
      'id' => $vote->id(),
      'user_id' => $vote->user_id->id(),
      'question_id' => $vote->question_id->id(),
      'answer_id' => $vote->answer_id->id(),
    ];

    return new ResourceResponse($response, 201);
  }
}
