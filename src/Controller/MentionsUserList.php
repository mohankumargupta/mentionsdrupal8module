<?php

/**
 * @file
 * Contains \Drupal\mentions\Controller\MentionsUserList.
 */

namespace Drupal\mentions\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryInterface;

/**
 * Returns json for user names that are enabled.
 */
class MentionsUserList extends ControllerBase {

  protected $queryInterface;

  public function __construct(QueryInterface $queryinterface) {
    $this->queryInterface = $queryinterface;
  }

  public static function create(ContainerInterface $container) {
    return new static(
    $container->get('entity.query')->get('user')
    );
  }

  public function userList(Request $request) {
    $usernids = $this->queryInterface
                         ->condition('status', 1)
                         ->sort('name')
                         ->execute();
    $users = entity_load_multiple('user', $usernids);
    $userlist = array();

    foreach ($users as $user) {
      $userlist['userlist'][] = $user->getUsername();
    }

    $response = new JsonResponse($userlist);
    return $response;
  }

}
