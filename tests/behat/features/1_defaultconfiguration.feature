  Feature: Test configuration form

  @api @javascript
  Scenario: Default values for configuration page 
  Given I am logged in as a user with the "administrator" role
  When I visit "admin/structure/mentions"
  And I click "Add Mentions Type"
  And I wait 10 seconds
  And I fill in "Name" with "boo"
  And I click "Save Mentions Type"
  And I wait 10 seconds
  Then I should see "boo" 

