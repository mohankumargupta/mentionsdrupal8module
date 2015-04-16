<?php

/**
 * @file
 * Definition of Drupal\mentions\Tests\MentionsFilterTest.
 */

namespace Drupal\mentions\Tests;

#use Drupal\simpletest\KernelTestBase;
use Drupal\Tests\UnitTestCase;
use Drupal\filter\FilterPluginCollection;
use Drupal\Core\DependencyInjection\ContainerBuilder;


/**
 * Test filter functionality 
 *
 * @group Mentions
 */
class MentionsFilterTest extends UnitTestCase {
 /*
  public static $modules = array('system', 'filter', 'user', 'views', 'views_ui', 'mentions');
  protected $filters;
*/
  protected $entityManager;

  protected function setUp() {
    parent::setUp();

    //$user = 'boo';

    //$container = new ContainerBuilder();

    
   $view_storage = $this->getMockBuilder('Drupal\Core\Entity\EntityStorageInterface')
     ->disableOriginalConstructor()
     ->getMock();     
   $view_storage->expects($this->once())
     ->method('loadByProperties')
     ->with(array('name' => $username))
     ->will($this->returnValue($user));

   $entity_manager = $this->getMock('Drupal\Core\Entity\EntityManagerInterface');
   $entity_manager->expects($this->once())
     ->method('getStorage')
     ->with('user')
     ->will($this->returnValue($user_storage));

    $this->entityManager = $entity_manager;
   
    //$container->set('entity.manager', $entity_manager);
     
  /*
    $this->installConfig(array('system', 'mentions'));

    $manager = $this->container->get('plugin.manager.filter');
    $bag = new FilterPluginCollection($manager, array());
    $this->filters = $bag->getAll();
    //print_r(array_keys($this->filters));
    */
  }

 function testFilterMentionByUsername() {

    $mentions_filter = $this->getMockBuilder('Drupal\mentions\Plugin\Filter\MentionsFilter')
      ->disableOriginalConstructor()
      ->getMock();

    $mentions_filter->setEntityManager($this->entityManager);  
 /*	
   $mentions_filter = $this->filters['filter_mentions'];   
   $test = function($input) use ($mentions_filter) {
     return $mentions_filter->process($input, 'und');
   };
 */
   $input = '[@admin]';
   $expected = 'boo';
   $username = 'admin';
   $user = 'boo';

   $test = function($input) use ($mentions_filter) {
     return $mentions_filter->process($input, 'und');
   };


   $this->assertIdentical($expected, $test($input));
   //$this->pass(print_r($test($input)));
 }

/*
 function testFilterMentionByUserId() {
   $mentions_filter = $this->filters['filter_mentions'];

 }
*/
}