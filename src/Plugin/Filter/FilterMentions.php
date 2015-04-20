<?php
/**
 * @file
 * Contains Drupal\mentions\Plugin\Filter\FilterMentions.
 */

namespace Drupal\mentions\Plugin\Filter;

use Drupal\Component\Utility\Unicode;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Config\ConfigFactory;
/**
 * Class FilterMentions.
 *
 * @package Drupal\mentions\Plugin\Filter
 *
 * @Filter(
 * id = "filter_mentions",
 * title = @Translation("Mentions Filter"),
 * type = Drupal\filter\Plugin\FilterInterface::TYPE_HTML_RESTRICTOR,
 * weight = -10
 * )
 */
class FilterMentions extends FilterBase implements ContainerFactoryPluginInterface {
  protected $entityManager;
  protected $renderer;
  protected $config;
		
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManagerInterface $entity_manager, RendererInterface $render, ConfigFactory $config) {
    $this->entityManager = $entity_manager;
    $this->renderer = $render;
    $this->config = $config;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $entity_manager = $container->get('entity.manager');
    $renderer = $container->get('renderer');
    $config = $container->get('config.factory');

    return new static($configuration,
      $plugin_id,
      $plugin_definition,
      $entity_manager,
      $renderer,
      $config
    );
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
    return new FilterProcessResult($this->_filter_mentions($text, $this));
  }

	public function _filter_mentions($text, $filter) {
    foreach ($this->mentions_get_mentions($text) as $match) {
      $mentions = array('#theme' => 'mentions', '#user' => $match['user']);
      $mentions2 = $this->renderer->render($mentions);
      $text = str_replace($match['text'], $mentions2, $text);
    }

    return $text;

  }

  public function mentions_get_mentions($text) {
    $settings = $this->config->get('mentions.mentions');
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
                    //$user = user_load(drupal_substr($match[2], 1));
                    //$user = \Drupal::entityManager()->getStorage('user')->load(Unicode::substr($match[2], 1));
                    $user = $userStorage->loadByProperties(array('uid'=>Unicode::substr($match[2], 1)));
                    $user = reset($user);
                }
                elseif ($match[1] == '#') {
                    //$user = user_load($match[2]);
                    $user = $userStorage->loadByProperties(array('uid'=>$match[2]));
                    $user = reset($user);
                }
                else {
                    //$user = user_load_by_name($match[2]);
                    $user = $userStorage->loadByProperties(array('name'=>$match[2]));
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
    }
}