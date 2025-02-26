<?php

namespace Drupal\voting_system\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Question entity.
 *
 * @ContentEntityType(
 *   id = "question",
 *   label = @Translation("Question"),
 *   base_table = "question",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 * )
 */
class Question extends ContentEntityBase {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Pergunta'))
      ->setDescription(t('The label of the question.'))
      ->setRequired(TRUE);

    $fields['identifier'] = BaseFieldDefinition::create('string')
      ->setLabel(t('identificador'))
      ->setDescription(t('A unique identifier for the question.'))
      ->setRequired(TRUE);

    $fields['show_votes'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Exibir Votos'))
      ->setDescription(t('Whether to show the total votes for this question.'))
      ->setDefaultValue(TRUE);

    return $fields;
  }
}