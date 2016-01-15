  Feature: Test configuration form

  @api @javascript
  Scenario: Default values for configuration page 
  Given I am logged in as a user with the "administrator" role
  When I visit "admin/config/mentions"
  Then the "mentions[input][prefix]" field should contain "[@"
  And the "mentions[input][suffix]" field should contain "]"
  And the "mentions[output][prefix]" field should contain "@"
