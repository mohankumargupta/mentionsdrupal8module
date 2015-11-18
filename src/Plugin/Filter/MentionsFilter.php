<?php
/**
 * @file
 * Contains Drupal\mentions\Plugin\Filter\FilterMentions.
 */

namespace Drupal\mentions\Plugin\Filter;

use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\mentions\MentionsPluginManager;
/**
 * Class FilterMentions.
 *
 * @package Drupal\mentions\Plugin\Filter
 *
 * @Filter(
 * id = "filter_mentions",
 * title = @Translation("Mentions Filter"),
 * description = @Translation("Configure via the <a href='/admin/structure/mentions'>Mention types</a> page."),
 * type = Drupal\filter\Plugin\FilterInterface::TYPE_HTML_RESTRICTOR,
 * settings = {
 *   "mentions_filter" = {}
 * },
 * weight = -10
 * )
 */
class MentionsFilter extends FilterBase implements ContainerFactoryPluginInterface {
  protected $entityManager;
  protected $renderer;
  protected $config;
  private $currentPath;
  protected $mentionsManager;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManagerInterface $entity_manager, RendererInterface $render, ConfigFactory $config, MentionsPluginManager $mentions_manager) {
    $this->entityManager = $entity_manager;
    $this->mentionsManager = $mentions_manager;
    $this->renderer = $render;
    $this->config = $config;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $entity_manager = $container->get('entity.manager');
    $renderer = $container->get('renderer');
    $config = $container->get('config.factory');
    $mentions_manager = $container->get('plugin.manager.mentions');
    
    return new static($configuration,
      $plugin_id,
      $plugin_definition,
      $entity_manager,
      $renderer,
      $config,
      $mentions_manager
    );
  }

  public function settingsForm(array $form, FormStateInterface $form_state) {
        //$plugin_names = $this->mentionsManager->getPluginNames();
      $allconfigs = $this->config->listAll('mentions.mentions_type');
      $candidate_entitytypes = array();
      $entitytype_info = $this->entityManager->getDefinition('mentions_type');
      foreach($allconfigs as $config) {
       array_push($candidate_entitytypes,str_replace('mentions.mentions_type.', '', $config));  
      }
      
    
    if (count($candidate_entitytypes) == 0) {
      return NULL;
    }
    
    $form['mentions_filter'] = array(
      '#type' => 'checkboxes',
      '#options' => $candidate_entitytypes,  
      '#title' => 'Mentions types',
    );
    return $form;
  }
  
  public function setEntityManager($entity_manager) {
    $this->entityManager = $entity_manager;
  }

  public function setRenderer($renderer) {
    $this->renderer = $renderer;
  }

  public function setConfig($config) {
    $this->config = $config;
  }

  public function process($text, $langcode) {  
    return new FilterProcessResult($this->_filter_mentions($text));
  }

  public function _filter_mentions($text) {
    $all_mentions = $this->mentions_get_mentions($text);
    foreach ($all_mentions as $match) {
      $mentions = array('#theme' => 'mentions', '#userid' => $match['user'], '#link'=> $match['text']);
      $mentions2 = $this->renderer->render($mentions);

      $text = str_replace($match['text'], $mentions2, $text);
    }

    return $text;

  }

  public function mentions_get_mentions($text) {
    $mentions = array();
    $entity_storage = $this->entityManager->getStorage('mentions_type');
    $label = '';
    foreach ($entity_storage->loadMultiple() as $entity) {
      $entity_id = $entity->id();
      $label = $entity->label() ?: $entity_id;
    }
    $settings = $this->config->get('mentions.mentions_type.'.$label);
    $input_settings = array(
      'prefix' => $settings->get('input.prefix'),
      'suffix' => $settings->get('input.suffix'),
      'entity_type' => $settings->get('input.entity_type'),
      'value' => $settings->get('input.inputvalue')
    );
    $pattern = $this->mentions_get_input_pattern(TRUE, $input_settings);
    //print($pattern);
    //print("\n");
    //print($text);
    //    print("\n");
    $pattern = '/(?:@)(admin)/';
    preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
   
    foreach($matches as $match) {
      $matching_text = $match[0];
      $username = $match[1];
      //$user = user_load_by_name($username);
      //print_r($user);
    }
    
    $users[] = array(
      'text' => "@admin",
      'user' => "5"
    );
return $users;
    //print_r($settings);
    //$users = array();
    //$input_pattern = '/(\b|\#)(\w*)/';
    //if (preg_match_all($input_pattern, $text, $matches, PREG_SET_ORDER) && isset($settings->mention_type)) {
    //  
    //}
  /*  
  $input_pattern = mentions_get_input_pattern($mention_type);
  if (preg_match_all($input_pattern, $text, $matches, PREG_SET_ORDER) && isset($mention_type->plugin)) {
    $plugin = mentions_get_plugin($mention_type->plugin);
    if (isset($plugin['callbacks']) && isset($plugin['callbacks']['target']) && function_exists($plugin['callbacks']['target'])) {
      foreach ($matches as $match) {
        if (($target = $plugin['callbacks']['target']($match[2], $mention_type)) !== FALSE) {
          $mentions[$match[0]] = array(
            'type'   => $mention_type,
            'source' => array(
              'string' => $match[0],
              'match'  => $match[1],
            ),
            'target' => $target,
          );
        }
      }
    }
  }

  krsort($mentions);

  return $mentions;    
   */ 
    /*
    $entity_storage = $this->entityManager->getStorage('mentions_type');
    $label = '';
    foreach ($entity_storage->loadMultiple() as $entity) {
      $entity_id = $entity->id();
      $label = $entity->label() ?: $entity_id;
    }
    $settings = $this->config->get('mentions.mentions_type.'.$label);
    $users = array();

    // Build regular expression pattern.
    $pattern = '/(\b|\#)(\w*)/';
    $input_settings = $settings->get('input');

    switch (TRUE) {
      case !empty($input_settings['prefix']) && !empty($input_settings['suffix']):
        $pattern = '/\B(' . preg_quote($input_settings['prefix']) . '|' . preg_quote($this->t($input_settings['prefix'])) . ')(\#?.*?)(' . preg_quote($input_settings['suffix']) . '|' . preg_quote($this->t($input_settings['suffix'])) . ')/';
        break;

      case !empty($$input_settings['prefix']) && empty($$input_settings['suffix']):
        $pattern = '/\B(' . preg_quote($$input_settings['prefix']) . '|' . preg_quote($this->t($$input_settings['prefix'])) . ')(\#?\w*)/';
        break;

      case empty($$input_settings['prefix']) && !empty($$input_settings['suffix']):
        $pattern = '/(\b|\#)(\w*)(' . preg_quote($$input_settings['suffix']) . '|' . preg_quote($this->t($$input_settings['suffix'])) . ')/';
        break;
    }

    $userStorage = $this->entityManager->getStorage('user');

    // Find all matching strings.
    if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
      foreach ($matches as $match) {
        if (Unicode::substr($match[2], 0, 1) == '#') {
          // $user = user_load(drupal_substr($match[2], 1));
          // $user = \Drupal::entityManager()->getStorage('user')->load(Unicode::substr($match[2], 1));
          $user = $userStorage->loadByProperties(array('uid' => Unicode::substr($match[2], 1)));
          $user = reset($user);
        }
        elseif ($match[1] == '#') {
          // $user = user_load($match[2]);
          $user = $userStorage->loadByProperties(array('uid' => $match[2]));
          $user = reset($user);
        }
        else {
          // $user = user_load_by_name($match[2]);
          $user = $userStorage->loadByProperties(array('name' => $match[2]));
          $user = reset($user);
        }

        if (!empty($user)) {
          $users[] = array(
            'text' => $match[0],
            'user' => $user,
          );
        }
      }
    }

    return $users;
     * 
     */
  }

