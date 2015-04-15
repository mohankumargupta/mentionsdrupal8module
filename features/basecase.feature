  Feature: Base case


  @api @javascript
  Scenario: internal test 
  Given I am logged in as a user with the "administrator" role
  When I visit "admin/config/content/formats/manage/basic_html" 
  And I check "Mentions Filter"
  And I press the "Save configuration" button
  And I wait 8 seconds
  And I wait 8 seconds
  And I visit "admin/config/content/formats/manage/basic_html"
  Then the "Mentions Filter" checkbox should be checked

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
  And I visit "admin/structure/types/manage/page/fields"
  And I press "Add Field" button
  And I wait 8 seconds
  And I select "Text (plain, long)" from "Add a new field"
  And I fill in "Label" with "MyBody"
  And I visit "node/add/page"
  And I wait 8 seconds
  And I fill in "Title" with "NewPage"
  And I fill in "MyBody" with "[@admin]"
  And I press the "Save and publish" button
  And I wait 8 seconds
  And I follow "NewPage"
  And I wait 8 seconds
  Then I should see the link "@admin"
