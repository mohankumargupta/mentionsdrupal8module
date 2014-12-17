<?php
/**
 * Created by PhpStorm.
 * User: mohan
 * Date: 9/12/2014
 * Time: 9:31 PM
 */

namespace Drupal\mentions\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 *
 * @ContentEntityType(
 *   id = "mentions",
 *   label = @Translation("Mentions"),
 *   handlers = {
 *     "storage" = "Drupal\mentions\MentionsStorage",
 *     "storage_schema" = "Drupal\mentions\MentionsStorageSchema",
 *     "access" = "Drupal\mentions\UserAccessControlHandler"
 *   },
 *   base_table = "mentions",
 *   data_table = "mentions_field_data",
 *   label_callback = "mentions_format_name",
 *   entity_keys = {
 *     "id" = "uid",
 *     "uuid" = "uuid"
 *   }
 * )
 */
//class Mentions extends ContentEntityBase {

    /**
     * Provides base field definitions for an entity type.
     *
     * Implementations typically use the class
     * \Drupal\Core\Field\BaseFieldDefinition for creating the field definitions;
     * for example a 'name' field could be defined as the following:
     * @code
     * $fields['name'] = BaseFieldDefinition::create('string')
     *   ->setLabel(t('Name'));
     * @endcode
     *
     * By definition, base fields are fields that exist for every bundle. To
     * provide definitions for fields that should only exist on some bundles, use
     * \Drupal\Core\Entity\FieldableEntityInterface::bundleFieldDefinitions().
     *
     * The definitions returned by this function can be overridden for all
     * bundles by hook_entity_base_field_info_alter() or overridden on a
     * per-bundle basis via 'base_field_override' configuration entities.
     *
     * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
     *   The entity type definition. Useful when a single class is used for multiple,
     *   possibly dynamic entity types.
     *
     * @return \Drupal\Core\Field\FieldDefinitionInterface[]
     *   An array of base field definitions for the entity type, keyed by field
     *   name.
     *
     * @see \Drupal\Core\Entity\EntityManagerInterface::getFieldDefinitions()
     * @see \Drupal\Core\Entity\FieldableEntityInterface::bundleFieldDefinitions()
     */
    /*
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        
        // TODO: Implement baseFieldDefinitions() method.
    }
    */
//}