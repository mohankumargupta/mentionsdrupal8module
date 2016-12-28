<?php

/**
 * @file
 * Contains \Drupal\mentions\Controller\MentionsUserTab.
 */

namespace Drupal\mentions\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\UserInterface;

/**
 * Returns view named MentionsDefault 
 */
class MentionsUserTab extends ControllerBase {
  public function mentionsView(UserInterface $user) {
    $uid = $user->id();
    return views_embed_view('mentionsdefault', 'page_1', $uid);    
  }
}
