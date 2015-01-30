<?php

namespace Drupal\mentions\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MentionsDelete implements EventSubscriberInterface {

    static function getSubscribedEvents() {
        $events['mentions.delete'][] = array('onMentionsDelete', 0);
        return $events;
    }

    public function onMentionsDelete($event) {
    // do stuff
    }

}