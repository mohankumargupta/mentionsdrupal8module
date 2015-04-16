<?php

/**
 * @file
 * Definition of Drupal\mentions\Tests\MentionsFilterTest.
 */

namespace Drupal\mentions\Tests;

use Drupal\simpletest\KernelTestBase;
use Drupal\filter\FilterPluginCollection;

/**
 * Test filter functionality 
 *
 * @group Mentions
 */
class MentionsFilterTest extends KernelTestBase {
  public static $modules = array('system', 'filter', 'user', 'views', 'views_ui', 'mentions');
  protected $filters;

  protected function setUp() {
    parent::setUp();
    $this->installConfig(array('system', 'mentions'));

    $manager = $this->container->get('plugin.manager.filter');
    $bag = new FilterPluginCollection($manager, array());
    $this->filters = $bag->getAll();
    //print_r(array_keys($this->filters));
  }

 function testFilterMentionByUsername() {	
   $mentions_filter = $this->filters['filter_mentions'];   
   $test = function($input) use ($mentions_filter) {
     return $mentions_filter->process($input, 'und');
   };
   $input = '[@admin]';
   $expected = 'boo';
   $username = 'admin';
   $user = 'boo';

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

   $this->pass(print_r($test($input)));
 }

/*
 function testFilterMentionByUserId() {
   $mentions_filter = $this->filters['filter_mentions'];

 }
*/
}