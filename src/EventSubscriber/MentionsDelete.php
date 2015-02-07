<?php

namespace Drupal\mentions\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * MentionsDelete handles event 'mentions.delete'.
 */
class MentionsDelete implements EventSubscriberInterface {
  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['mentions.delete'][] = array('onMentionsDelete', 0);
    return $events;
  }

  /**
   * Event handler.
   */
  public function onMentionsDelete($event) {
    // Do stuff.
  }

}