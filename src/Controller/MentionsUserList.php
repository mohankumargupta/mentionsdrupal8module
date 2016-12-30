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
        $entityid = $entity->id();  
        $entitytypeid = $entity->getEntityTypeId();
        $entityuuid = $entity->uuid();
        $newentityarray = array(
          'name' => $entity->get('name')->value,
          'id' => $entityid
        );
        
        $configentity = array(
            $input_prefix => array(
                'suffix' => $input_suffix,
                'entitytypeid' => $entitytypeid
            )
            //'prefix' => $input_prefix,
            //'suffix' => $input_suffix,
            //'entitytypeid' => $entitytypeid
        );
        
        if (isset($entitylist['data']['config']) && in_array($configentity, $entitylist['data']['config'])) {
            continue;
        }
        
        $entitylist['data']['config'][] = $configentity;
        
        if (isset($entitylist['data']['entitydata'][$entitytypeid]) && in_array($newentityarray,$entitylist['data']['entitydata'][$entitytypeid])) {
            continue;
        }  
        
                  
        $entitylist['data']['entitydata'][$entitytypeid][] = $newentityarray;
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

