<?php

/**
 * @file
 * Contains Drupal\mentions\MentionsConfigListBuilder.
 */

namespace Drupal\mentions;

use Drupal\Core\Config\Entity\DraggableListBuilder;
//use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Mentions Type entities.
 */
class MentionsConfigListBuilder extends DraggableListBuilder {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mentions_config_listbuilder_form';
  }  
  
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
  
  /**
   * {@inheritdoc}
   */
  public function getDefaultOperations(EntityInterface $entity) {
    $operations = parent::getDefaultOperations($entity);

    if ($entity->hasLinkTemplate('delete_form')) {
      $operations['mentions'] = array(
        'title' => t('Delete'),
        'weight' => 20,
        'url' => $entity->urlInfo('delete_form'),
      );
    }
    return $operations;
  }

}
