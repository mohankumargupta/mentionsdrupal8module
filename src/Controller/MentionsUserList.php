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
      $input_prefix = $config->get('input.prefix');
      $input_suffix = $config->get('input.suffix');
      $query = $this->entityQuery->get($entity_type);
      $entityids = $query
                  ->condition('status', 1)
                  ->sort('name')
                  ->execute();
      
      $entitys = entity_load_multiple($entity_type, $entityids);

      foreach ($entitys as $entity) {
        $newentityarray = array(
          'name' => $entity->get('name')->value,
          'id' => $entity->id() 
        );  
        if (isset($entitylist['data']['entitydata']) && in_array($newentityarray,$entitylist['data']['entitydata'])) {
            continue;
        }  
          
        $entitylist['data']['entitydata'][] = $newentityarray;
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

