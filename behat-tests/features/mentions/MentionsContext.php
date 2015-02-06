<?php


use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;

class MentionsContext implements Context, SnippetAcceptingContext
{
    /**
     * @Given I have created a node with a single mention of user admin
     */
    public function iHaveCreatedANodeWithASingleMentionOfUserAdmin()
    {
        throw new PendingException();
    }
}