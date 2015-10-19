<?php

namespace Drupal\mentions;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;


class MentionsPluginManager extends DefaultPluginManager
{
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/Mentions', $namespaces, $module_handler, 'Drupal\mentions\MentionsPluginInterface', 'Drupal\mentions\Annotation\Mention');
    $this->alterInfo('mentions_plugin_info');
    $this->setCacheBackend($cache_backend, 'mentions_plugins');
  }   
  
  public function getPluginNames() {
    $definitions = $this->getDefinitions();
    $plugin_names = array();
    
    foreach($definitions as $definition) {
      array_push($plugin_names, $definition['name']->getUntranslatedString());
    }
    
    return $plugin_names;
  }
  
  
  
  
}

