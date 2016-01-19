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
use Drupal\filter\Entity\FilterFormat;
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
  protected $mentionsManager;
  private $tokenService;
  private $mentionType;
  private $entityQueryService;
  private $inputSettings;
  private $outputSettings;
  private $textFormat;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManagerInterface $entity_manager, RendererInterface $render, ConfigFactory $config, MentionsPluginManager $mentions_manager, Token $token, QueryFactory $query_factory) {
    $this->entityManager = $entity_manager;
    $this->mentionsManager = $mentions_manager;
    $this->renderer = $render;
    $this->config = $config;
    $this->tokenService = $token;
    $this->entityQueryService = $query_factory;
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
    $allconfigs = $this->config->listAll('mentions.mentions_type');
    $candidate_entitytypes = array();
    $entitytype_info = $this->entityManager->getDefinition('mentions_type');
    foreach ($allconfigs as $config) {
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

  public function setMentionsManager($mentions_manager) {
    $this->mentionsManager = $mentions_manager;  
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

  
  public function setTextFormat($text_format) {
      $this->textFormat = $text_format;
  }
  
  public function shouldApplyFilter() {
    $flag = false;  
    $settings = $this->settings;
    if ($settings == NULL) {
      $filter_format = FilterFormat::load($this->textFormat);  
      $filters = $filter_format->get('filters');  
      if (isset($filters['filter_mentions']['settings']['mentions_filter'])) {
          $mentions_filter_settings = $filters['filter_mentions']['settings']['mentions_filter'];
          foreach($mentions_filter_settings as $mention_type) {
             //$config =  $this->config->get('mentions.mentions_type.'. $mention_type);
             //if ($config != null) {
             $this->mentionType = $mention_type;
             $flag = true;
             //}
          }
      }
      return $flag;    
    }
    
    $allconfigs = $this->config->listAll('mentions.mentions_type');
    if (isset($settings['mentions_filter'])) {
      foreach ($settings['mentions_filter'] as $mention_type) {
        foreach ($allconfigs as $config) {
          $mentions_name = str_replace('mentions.mentions_type.', '', $config);
          if ($mentions_name == $mention_type) {
            $this->mentionType = $mention_type;
            return TRUE;
          }
        }
      }
    }
    return FALSE;
  }

  public function _filter_mentions($text) {
    $all_mentions = $this->mentions_get_mentions($text);
    if ($all_mentions !== FALSE && $all_mentions != NULL && !empty($all_mentions)) {
      foreach ($all_mentions as $match) {
        $mention = $this->mentionsManager->createInstance($match['type']);
        if ($mention instanceof MentionsPluginInterface) {
          $output = $mention->outputCallback($match, $this->outputSettings);
          $mentionsid = $match['target']['entity_id'];
          $link = $output['link'];
          $shouldrenderlink = $this->outputSettings['renderlink'];
          $rendervalue = $output['value'];
          $mentions = array(
            '#theme' => 'mentions',
            '#mentionsid' => $mentionsid,
            '#link' => $link,
            '#renderlink' => $shouldrenderlink,
            '#rendervalue' => $rendervalue,
          );
          $mentions2 = $this->renderer->render($mentions);
          $text = str_replace($match['source']['string'], $mentions2, $text);
        }
      }
    }
    return $text;

  }

  public function mentions_get_mentions($text) {
    $mentions = array();
    $config_name = $this->mentionType;
    $settings = $this->config->get('mentions.mentions_type.' . $config_name);
    $input_settings = array(
      'prefix' => $settings->get('input.prefix'),
      'suffix' => $settings->get('input.suffix'),
      'entity_type' => $settings->get('input.entity_type'),
      'value' => $settings->get('input.inputvalue'),
    );
    $output_settings = array(
      'value' => $settings->get('output.outputvalue'),
      'renderlink' => $settings->get('output.renderlink') == 1?TRUE:FALSE,
      'rendertextbox' => $settings->get('output.renderlinktextbox'),
    );
    $this->inputSettings = $input_settings;
    $this->outputSettings = $output_settings;
    $mention_type = $settings->get('mention_type');

    $pattern = $this->mentions_get_input_pattern(TRUE, $input_settings);
    $pattern = '/(?:' . preg_quote($input_settings['prefix']) . ')([a-zA-Z0-9_]+)' . preg_quote($input_settings['suffix']) . '/';
    preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

    if (!isset($input_settings['entity_type'])) {
      return NULL;
    }

    foreach ($matches as $match) {
      $mention = $this->mentionsManager->createInstance($mention_type);
      if ($mention instanceof MentionsPluginInterface) {
        $target = $mention->targetCallback($match[1], $input_settings);
        if ($target !== false) {
          $mentions[$match[0]] = array(
            'type' => $mention_type,
            'source' => array(
              'string' => $match[0],
              'match' => $match[1],
            ),
            'target' => $target,
          );
        }
      }
    }

    return $mentions;
  }

  /**
   * Returns the input pattern of a mention type, either as a regex or plain text.
   *
   * @param input_settings
   * @param bool|TRUE    $regex
   *
   * @return bool|string
   */
  public function mentions_get_input_pattern($input_settings,$regex = true) {
    $pattern = '';

    // Append prefix to pattern.
    if (isset($input_settings['suffix'])) {
      $pattern .= $regex ? preg_quote($input_settings['prefix'], '/') : $input_settings['prefix'];
    }

    $pattern .= "\w+";

    // Append suffix to pattern.
    if (isset($input_settings['suffix'])) {
      $pattern .= $regex ? preg_quote($input_settings['suffix'], '/') : $input_settings['suffix'];
    }

    $pattern = $regex ? '/(?:^|\s)(' . $pattern . ')(?:$|\W)/m' : $pattern;

    return $pattern;
  }

}
