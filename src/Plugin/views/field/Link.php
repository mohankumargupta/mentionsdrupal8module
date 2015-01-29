<?php
namespace Drupal\mentions\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\ResultRow;
use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\field\FieldPluginBase;

/**
 * @ViewsField("mentions_link")
 */
class Link extends FieldPluginBase {
    public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
        parent::init($view, $display, $options);
    }

    protected function defineOptions() {
        $options = parent::defineOptions();
        return $options;
    }

    public function render(ResultRow $values) {
        $this->options['alter']['make_link'] = TRUE;
        $this->options['alter']['html'] = TRUE;

        $value = $this->getValue($values);
        $entity = entity_load('mentions', $value);
        $entity_type = $entity->get('entity_type')->getValue()[0]['value'];
        $entity_value = $entity->get('entity_id')->getValue()[0]['value'];
        $this->options['alter']['path'] = $entity_type ."/" . $entity_value;
        return 'View mention';
    }
}