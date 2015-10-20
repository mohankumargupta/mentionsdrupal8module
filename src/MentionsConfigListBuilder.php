<?php

/**
 * @file
 * Contains Drupal\mentions\MentionsConfigListBuilder.
 */

namespace Drupal\mentions;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Mentions Type entities.
 */
class MentionsConfigListBuilder extends ConfigEntityListBuilder {
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Machine name');
    //$header['label'] = $this->t('Mentions Type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['id'] = $entity->id();
    //$row['label'] = $entity->mention_type();
    // You probably want a few more properties here...
    return $row + parent::buildRow($entity);
  }

}
