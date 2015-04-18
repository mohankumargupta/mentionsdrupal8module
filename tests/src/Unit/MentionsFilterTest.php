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
 * @coverDefaultClass \Drupal\mentions\Plugin\Filter\MentionsFilter
 * @group Mentions
 */
class MentionsFilterTest extends UnitTestCase {
 /*
  public static $modules = array('system', 'filter', 'user', 'views', 'views_ui', 'mentions');
  protected $filters;
*/
  protected $entityManager;
  protected $renderer;

  protected function setUp() {
    parent::setUp();
    
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
    
    $renderer = $this->getMock('Drupal\Core\Render\RendererInterface');

    $this->renderer = $renderer;
  }

 function testFilterMentionByUsername() {
   $input = '[@admin]';
   $expected = 'boo';
   $username = 'admin';
   $user = 'boo';
     
    $mentions_filter = $this->getMockBuilder('Drupal\mentions\Plugin\Filter\MentionsFilter')
      ->disableOriginalConstructor()
      ->getMock();

    $mentions_filter->setEntityManager($this->entityManager); 
    
    $this->renderer->expects($this->once())
                   ->method('render')
                   ->with($input)
                   ->will($this->returnValue($expected));
    $mentions_filter->setRenderer($this->renderer);
 /*	
   $mentions_filter = $this->filters['filter_mentions'];   
   $test = function($input) use ($mentions_filter) {
     return $mentions_filter->process($input, 'und');
   };
 */


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