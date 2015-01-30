<?php

namespace Drupal\mentions\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MentionsDelete implements EventSubscriberInterface {

    static function getSubscribedEvents() {
        $events['mentions.insert'][] = array('onMentionsInsert', 0);
        return $events;
    }

    public function onMentionsInsert($event) {
    // do stuff
    }

}