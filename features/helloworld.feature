
Feature: Does it work

  Scenario: Ensure Behat infrastructure is setup and working
    Given I am an anonymous user
     When I am on the homepage
     Then I should be on the homepage
