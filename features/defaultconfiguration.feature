  Feature: Test configuration form

  @api
  Scenario: Ensure default configuration is correct
  Given I am an authenticated user
  When I visit admin/config/mentions
  Then I see the text 'Input'