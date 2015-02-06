<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
//use Behat\Behat\Tester\Exception\PendingException;
//use Behat\Gherkin\Node\PyStringNode;
//use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I have created a node with a single mention of user admin
     */
    public function iHaveCreatedANodeWithASingleMentionOfUserAdmin()
    {
        //throw new PendingException();
    }


    /**
     * @When I view the node
     */
    public function iViewTheNode()
    {
        //throw new PendingException();
    }

    /**
     * @Then I should see the link (:arg1)
     */
    public function iShouldSeeTheLink($arg1)
    {
        //throw new PendingException();
    }
}
