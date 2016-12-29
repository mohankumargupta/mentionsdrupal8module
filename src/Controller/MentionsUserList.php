<?php

/**
 * @file
 * Contains \Drupal\mentions\Controller\MentionsUserList.
 */

namespace Drupal\mentions\Controller;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Query\QueryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns json for user names that are enabled.
 */
class MentionsUserList extends ControllerBase {

  protected $queryInterface;
  protected $config;
  
  public function __construct(QueryInterface $queryinterface, ConfigFactory $config ) {
    $this->queryInterface = $queryinterface;
    $this->config = $config;
  }

  public static function create(ContainerInterface $container) {
    $config = $container->get('config.factory');  
    return new static(
      $container->get('entity.query')->get('user'),
      $config
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
  
  public function userPrefixesAndSuffixes(Request $request) {
    $allconfigs = $this->config->listAll('mentions.mentions_type');
           $ps = array('data'=>'');
    
      foreach($allconfigs as $configname) {     
      $config = $this->config->get($configname);
      
      $ps['data'][] = array(
          'prefix' => $config->get('input')['prefix'],
          'suffix' => $config->get('input')['suffix']
       );
    
      }
	  $response = new JsonResponse($ps);
	  return $response;
  }

}

