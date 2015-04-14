  Feature: Base case

  @api @javascript
  Scenario: Add simple node with a mention for admin
  Given users:
  | name            | mail    | status |
  | Mohan Gupta     | a@b.com | 1      |
  And "page" content:
  | title | promote | body    | author      |
  | Boo   | 1       |[@admin] | Mohan Gupta | 
  And I am logged in as a user with the "administrator" role
  When I visit "admin/config/content/formats/manage/basic_html"
  And I check the box "filters[filter_mentions][status]"
  And I press the "Save configuration" button
  And I wait 8 seconds
  And I go to the homepage
  And I follow "Boo"
  Then I should see "admin"

  @api @javascript
  Scenario: Add simple node with a mention for admin (alternate format)
  Given I am logged in as a user with the "administrator" role
  And "page" content:
  | title | promote | body    |
  | Boo   | 1       |[@#1] |  
  When I visit "admin/config/content/formats/manage/basic_html"
  And I check the box "filters[filter_mentions][status]"
  And I press the "Save configuration" button
  And I wait 8 seconds
  And I go to the homepage
  Then I should see "admin"
