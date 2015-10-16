<?php

/** 
 * @file
 * Contains Drupal\mentions\Entity\MentionsType
 * 
 * Mentions type class used in the admin UI to specify mentions types.
 * 
 */
namespace Drupal\mentions\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\mentions\Entity\MentionsTypeInterface;

/**
 * Defines the Mentions Type entity.
 *
 * @ConfigEntityType(
 *   id = "mentions_type",
 *   label = @Translation("Mentions Type"),
 *   handlers = {
 *     "list_builder" = "Drupal\mentions\MentionsConfigListBuilder",
 *     "form" = {
 *       "add" = "Drupal\mentions\Form\MentionsTypeForm"
 *     }
 *   },
 *   config_prefix = "mentions_type",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/mentions/{mentions_type}",
 *     "collection" = "/admin/structure/visibility_group"
 *   }
 * )
 */
class MentionsType extends ConfigEntityBase implements MentionsTypeInterface {
  /**
   * The Mentions Type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Mentions Type label.
   *
   * @var string
   */
  protected $label;

}


