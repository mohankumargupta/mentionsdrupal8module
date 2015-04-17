<?php
/**
 * @file
 * Contains Drupal\mentions\Plugin\Filter\FilterMentions
 */

namespace Drupal\mentions\Plugin\Filter;

use Drupal\Component\Utility\Unicode;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityManagerInterface;

/**
 * Class FilterMentions
 * @package Drupal\mentions\Plugin\Filter
 *
 * @Filter(
 * id = "filter_mentions",
 * title = @Translation("Mentions Filter"),
 * type = Drupal\filter\Plugin\FilterInterface::TYPE_HTML_RESTRICTOR,
 * weight = -10
 * )
 */
class FilterMentions extends FilterBase implements ContainerFactoryPluginInterface{
  protected $entityManager;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
    parent::__construct($configuration, $plugin_id, $plugin_definition);  
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $entity_manager = $container->get('entity.manager');
    
    return new static($configuration,
      $plugin_id,
      $plugin_definition,            
      $entity_manager
    );
  }

  public function setEntityManager($entity_manager) {
    
  }

    /**
     * Performs the filter processing.
     *
     * @param string $text
     *   The text string to be filtered.
     * @param string $langcode
     *   The language code of the text to be filtered.
     *
     * @return \Drupal\filter\FilterProcessResult
     *   The filtered text, wrapped in a FilterProcessResult object, and possibly
     *   with associated assets, cache tags and #post_render_cache callbacks.
     *
     * @see \Drupal\filter\FilterProcessResult
     */
    public function process($text, $langcode)
    {
        return new FilterProcessResult($this->_filter_mentions($text, $this));
    }

    public function _filter_mentions($text, $filter) {
        foreach ($this->mentions_get_mentions($text) as $match) {
            //$text = str_replace($match['text'], theme('mentions', array('user' => $match['user'])), $text);
            $mentions = array('#theme'=>'mentions', '#user' => $match['user']);
            $mentions2 = drupal_render($mentions);
            $text = str_replace($match['text'], $mentions2, $text);
        }
        return $text;
    }

    public function mentions_get_mentions($text) {
        //$settings = variable_get('mentions', mentions_defaults());
        $settings = \Drupal::config('mentions.mentions');
        $users = array();

        // Build regular expression pattern.
        $pattern = '/(\b|\#)(\w*)/';
        $input_settings = $settings->get('input');
        $output_settings = $settings->get('output');

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




/**
 * Return a '@username' link to user's profile.
 */
    /*
    function theme_mentions($variables) {
        $settings = variable_get('mentions', mentions_defaults());
        $user = $variables['user'];

        foreach (array('text', 'link') as $type) {
            if (!empty($settings['output'][$type])) {
                $$type = token_replace($settings['output'][$type], array('user' => $user));
            }
        }

        // Allow other modules to modify the link text and destination.
        drupal_alter('mentions_link', $text, $link, $user);

        return l($settings['output']['prefix'] . $text . $settings['output']['suffix'], $link, array(
            'attributes' => array(
                'class' => 'mentions mentions-' . $user->uid,
                'title' => $text,
            ),
        ));
    }

     */



}