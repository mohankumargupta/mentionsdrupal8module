  Feature: Test configuration form

  @api
  Scenario: Can land on configuration page
  Given I am logged in as a user with the 'administrator' role
  When I visit 'admin/config/mentions'
  Then I see the text 'Input'

  @api
  Scenario: 
  Given I am logged in as a user with the 'administrator' role
  When I visit 'admin/config/mentions'
  Then the 'mentions[input][prefix]' field should contain 'boo'