  Feature: Base case

  @api @javascript
  Scenario: Add simple node with a mention for admin
  Given I am logged in as a user with the "Administrator" role
  When I visit "admin/config/content/formats/manage/basic_html" 
  And I check "Mentions Filter"
  And I press the "Save configuration" button
  And I visit "admin/config/content/formats/manage/basic_html"
  And I visit "node/add/page"
  And I fill in "Title" with "NewPage"
  And I custom fill content "[@admin]" into the body field
  And I press the "Save and publish" button
  And I visit "admin/content"
  And I follow "NewPage"
  And I follow "@admin"
  Then I should be on "user/1"

  @api @javascript
  Scenario: Add simple node with a mention for admin (alternative format)
  Given I am logged in as a user with the "administrator" role
  When I visit "node/add/page"
  And I fill in "Title" with "NewPage2"
  And I custom fill content "[@#1]" into the body field  
  And I press the "Save and publish" button
  And I visit "admin/content"
  And I follow "NewPage2"
  And I follow "@admin"
  Then I should be on "user/1"    
  