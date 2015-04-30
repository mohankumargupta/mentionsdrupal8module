<?php
namespace Drupal\mentions\Entity;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
/**
 *
 * @file
 * Mentions Entity.
 *
 * @ContentEntityType(
 *   id = "mentions",
 *   label = @Translation("Mentions"),
 *   handlers = {
 *     "views_data" = "Drupal\mentions\MentionsViewsData"
 *   },
 *   base_table = "mentions",
 *   translatable = TRUE,
 *   data_table = "mentions_field_data",
 *   entity_keys = {
 *     "id" = "mid",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   }
 * )
 */
class Mentions extends ContentEntityBase {
  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['mid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Mention ID'))
      ->setDescription(t('The primary identifier for a mention.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['entity_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Entity ID'))
      ->setDescription(t('Entity ID'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);


		    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('internal uuid'))
      ->setDescription(t('internal uuid'))
      ->setReadOnly(TRUE);
		
    $fields['uid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('UUID'))
      ->setDescription(t('Mention UUID.'))
      ->setReadOnly(TRUE);

    $fields['auid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User ID'))
      ->setDescription(t('The author ID of the mention'))
      ->setSetting('target_type', 'user')
      ->setDefaultValue(0);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the mention was created.'));

    $fields['entity_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Entity type'))
      ->setDescription(t('The entity type to which this mention is attached.'))
      ->setSetting('max_length', 32);
		
		    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The user language code.'))
      ->setTranslatable(TRUE);

    return $fields;
  }
}