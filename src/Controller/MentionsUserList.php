<?php

/**
 * @file
 * Contains \Drupal\mentions\Controller\MentionsUserList.
 */

namespace Drupal\mentions\Controller;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns json for user names that are enabled.
 */
class MentionsUserList extends ControllerBase {

  protected $entityQuery;
  protected $config;
  protected $allconfigs;
  
  public function __construct(QueryFactory $queryfactory, ConfigFactory $config ) {
    $this->entityQuery = $queryfactory;
    $this->config = $config;
    $this->allconfigs = $this->config->loadMultiple($this->config->listAll('mentions.mentions_type'));
  }

  public static function create(ContainerInterface $container) {
    $config = $container->get('config.factory');  
    return new static(
      $container->get('entity.query'),
      $config
    );
  }

  public function userList(Request $request) {

    $entitylist = array();
      
    foreach ($this->allconfigs as $config) {  
      $entity_type = $config->get('input.entity_type');
      $query = $this->entityQuery->get($entity_type);
      $usernids = $query
                  ->condition('status', 1)
                  ->sort('name')
                  ->execute();
      $users = entity_load_multiple('user', $usernids);


      foreach ($users as $user) {
        $newuserarray = array(
          'username' => $user->getAccountName(),
          'uid' => $user->id() 
        );  
        if (isset($entitylist['data']) && in_array($newuserarray,$entitylist['data'])) {
            continue;
        }  
          
        $entitylist['data'][] = $newuserarray;
    }
    
    }
    $response = new JsonResponse($entitylist);
    return $response;
  }
  
  public function userPrefixesAndSuffixes(Request $request) {

    $ps = array('data'=>'');
    
      foreach($this->allconfigs as $configname) {     
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

