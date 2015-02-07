<?php

namespace Drupal\mentions\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * MentionsUpdate handles event 'mentions.update'.
 */
class MentionsUpdate implements EventSubscriberInterface {
  /**
   * @{inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['mentions.update'][] = array('onMentionsUpdate', 0);
    return $events;
  }

  public function onMentionsUpdate($event) {
  // do stuff
  }

}