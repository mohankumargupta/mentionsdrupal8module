<?php
namespace Drupal\mentions\Plugin\Mentions;

use Drupal\mentions\MentionsPluginInterface;

/**
 * @Mention(
 *  id = "url",
 *  name = @Translation("URL")
 * )
 */
class URL implements MentionsPluginInterface {
  public function entityOutput($mention, $settings) {
      
  }
}
