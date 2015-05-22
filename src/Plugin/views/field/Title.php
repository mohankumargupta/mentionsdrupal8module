<?php

/**
 * @file
 */

namespace Drupal\mentions\Plugin\views\field;

use Drupal\views\ResultRow;
use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\field\FieldPluginBase;

/**
 * Title field available in views.
 *
 * @ViewsField("mentions_title")
 */
class Title extends FieldPluginBase {
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
  }

  protected function defineOptions() {
    $options = parent::defineOptions();
    return $options;
  }

  public function render(ResultRow $values) {
    $value = $this->getValue($values);
    $entity = entity_load('mentions', $value);
    $entity_type = $entity->get('entity_type')->getValue()[0]['value'];
		$entity_value = $entity->get('entity_id')->getValue()[0]['value'];
		if ($entity_type == 'taxonomy') {
			$entity_type = 'taxonomy_term';
		}
		$entity = entity_load($entity_type, $entity_value);

    if ($entity_type == 'node') {
      $entity_title_field = 'title';
    }

    if ($entity_type == 'comment') {
      $entity_title_field = 'subject';
    }
		
		if ($entity_type == 'taxonomy_term') {
			$entity_title_field = 'name';
		}

    $entity_title = $entity->get($entity_title_field)->getValue()[0]['value'];
    return $entity_title;
  }

}
