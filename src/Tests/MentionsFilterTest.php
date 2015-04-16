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

 function testMention() {
 	$this->assertIdentical(1,1);
 }


}