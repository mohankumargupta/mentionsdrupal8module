<?php

namespace Drupal\mentions\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MentionsUpdate implements EventSubscriberInterface {
  static function getSubscribedEvents() {
    $events['mentions.update'][] = array('onMentionsUpdate', 0);
        return $events;
    }

  public function onMentionsUpdate($event) {
  // do stuff
  }

}