<?php

/**
 * @file
 */

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Definition\Call;

use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * 
.
 *
 * Define application features from the specific context.
 */
class FeatureContext extends RawDrupalContext implements Context, SnippetAcceptingContext {
  /**
   * 
.
   *
   * Initializes context.
   * Every scenario gets its own context object.
   *
   * @param array $parameters
   *   Context parameters (set them in behat.yml)
   */
  public function __construct(array $parameters = []) {
    // Initialize your context here.
  }

  //
  // Place your definition and hook methods here:
  //
  //  /**
  //   * @Given I have done something with :stuff
  //   */
  //  public function iHaveDoneSomethingWith($stuff) {
  //    doSomethingWith($stuff);
  //  }
  //
  /**
   * 
.
   *
   * @When I wait :waittime seconds
   */
  public function iWaitSeconds($waittime) {
    sleep($waittime);
  }

  /**
   *
   * @When I custom fill content :text into the body field
   */
  public function fillBodyFieldWithText($text) {
    $this->getSession()->evaluateScript("document.querySelector('iframe').contentWindow.document.querySelector('body').innerHTML = '" . $text . "';");
  }
	
	/**
	 * @When I enable mentions filter
	 */
	public function enableMentionsFilter() {
		return array(
			new Call\When('I visit "admin/config/content/formats/manage/basic_html"'),
			new Call\When('I check "Mentions Filter"'),
			new Call\When('I press the "Save configuration" button')
		);
	}
	
	/**
	 * @When I add page node with title :title with mention :mentiontext 
	 */
	public function addNodeWithMention($title,$mentiontext) {
		
		return array(
			new Call\When('I visit "node/add/page"'),
			new Call\When('I fill in "Title" with "'.$title.'"'),
			new Call\When('I custom fill content "'.$mentiontext.'" into the body field'),
			new Call\When('I press the "Save and publish" button')
		);
		/*
		$steps[] = new Call\When('I visit "node/add/page"');
		$steps[] = new Call\When('I fill in "Title" with "'.$title.'"');
		$steps[] = new Call\When('I custom fill content "'.$mentiontext.'" into the body field');
		$steps[] = new Call\When('I press the "Save and publish" button');
	*/
		  }
		 
	
	
}

