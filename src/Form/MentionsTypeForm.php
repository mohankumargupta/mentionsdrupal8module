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
      'mentions.mentions_type'
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
    $entity = $this->entity;
    //$entity_id = $entity->id();
      
    $config = $this->config('mentions.mentions_type');
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
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
      '#description' => $this->t('Describe this mention type.'),
      '#default_value' => $config->get('description'),
    );

    $form['mentions'] = array(
      '#type' => 'container',
      '#tree' => TRUE
    );
    
     $form['mentions']['input'] = array(
      '#type' => 'fieldset',
      '#title' => t('Input Settings')
    );
     
    $form['mentions']['input']['prefix'] = array(
      '#type' => 'textfield',
      '#title' => t('Prefix'),
      '#default_value' => $config->get('mentions.mentions_type'),
      '#size' => 2,
    );
    
    $form['mentions']['input']['entity_type'] = array(
      '#type' => 'select',
      '#title' => 'Entity Type',
      '#options' => array()  
    );
    
    $form['mentions']['input']['value'] = array(
      '#type' => 'select',
      '#title' => $this->t('Value'),
      '#options' => array()  
    );
    
    $form['mentions']['input']['suffix'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Suffix'),
      '#default_value' => $config->get('mentions.mentions_type'),
      '#size' => 2,
    );
        
    $form['mentions']['output'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Output Settings')
    );
    
    $form['mentions']['output']['value'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Value'),
        '#description' => $this->t('This field supports tokens.')
    );
    
    $form['mentions']['output']['link'] = array(
        '#type' => 'checkbox',
        '#title' => 'Render as link'
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
    $entity = $this->entity;
    $entity->save();
    $form_state->setRedirect('entity.mentions_type.list');
      /*
    parent::submitForm($form, $form_state);

    $this->configFactory()->getEditable('mentions.mentions_type')
      ->set('name', $form_state->getValue('name'))
      ->set('mention_type', $form_state->getValue('mention_type'))
      ->set('description', $form_state->getValue('description'))
      ->save();
       */
       
  }

}
