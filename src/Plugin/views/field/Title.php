<?php

namespace Drupal\mentions\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\ResultRow;
use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\field\FieldPluginBase;

/**
 * @ViewsField("mentions_title")
 */
class Title extends FieldPluginBase {
    public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
        parent::init($view, $display, $options);

        //$this->additional_fields['title'] = 'title';
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
        $entity = entity_load($entity_type, $entity_value);
        $entity_title = $entity->get('title')->getValue()[0]['value'];
        return $entity_title;
    }
}