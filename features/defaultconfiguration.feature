  Feature: Test configuration form

  @api
  Scenario: Ensure default configuration is correct
  Given I am logged in as a user with the 'administrator' role
  When I visit 'admin/config/mentions'
  Then I see the text 'Input'