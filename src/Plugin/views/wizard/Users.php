<?php

namespace Drupal\mentions\Plugin\views;

use Drupal\views\Plugin\views\wizard\WizardPluginBase;

/**
 *
 * @ViewsWizard(
 *   id = "mentions",
 *   base_table = "mention",
 *   title = @Translation("Mentions")
 * )
 */
class Mentions extends WizardPluginBase {

    protected function defaultDisplayOptions() {
        $display_options = parent::defaultDisplayOptions();
        return $display_options;
    }

}