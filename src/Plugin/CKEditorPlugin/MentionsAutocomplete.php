<?php

/**
 * @file
 * Contains \Drupal\mentions\Plugin\CKEditorPlugin\MentionsAutocomplete.
 */

namespace Drupal\mentions\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\editor\Entity\Editor;
use Drupal\ckeditor\CKEditorPluginContextualInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;

/**
 * Defines the "Mentions Autocomplete" plugin.
 *
 * @CKEditorPlugin(
 *   id = "mentionsautocomplete",
 *   label = @Translation("Mentions Autocomplete"),
 *   module = "mentions"
 * )
 */
class MentionsAutocomplete extends CKEditorPluginBase implements CKEditorPluginContextualInterface, ContainerFactoryPluginInterface {

  protected $config;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactory $config) {
    $this->config = $config->get('mentions.mentions');
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $config = $container->get('config.factory');

    return new static($configuration,
      $plugin_id,
      $plugin_definition,
      $config
    );
  }

  public function getButtons() {
    return array();
  }

  public function getConfig(Editor $editor) {
    return array();
  }

  public function getFile() {
    return drupal_get_path('module', 'mentions') . '/js/plugins/mentionsautocomplete/plugin.js';
  }

  public function isInternal() {
    return FALSE;
  }

  public function isEnabled(Editor $editor) {
    if (!isset($this->config)) {
      return TRUE;
    }

    if ($this->config->get('ckeditor.enabled')) {
      return TRUE;
    }

    else {
      return FALSE;
    }

  }

}
