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

    $entitylist = array(
      'data' => array(
      'config' => '',
      'entitydata' => ''
     )
    );

    $entityfields = array(); 
     
    foreach ($this->allconfigs as $config) {
      $entity_type = $config->get('input.entity_type');
      $input_value = $config->get('input.inputvalue');     
      
      if (!isset($entityfields[$entity_type]) || !in_array($input_value, $entityfields[$entity_type])){
        if ($input_value != 'name' && $input_value != 'id') {
          $entityfields[$entity_type][]  = $input_value;
        }
      }
        
    }
    
    foreach ($this->allconfigs as $config) {  
      $entity_type = $config->get('input.entity_type');
      $input_prefix = $config->get('input.prefix');
      $input_suffix = $config->get('input.suffix');
      $input_value = $config->get('input.inputvalue');
      $query = $this->entityQuery->get($entity_type);
      $entityids = $query
                  ->condition('status', 1)
                  ->sort('name')
                  ->execute();
      
      $entitys = entity_load_multiple($entity_type, $entityids);

      
      foreach ($entitys as $entity) {
        $entityid = $entity->id();  
        $entitytypeid = $entity->getEntityTypeId();
        
        $newentityarray = array(
          'name' => $entity->get('name')->value,
          'id' => $entityid
        );
        

        
        $configentity = array(

                'suffix' => $input_suffix,
                'entitytypeid' => $entitytypeid,
                'inputvalue' => $input_value
            
            //'prefix' => $input_prefix,
            //'suffix' => $input_suffix,
            //'entitytypeid' => $entitytypeid
        );
        
        
        
        if (isset($entitylist['data']['config']) && isset($entitylist['data']['config'][$input_prefix])) {
            
        }
        
        else{
          $entitylist['data']['config'][$input_prefix] = $configentity;
        }
        
        foreach ($entityfields[$entity_type] as $field) {
           $newentityarray[$field] = $entity->get($field)->value; 
        }         

        
        if (isset($entitylist['data']['entitydata'][$entitytypeid]) && in_array($newentityarray,$entitylist['data']['entitydata'][$entitytypeid])) {
            continue;
        }  


        /*
         if ($input_value != "name" && $input_value != "id") {
          $newentityarray[$input_value] = $entity->get($input_value)->value;   
        }        
        */
       
       
        
        
        
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

