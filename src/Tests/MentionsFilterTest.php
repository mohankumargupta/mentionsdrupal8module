<?php

/**
 * @file
 * Definition of Drupal\mentions\Tests\MentionsFilterTest.
 */

namespace Drupal\mentions\Tests;

use Drupal\Tests\KernelTestCase;

/**
 * Tests Mentions Filter module 
 *
 * @group mentions
 */
class MentionsFilterTest extends KernelTestBase {
	  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('system', 'filter', 'user', 'mentions');

    /**
   * @var \Drupal\filter\Plugin\FilterInterface[]
   */
  protected $filters;

  protected function setUp() {
    parent::setUp();
    $this->installConfig(array('system', 'mentions'));

    $manager = $this->container->get('plugin.manager.filter');
    $bag = new FilterPluginCollection($manager, array());
    $this->filters = $bag->getAll();
    print_r($this->filters);
  }

 function testMention() {
 	$this->assertIdentical(1,1);
 }


}