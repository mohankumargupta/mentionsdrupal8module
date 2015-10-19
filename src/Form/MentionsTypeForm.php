<?php

/**
 * @file
 * Contains Drupal\mentions\Form\MentionsTypeForm.
 */

namespace Drupal\mentions\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\ConfigFormBaseTrait;
use Drupal\mentions\MentionsPluginManager;

/**
 * Class MentionsTypeForm.
 *
 * @package Drupal\mentiona\Form
 */
class MentionsTypeForm extends EntityForm implements ContainerInjectionInterface {
  use ConfigFormBaseTrait; 

  private $mentions_manager;

  public function __construct(MentionsPluginManager $mentions_manager) {
      $this->mentions_manager = $mentions_manager;
  }    
    
    
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
            $container->get('plugin.manager.mentions')
    );
  }    
    
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'mentions_type.mentions_type'
    ];
  }

  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mentions_type_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $plugin_names = $this->mentions_manager->getPluginNames();
      
    $config = $this->config('mentions_type');
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#description' => $this->t('The human-readable name of this mention type. It is recommended that this name begin with a capital letter and contain only letters, numbers, and spaces. This name must be unique.'),
      '#default_value' => $config->get('name'),
    );
    $form['mention_type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Mention Type'),
      '#description' => $this->t(''),
      '#options' => $plugin_names,
      '#default_value' => $config->get('mention_type'),
    );
    $form['description'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('description'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->configFactory()->getEditable('mentions.mentions_type')
      ->set('name', $form_state->getValue('name'))
      ->set('mention_type', $form_state->getValue('mention_type'))
      ->set('description', $form_state->getValue('description'))
      ->save();
  }

}
