<?php
namespace Drupal\mentions\Entity;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\mentions\MentionInterface;

/**
 *
 * @ContentEntityType(
 *   id = "mention",
 *   label = @Translation("Mention"),
 *   base_table = "mention",
 *   data_table = "mention_data",
 *   entity_keys = {
 *     "id" = "entity_id",
 *     "uuid" = "uid"
 *   }
 * )
 */

class Mention extends ContentEntityBase implements MentionInterface {


  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['entity_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Mention ID'))
      ->setDescription(t('The mention ID.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);


    $fields['uid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('Mention UUID.'))
      ->setReadOnly(TRUE);

    $fields['auid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User ID'))
      ->setDescription(t('The author ID of the mention'))
      ->setTranslatable(TRUE)
      ->setSetting('target_type', 'user')
      ->setDefaultValue(0);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the mention was created.'))
      ->setTranslatable(TRUE);

    $fields['entity_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Entity type'))
      ->setDescription(t('The entity type to which this mention is attached.'))
      ->setSetting('max_length', EntityTypeInterface::ID_MAX_LENGTH);

    return $fields;
  }
}