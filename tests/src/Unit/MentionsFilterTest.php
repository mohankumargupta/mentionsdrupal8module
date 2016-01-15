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
  }

  public function testFilterMentionByUsername() {
    $input = '[@admin]';
    $username = 'admin';
    $user = $this->getMockBuilder('Drupal\user\Entity\User')
                ->disableOriginalConstructor()
                ->getMock();

    $expected = array(
      $input => array(
            'type' => 'mentions.mentions_type',
            'source' => array(
              'string' => $input,
              'match' => 'admin',
            ),
            'target' => 'user/1',          
      )  

    );

    $inputconfig = array(
      'prefix' => '[@',
      'suffix' => ']',
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

    $mentions_filter->setEntityManager($this->entityManager);

    /*
    $this->renderer->expects($this->once())
    ->method('render')
    ->with($input)
    ->will($this->returnValue($expected));
    $mentions_filter->setRenderer($this->renderer);
     */

    $this->config->expects($this->any())
      ->method('get')
      ->with('input')
      ->will($this->returnValue($inputconfig));
     

    $this->configFactory->expects($this->once())
      ->method('get')
      ->with('mentions.mentions_type.')
      ->will($this->returnValue($this->config));

    $mentions_filter->setConfig($this->configFactory);
    $mentions_filter->setStringTranslation($this->getStringTranslationStub());

    $test = function($input) use ($mentions_filter) {
      return $mentions_filter->mentions_get_mentions($input);
    };

    $this->assertSame($expected, $test($input));
  }

}
