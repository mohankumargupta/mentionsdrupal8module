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

$config = \Drupal::config('mentions.mentions');
$config_mentions_events = $config->get('mentions_events');
$action_id = $config_mentions_events['insert'];
$actionManager = \Drupal::service('plugin.manager.action');
$action = $actionManager->createInstance($action_id);
$action->execute(false);
}

}