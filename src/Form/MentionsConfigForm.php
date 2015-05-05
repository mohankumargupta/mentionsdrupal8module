<?php
/**
 * @file
 * Created by PhpStorm.
 * User: mohan
 * Date: 25/11/2014
 * Time: 12:43 AM.
 */

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
  public function __construct(Token $token, ConfigFactory $config) {
    $this->token = $token;
    $this->config = $config->get('mentions.mentions');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
       // Load the service required to construct this class.
       $container->get('token'),
       $container->get('config.factory')
    );
  }

  /**
   * @{inheritdoc}
   */
  protected function getEditableConfigNames() {

    return [
            'mentions.mentions',
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

    $form = parent::buildForm($form, $form_state);

    $userid = $this->token->replace('[user:uid]', array('user' => user_load_by_name('admin')));

    $form['mentions'] = array(
      '#type' => 'container',
      '#tree' => TRUE,
    );

    $form['mentions']['input'] = array(
      '#type' => 'fieldset',
      '#title' => t('Input'),
    );

    $form['mentions']['input']['prefix'] = array(
      '#type' => 'textfield',
      '#title' => t('Prefix'),
      '#default_value' => $this->config->get('input.prefix'),
      '#size' => 2,
    );

    $form['mentions']['input']['suffix'] = array(
      '#type' => 'textfield',
      '#title' => t('Suffix'),
      '#default_value' => $this->config->get('input.suffix'),
      '#size' => 2,
    );

    $form['mentions']['output'] = array(
      '#type' => 'fieldset',
      '#title' => t('Output'),
    );

    $form['mentions']['output']['prefix'] = array(
      '#type' => 'textfield',
      '#title' => t('Prefix'),
      '#default_value' => $this->config->get('output.prefix'),
      '#size' => 2,
    );

    $form['mentions']['output']['suffix'] = array(
      '#type' => 'textfield',
      '#title' => t('Suffix'),
      '#default_value' => $this->config->get('output.suffix'),
      '#size' => 2,
    );

    $form['mentions']['output']['text'] = array(
      '#type' => 'textfield',
      '#title' => t('Text'),
      '#default_value' => '[user:name]',
      '#description' => t('The text for the replacement link. Can use tokens.'),
      '#size' => 20,
    );

    $form['mentions']['output']['link'] = array(
      '#type' => 'textfield',
      '#title' => t('Link'),
      '#default_value' => 'user/[user:uid]',
      '#description' => t('The destination for the replacement link. Can use tokens.'),
      '#size' => 20,
    );

    $form['mentions']['mentions_events'] = array(
      '#type' => 'details',
      '#title' => t('Mentions Events'),
      '#open' => TRUE,
    );

    $isactionmoduleenabled = \Drupal::moduleHandler()->moduleExists('action');
    if ($isactionmoduleenabled) {
      $entity_manager = \Drupal::service('entity.manager');
      $action_definitions = $entity_manager->getListBuilder('action')->load();
      $actions = array();
      foreach ($action_definitions as $action_definition) {
        if ($action_definition->getType() == 'system') {
          $actions[$action_definition->id()] = $action_definition->label();
        }
      }

      $config_mentions_events = $this->config->get('mentions_events');

      $form['mentions']['mentions_events']['insert'] = array(
        '#type' => 'select',
        '#title' => $this->t('When a mention is inserted'),
        '#empty_value' => '',
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
      '#title' => t('CKEditor Integration'),
      '#open' => TRUE,
    );

    $form['mentions']['ckeditor']['enabled'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $this->config->get('ckeditor.enabled'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    parent::submitForm($form, $form_state);
    $updated_config = $this->configFactory()->getEditable('mentions.mentions')->merge($form_state->getValue('mentions'));
    $updated_config->save();
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Validation is optional.
    // parent::validateForm($form,$form_state);
  }

}
