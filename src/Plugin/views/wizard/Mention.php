<?php
namespace Drupal\mentions\Plugin\views\wizard;


use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\wizard\WizardPluginBase;

/**
 * @ViewsWizard(
 *   id = "mention",
 *   base_table = "mentions_field_data",
 *   title = @Translation("Mentions")
 * )
 */
class Mention extends WizardPluginBase {
    /**
   * Overrides Drupal\views\Plugin\views\wizard\WizardPluginBase::defaultDisplayOptions().
   */
  protected function defaultDisplayOptions() {
    $display_options = parent::defaultDisplayOptions();
	  unset($display_options['fields']);
		/* Field: User: Name */
    $display_options['fields']['name']['id'] = 'mentionsid';
    $display_options['fields']['name']['table'] = 'mentions_field_data';
    $display_options['fields']['name']['field'] = 'mid';
    $display_options['fields']['name']['entity_type'] = 'mentions';
    $display_options['fields']['name']['entity_field'] = 'mid';
    $display_options['fields']['name']['label'] = '';
    $display_options['fields']['name']['alter']['alter_text'] = 0;
    $display_options['fields']['name']['alter']['make_link'] = 0;
    $display_options['fields']['name']['alter']['absolute'] = 0;
    $display_options['fields']['name']['alter']['trim'] = 0;
    $display_options['fields']['name']['alter']['word_boundary'] = 0;
    $display_options['fields']['name']['alter']['ellipsis'] = 0;
    $display_options['fields']['name']['alter']['strip_tags'] = 0;
    $display_options['fields']['name']['alter']['html'] = 0;
    $display_options['fields']['name']['hide_empty'] = 0;
    $display_options['fields']['name']['empty_zero'] = 0;
    $display_options['fields']['name']['link_to_user'] = 1;
    $display_options['fields']['name']['overwrite_anonymous'] = 0;
    $display_options['fields']['name']['plugin_id'] = 'mid';
		return $display_options;
	}
}