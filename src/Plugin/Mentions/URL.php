<?php
namespace Drupal\mentions\Plugin\Mentions;

use Drupal\mentions\MentionsPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Mention(
 *  id = "url",
 *  name = @Translation("URL")
 * )
 */
class URL implements MentionsPluginInterface {
  

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    
  }

  public function outputSettingsCallback($mention, $settings) {
      
  }

  public function outputCallback($mention, $settings) {
    
  }

  public function targetCallback($value, $settings) {
    
  }

  public function mentionPresaveCallback($entity) {
    
  }

  public function patternCallback($settings, $regex) {
    
  }

  public function settingsCallback($form, $form_state, $type) {
    
  }

  public function settingsSubmitCallback($form, $form_state, $type) {
    
  }

}
