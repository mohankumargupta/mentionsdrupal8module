  Feature: Base case

  @api
  Scenario: Add simple node with a mention for admin
  Given I am logged in as a user with the "administrator" role
  And "page" content:
  | title | promote | body    |
  | Boo   | 1       |[@admin] |  
  When I visit "admin/config/content/formats/manage/basic_html"
  And I check the box "filters[filter_mentions][status]"
  And I press the "Save configuration" button
  And I follow "Boo"
  Then I should see the link "@admin"
  
