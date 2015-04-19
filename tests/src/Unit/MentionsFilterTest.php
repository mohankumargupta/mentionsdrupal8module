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
 * @coverDefaultClass \Drupal\mentions\Plugin\Filter\FilterMentions
 * @group Mentions
 */
class MentionsFilterTest extends UnitTestCase {
 /*
  public static $modules = array('system', 'filter', 'user', 'views', 'views_ui', 'mentions');
  protected $filters;
*/
  protected $entityManager;
  protected $renderer;
  protected $userStorage;
  protected $configFactory;
  protected $config;


  protected function setUp() {
    parent::setUp();
    
   $this->userStorage = $this->getMockBuilder('Drupal\Core\Entity\EntityStorageInterface')
     ->disableOriginalConstructor()
     ->getMock();     

   $entity_manager = $this->getMock('Drupal\Core\Entity\EntityManagerInterface');
   $this->entityManager = $entity_manager;
    
    $renderer = $this->getMock('Drupal\Core\Render\RendererInterface');
    $this->renderer = $renderer;

    $config = $this->getMock('Drupal\Core\Config\ConfigFactoryInterface');
    $this->config = $config;
    
    $configFactory = $this->getMock('Drupal\Core\Config\ConfigFactoryInterface');
    $this->configFactory = $configFactory;
  }

 function testFilterMentionByUsername() {
   $input = '[@admin]';
   $user = array(
       'name' => 'admin',
       'uid' => 1
   );
   $expected = array(
       array(
           'text'=> $input,
           'user'=> $user
       )
   );
   $username = 'admin';
   $inputconfig = array(
       'input' => array(
           'prefix' => '[@',
           'suffix' => ']'
       )
   );
   

      $this->userStorage->expects($this->once())
     ->method('loadByProperties')
     ->with(array('name' => $username))
     ->will($this->returnValue($user));

     $this->entityManager->expects($this->once())
     ->method('getStorage')
     ->with('user')
     ->will($this->returnValue($this->userStorage));
      
    $mentions_filter = $this->getMockBuilder('Drupal\mentions\Plugin\Filter\FilterMentions')
      ->disableOriginalConstructor()
      ->setMethods(null)
      ->getMock();

    $mentions_filter->setEntityManager($this->entityManager); 
    
    $this->renderer->expects($this->once())
      ->method('render')
      ->with($input)
      ->will($this->returnValue($expected));
    $mentions_filter->setRenderer($this->renderer);
    
    
    $this->config->expects($this->once())
      ->method('get')
      ->with('input')
      ->will($this->returnValue($inputconfig)); 
    
    $this->configFactory->expects($this->once())
      ->method('get')
      ->with('mentions.mentions')
      ->will($this->returnValue($this->config));
    
    $mentions_filter->setConfig($this->configFactory);
    
 /*	
   $mentions_filter = $this->filters['filter_mentions'];   
   $test = function($input) use ($mentions_filter) {
     return $mentions_filter->process($input, 'und');
   };
 */


   $test = function($input) use ($mentions_filter) {
     return $mentions_filter->mentions_get_mentions($input);
   };


   $this->assertEquals($expected, $test($input));
   //$this->pass(print_r($test($input)));
 }

/*
 function testFilterMentionByUserId() {
   $mentions_filter = $this->filters['filter_mentions'];

 }
*/
}