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
use Drupal\mentions\Entity\MentionsTypeInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\ContentEntityType;

/**
 * Class MentionsTypeForm.
 *
 * @package Drupal\mentiona\Form
 */
class MentionsTypeForm extends EntityForm implements ContainerInjectionInterface {
  use ConfigFormBaseTrait; 

  private $mentions_manager;
  private $entity_manager;

  public function __construct(MentionsPluginManager $mentions_manager, EntityManagerInterface $entity_manager) {
      $this->mentions_manager = $mentions_manager;
      $this->entity_manager = $entity_manager;
  }    
    
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.mentions'),
      $container->get('entity.manager')      
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

  /*
    public function buildForm(array $form, FormStateInterface $form_state, MentionsTypeInterface $mentions_type){
      
      
    }
*/

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $plugin_names = $this->mentions_manager->getPluginNames();
    $entity = $this->entity;
    $entity_id = isset($entity)?$entity->id():'';
      
    $all_entitytypes = array_keys($this->entity_manager->getEntityTypeLabels());
    
    $candidate_entitytypes = array();
    
    foreach($all_entitytypes as $entity_type) {
      //$entitytype_info = $this->entity_manager->getBundleInfo($entity_type);
      $entitytype_info = $this->entity_manager->getDefinition($entity_type);
      $configentityclassname = ContentEntityType::class;
      $entitytype_type = get_class($entitytype_info);
      if ($entitytype_type == $configentityclassname)
        array_push($candidate_entitytypes, $entitytype_info->getLabel()->getUntranslatedString());
    }
    
    $config = $this->config('mentions.mentions_type.'. $entity_id);
    $name = $config->get('id');
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

    /*
    $form['mentions'] = array(
      '#type' => 'container',
      '#tree' => TRUE
    );
    
     * 
     */
    
     $form['input'] = array(
      '#type' => 'fieldset',
      '#title' => t('Input Settings'),
      '#tree' => TRUE
    );
     
    $form['input']['prefix'] = array(
      '#type' => 'textfield',
      '#title' => t('Prefix'),
      '#default_value' => $config->get('input.prefix'),
      '#size' => 2,
    );
    
    $form['input']['entity_type'] = array(
      '#type' => 'select',
      '#title' => 'Entity Type',
      '#options' => $candidate_entitytypes
    );
    
    $form['input']['inputvalue'] = array(
      '#type' => 'select',
      '#title' => $this->t('Value'),
      '#options' => array()  
    );
    
    $form['input']['suffix'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Suffix'),
      '#default_value' => $config->get('input.suffix'),
      '#size' => 2,
    );
        
    $form['output'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Output Settings'),
      '#tree' => TRUE
    );
    
    $form['output']['outputvalue'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Value'),
        '#description' => $this->t('This field supports tokens.'),
        '#default_value' => $config->get('output.outputvalue')
    );
    
    $form['output']['renderlink'] = array(
        '#type' => 'checkbox',
        '#title' => 'Render as link',
        '#default_value' => $config->get('output.renderlink')
    );
    
    return parent::buildForm($form, $form_state);
  }

    /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = $this->t('Save Mentions Type');
    return $actions;
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
    $form_state->setRedirect('entity.mentions_type.list');
  }
  
}
