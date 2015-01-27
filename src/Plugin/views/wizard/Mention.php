<?php
namespace Drupal\mentions\Plugin\views\wizard;


use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\wizard\WizardPluginBase;

/**
 * @ViewsWizard(
 *   id = "mention",
 *   base_table = "mentions",
 *   title = @Translation("Mentions")
 * )
 */
class Mention extends WizardPluginBase {
  
}