/**
 * Returns the input pattern of a mention type, either as a regex or plain text.
 *
 * @param           $mention_type
 * @param bool|TRUE $regex
 *
 * @return bool|string
 */
 public function mentions_get_input_pattern($regex = TRUE, $input_settings) {
  $pattern = '';

  // Append prefix to pattern.
  if (isset($input_settings['suffix'])) {
    $pattern .= $regex ? preg_quote($input_settings['prefix'], '/') : $input_settings['prefix'];
  }

  // Append value to pattern.
  /*
  if (!isset($mention_type->plugin) || ($plugin = mentions_get_plugin($mention_type->plugin)) == FALSE) {
    return FALSE;
  }
  if (isset($plugin['callbacks']['pattern']) && function_exists($plugin['callbacks']['pattern'])) {
    $input = $plugin['callbacks']['pattern']($mention_type->input, $regex);
    $pattern .= $regex ? "({$input})" : drupal_strtoupper($input);
  }
*/
  $pattern .= "\w+";
  
  // Append suffix to pattern.
  if (isset($input_settings['suffix'])) {
    $pattern .= $regex ? preg_quote($input_settings['suffix'], '/') : $input_settings['suffix'];
  }

  $pattern = $regex ? '/(?:^|\s)(' . $pattern . ')(?:$|\W)/m' : $pattern;

  return $pattern;
}
}

