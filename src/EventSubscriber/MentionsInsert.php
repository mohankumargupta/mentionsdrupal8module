<?php

namespace Drupal\mentions\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MentionsInsert implements EventSubscriberInterface {

static function getSubscribedEvents() {
$events['mentions.insert'][] = array('onMentionsInsert', 0);
return $events;
}

public function onMentionsInsert($event) {

// do stuff

//$actionManager = \Drupal::service('plugin.manager.action');
//$action = $actionManager->createInstance('user_unblock_user_action');
//$action->execute(false);
}

}