<?php
/**
 * Created by PhpStorm.
 * User: mohan
 * Date: 25/11/2014
 * Time: 12:43 AM
 */

namespace Drupal\mentions\Form;


use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Utility\Token;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MentionsConfigForm extends ConfigFormBase {

  protected $token;
  protected $config;

  /**
   * Class constructor
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
    $form = parent::buildForm($form,$form_state);

    $userid = $this->token->replace('[user:uid]', array('user'=> user_load_by_name('admin')));

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

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    //parent::submitForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validation is optional.
    //parent::validateForm($form,$form_state);
  }

}