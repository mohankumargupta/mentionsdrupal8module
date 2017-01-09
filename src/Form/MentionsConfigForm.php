<?php

namespace Drupal\mentions\Form;


use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Utility\Token;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MentionsConfigForm.
 */
class MentionsConfigForm extends ConfigFormBase {
  protected $token;
  protected $config;

  /**
   * Constructor.
   */
  public function __construct(Token $token) {
    $this->token = $token;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
       // Load the service required to construct this class.
       $container->get('token')
    );
  }

  /**
   * @{inheritdoc}
   */
  protected function getEditableConfigNames() {

    return [
            'mentions.settings',
        ];
  }


  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {

    return "mentionsconfigform";
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

     $config = $this->config('example.settings');
    

    //$userid = $this->token->replace('[user:uid]', array('user' => user_load_by_name('admin')));

    $form['mentions'] = array(
      '#type' => 'container',
      '#tree' => TRUE,
    );

    $form['mentions']['info'] = array(
      '#type' => 'details',
      '#title' => "Configure Mentions",
      '#open' => TRUE  
    );
    
    $form['mentions']['info']['configure'] = array(
      '#type' => 'markup',
      '#markup' => "Hail Mary!"  
    );    
    
    $form['mentions']['mentions_events'] = array(
      '#type' => 'details',
      '#title' => t('Mentions Actions'),
      '#open' => TRUE,
    );    
    
    
    
    $isactionmoduleenabled = \Drupal::moduleHandler()->moduleExists('action');
    if ($isactionmoduleenabled) {
      $entity_manager = \Drupal::service('entity.manager');
      $action_definitions = $entity_manager->getListBuilder('action')->load();
      $actions = array();
      foreach ($action_definitions as $action_definition) {
        if ($action_definition->getType() == 'system') {
          $actions[$action_definition->uuid()] = $action_definition->label();
        }
      }

      $config_mentions_events = $this->config->get('mentions_events');

      $form['mentions']['mentions_events']['insert'] = array(
        '#type' => 'select',
        '#title' => $this->t('When a mention is inserted'),
        '#default_value' => $config_mentions_events['insert'],
        '#options' => $actions,
        '#description' => $this->t('When a mention is inserted, the following action is executed.'),
      );

      $form['mentions']['mentions_events']['update'] = array(
        '#type' => 'select',
        '#title' => $this->t('When a mention is updated'),
        '#empty_value' => '',
        '#default_value' => $config_mentions_events['update'],
        '#options' => $actions,
        '#description' => $this->t('When a mention is updated, the following action is executed.'),
      );

      $form['mentions']['mentions_events']['delete'] = array(
        '#type' => 'select',
        '#title' => $this->t('When a mention is deleted'),
        '#empty_value' => '',
        '#default_value' => $config_mentions_events['delete'],
        '#options' => $actions,
        '#description' => $this->t('When a mention is deleted, the following action is executed.'),
      );
    }

    else {
      $form['mentions']['mentions_events']['action_module_not_enabled'] = array(
        '#type' => 'label',
        '#title' => t('When the actions module is enabled, actions can be performed when mentions are inserted, updated or deleted.'),
      );
    }

    $form['mentions']['ckeditor'] = array(
      '#type' => 'details',
      '#title' => t('CKEditor'),
      '#open' => TRUE,
    );

    $form['mentions']['ckeditor']['autocomplete'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Autocomplete'),
      '#default_value' => $this->config->get('ckeditor.autocomplete'),
    );  
    
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    parent::submitForm($form, $form_state);
    //$updated_config = $this->configFactory()->getEditable('mentions.mentions')->merge($form_state->getValue('mentions'));
    //$updated_config->save();
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Validation is optional.
    // parent::validateForm($form,$form_state);
  }

}
