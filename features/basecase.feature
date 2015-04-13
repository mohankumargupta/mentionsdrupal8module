  Feature: Base case

  @api
  Scenario: Add simple node with a mention for admin
  Given I am logged in as a user with the "administrator" role  
  When I visit "admin/config/content/formats/manage/basic_html"
  And I check the box "filters[filter_mentions][status]"