<?php

namespace Drupal\mentions\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * MentionsInsert handles event 'mentions.insert'.
 */
class MentionsInsert implements EventSubscriberInterface {

  /**
   * @{inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['mentions.insert'][] = array('onMentionsInsert', 0);
    return $events;
}

  /**
   * Event Handler.
   */
  public function onMentionsInsert($event) {
    $config = \Drupal::config('mentions.mentions');
    $config_mentions_events = $config->get('mentions');
    $action_id = $config_mentions_events['id'];
    if (!empty($action_id)) {
      $action_manager = \Drupal::service('plugin.manager.action');
      $action = $action_manager->createInstance($action_id);
      $action->execute(false);
    }
}

}