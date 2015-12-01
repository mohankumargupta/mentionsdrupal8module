<?php
/**
 * @file
 * Contains Drupal\mentions\Plugin\Filter\FilterMentions.
 */

namespace Drupal\mentions\Plugin\Filter;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Utility\Token;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\mentions\MentionsPluginInterface;
use Drupal\mentions\MentionsPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
  private $token_service;
  private $mention_type;
  private $entity_query_service;
  private $input_settings;
  private $output_settings;
  
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManagerInterface $entity_manager, RendererInterface $render, ConfigFactory $config, MentionsPluginManager $mentions_manager, Token $token, QueryFactory $query_factory) {
    $this->entityManager = $entity_manager;
    $this->mentionsManager = $mentions_manager;
    $this->renderer = $render;
    $this->config = $config;
    $this->token_service = $token;
    $this->entity_query_service = $query_factory;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $entity_manager = $container->get('entity.manager');
    $renderer = $container->get('renderer');
    $config = $container->get('config.factory');
    $mentions_manager = $container->get('plugin.manager.mentions');
    $token = $container->get('token');
    $entity_service = $container->get('entity.query');
    
    return new static($configuration,
      $plugin_id,
      $plugin_definition,
      $entity_manager,
      $renderer,
      $config,
      $mentions_manager,
      $token,
      $entity_service      
    );
  }

  public function settingsForm(array $form, FormStateInterface $form_state) {
      //$plugin_names = $this->mentionsManager->getPluginNames();
      //print_r($this->settings);
      $allconfigs = $this->config->listAll('mentions.mentions_type');
      $candidate_entitytypes = array();
      $entitytype_info = $this->entityManager->getDefinition('mentions_type');
      foreach($allconfigs as $config) {
       $mentions_name = str_replace('mentions.mentions_type.', '', $config);    
       $candidate_entitytypes[$mentions_name] = $mentions_name;  
      }
      
    
    if (count($candidate_entitytypes) == 0) {
      return NULL;
    }
    
    $form['mentions_filter'] = array(
      '#type' => 'checkboxes',
      '#options' => $candidate_entitytypes,  
      '#default_value' => $this->settings['mentions_filter'],  
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
    if ($this->shouldApplyFilter()) {
      return new FilterProcessResult($this->_filter_mentions($text));    
    }
    
    else {
      return new FilterProcessResult($text);  
    }
    
  }

  private function shouldApplyFilter() {
    $settings = $this->settings;  
    $allconfigs = $this->config->listAll('mentions.mentions_type');
    if (isset($settings['mentions_filter'])) {
        foreach($settings['mentions_filter'] as $mention_type) {
          foreach($allconfigs as $config) {
            $mentions_name = str_replace('mentions.mentions_type.', '', $config); 
            if ($mentions_name == $mention_type) {
                $this->mention_type = $mention_type;
                return true;
            } 
          }
        }
    }
    return false;
  }
  
  public function _filter_mentions($text) {
    $all_mentions = $this->mentions_get_mentions($text);
    if ($all_mentions !== FALSE && $all_mentions!=NULL && !empty($all_mentions)) {
      foreach ($all_mentions as $match) {
        //$entity = entity_load($match['target']['entity_type'], $match['target']['entity_id']);
        $mention = $this->mentionsManager->createInstance($match['type']);
        if ($mention instanceof MentionsPluginInterface) {
          $output = $mention->outputCallback($match, $this->output_settings);
          $mentionsid = $match['target']['entity_id'];
          $link = $output['link'];
          $shouldrenderlink = $this->output_settings['renderlink'];
          $rendervalue = $output['value'];
          $mentions = array('#theme' => 'mentions', '#mentionsid' => $mentionsid, '#link'=> $link, '#renderlink'=> $shouldrenderlink, '#rendervalue'=> $rendervalue);
          $mentions2 = $this->renderer->render($mentions);
          $text = str_replace($match['source']['string'], $mentions2, $text);
        }
      }
    }
    return $text;
    
  }

  public function mentions_get_mentions($text) {
    $mentions = array();
    //$entity_storage = $this->entityManager->getStorage('mentions_type');
    //$label = '';
    //foreach ($entity_storage->loadMultiple() as $entity) {
    //  $entity_id = $entity->id();
    //  $label = $entity->label() ?: $entity_id;
    //}
    $config_name = $this->mention_type;
    $settings = $this->config->get('mentions.mentions_type.'.$config_name);
    $input_settings = array(
      'prefix' => $settings->get('input.prefix'),
      'suffix' => $settings->get('input.suffix'),
      'entity_type' => $settings->get('input.entity_type'),
      'value' => $settings->get('input.inputvalue')
    );
    $output_settings = array(
        'value' => $settings->get('output.outputvalue'),
        'renderlink' => $settings->get('output.renderlink')==1?TRUE:FALSE,
        'rendertextbox' => $settings->get('output.renderlinktextbox')
    );
    $this->input_settings = $input_settings;
    $this->output_settings = $output_settings;    
    $mention_type = $settings->get('mention_type');
    
    $pattern = $this->mentions_get_input_pattern(TRUE, $input_settings);
    $pattern = '/(?:'.preg_quote($input_settings['prefix']).')([a-zA-Z0-9_]+)'.preg_quote($input_settings['suffix']).'/';
    preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
   
    if (!isset($input_settings['entity_type'])) {
      return null;
    }
    
    //$mentions_plugin = $this->mentionsManager->createInstance($mention_type);
    
    
    foreach($matches as $match) {      
      $mention = $this->mentionsManager->createInstance($mention_type);
      if ($mention instanceof MentionsPluginInterface) {
        $target = $mention->targetCallback($match[1], $input_settings);
        if ($target !== FALSE) {
          $mentions[$match[0]] = array(
         'type' => $mention_type,
         'source' => array(
           'string' => $match[0],
           'match' => $match[1],
         ),
         'target' => $target    
        );
        }
      }
       
      
      /*
      $user = user_load_by_name($match[1]);
      $replacement = $this->token_service->replace($output_settings['value'], array('user'=>$user));
      if ($output_settings['renderlink']) {
        $rendervalue = $this->token_service->replace($output_settings['rendertextbox'], array('user'=>$user));  
      }
      
      $users[] = array(
          'text' => $match[0],
          'replacement' => $replacement,
          'user' => $user->id(),
          'renderlink' => $output_settings['renderlink'],
          'rendervalue' => isset($rendervalue)?$rendervalue:''
      );  

    $users = isset($users)?$users:array();
    return $users;
       * 
       */
       
  }
  
  return $mentions;
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

