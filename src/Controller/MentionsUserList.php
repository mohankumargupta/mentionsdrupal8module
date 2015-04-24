<?php

/**
 * @file
 * Contains \Drupal\mentions\Controller\MentionsUserList.
 */

namespace Drupal\mentions\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns json for user names that are enabled.
 */
class MentionsUserList extends ControllerBase {

public function userList(Request $request) {
	$returndata = array('userlist' => ['admin', 'mohangupta']);
	$response = new JsonResponse($returndata);
	return $response;
}


}
