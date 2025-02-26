<?php

namespace Drupal\voting_system\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Answer entity.
 *
 * @ContentEntityType(
 *   id = "answer",
 *   label = @Translation("Answer"),
 *   base_table = "answer",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "description" = "description",
 *     "uuid" = "uuid",
 *   },
 * )
 */
class Answer extends ContentEntityBase {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Resposta'))
      ->setDescription(t('The label of the answer.'))
      ->setRequired(TRUE);

      $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Descrição'))
      ->setDescription(t('A brief description of the answer.'))
      ->setRequired(FALSE);  // Não é obrigatório
    

    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Imagem'))
      ->setDescription(t('An image for the answer.'));

    $fields['question'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Pergunta'))
      ->setDescription(t('The question this answer belongs to.'))
      ->setSetting('target_type', 'question')
      ->setRequired(TRUE);

    return $fields;
  }
}