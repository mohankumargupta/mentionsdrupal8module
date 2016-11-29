<?php

/**
 * @file
 * Definition of Drupal\mentions\Tests\MentionsFilterTest.
 */

namespace Drupal\mentions\Tests;

use Drupal\mentions\Plugin\Filter\FilterMentions;
use Drupal\Tests\UnitTestCase;

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

    $configfactory = $this->getMock('Drupal\Core\Config\ConfigFactoryInterface');
    $this->configFactory = $configfactory;
    
    $mentions_manager = $this->getMockBuilder('Drupal\mentions\MentionsPluginManager')
      ->disableOriginalConstructor()
      ->getMock();
    $this->mentionsManager = $mentions_manager;
    
    $mentions_plugin = $this->getMock('Drupal\mentions\MentionsPluginInterface');
    $this->mentionsPlugin = $mentions_plugin;
  }

  public function testFilterMentionByUsername() {
    $input = '[@admin]';
    $username = 'admin';
    $node_entity_id = 1;
    $node_entity_type = 'node';
    $user = $this->getMockBuilder('Drupal\user\Entity\User')
                ->disableOriginalConstructor()
                ->getMock();

    $settings =  array(
        'entity_type' => 'user',
        'value' => 'name'
    );
    
    $target = array(
        "entity_type" => $node_entity_type ,
        "entity_id" => $node_entity_id
    );
    
    $expected = array(
      $input => array(
            'type' => 'entity',
            'source' => array(
              'string' => $input,
              'match' => 'admin',
            ),
            'target' => array('entity_type'=> 'user', 'entity_id' => 1),          
      )  

    );

    $inputconfig = array(
      'prefix' => '[@',
      'suffix' => ']',
      'entity_type' => 'user',
      'inputvalue' => 'name'  
    );

    $this->userStorage->expects($this->once())
      ->method('loadByProperties')
      ->with(array('name' => $username))
      ->will($this->returnValue(array($user)));

    $this->entityManager->expects($this->once())
      ->method('getStorage')
      ->with('user')
      ->will($this->returnValue($this->userStorage));

    $mentions_filter = $this->getMockBuilder('Drupal\mentions\Plugin\Filter\MentionsFilter')
      ->disableOriginalConstructor()
      ->setMethods(NULL)
      ->getMock();

    
    
    $mentions_filter->setMentionsManager($this->mentionsManager);
    $mentions_filter->setEntityManager($this->entityManager);

    /*
    $this->renderer->expects($this->once())
    ->method('render')
    ->with($input)
    ->will($this->returnValue($expected));
    $mentions_filter->setRenderer($this->renderer);
     */

    /*
    $this->config->expects($this->at(0))
      ->method('get')
      ->with('input.prefix')
      ->will($this->returnValue($inputconfig['prefix']));
    
    $this->config->expects($this->at(1))
      ->method('get')
      ->with('input.suffix')
      ->will($this->returnValue($inputconfig['suffix']));    

    $this->config->expects($this->at(2))
      ->method('get')
      ->with('input.entity_type')
      ->will($this->returnValue(''));   
    
    $this->config->expects($this->at(3))
      ->method('get')
      ->with('input.inputvalue')
      ->will($this->returnValue(''));       
    */
    
    $map = array(
        array('input.prefix', $inputconfig['prefix']),
        array('input.suffix', $inputconfig['suffix']),
        array('input.entity_type', $inputconfig['entity_type']),
        array('input.inputvalue', $inputconfig['inputvalue']),
    );
    $this->config
      ->method('get')
      ->will($this->returnValueMap(
            $map
      ));      
    
    $this->configFactory->expects($this->once())
      ->method('get')
      ->with('mentions.mentions_type.')
      ->will($this->returnValue($this->config));
  
    $this->mentionsPlugin->expects($this->any())
      ->method('targetCallback')
      ->with($node_entity_id, $settings)
      ->will($this->returnValue($target));
    
    $this->mentionsManager->expects($this->once())
      ->method('createInstance')
      ->with('entity')      
      ->will($this->returnValue());      
   
    
    $mentions_filter->setConfig($this->configFactory);
    $mentions_filter->setStringTranslation($this->getStringTranslationStub());

    $test = function($input) use ($mentions_filter) {
      return $mentions_filter->mentions_get_mentions($input);
    };

    $this->assertSame($expected, $test($input));
  }

}
