  Feature: Views Integration and mentions tab on user page

  @api @javascript
  Scenario: Mentions tab on user page
  Given I am logged in as a user with the "administrator" role
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
  Then I should see "Mentions"
   
  @api @javascript
  Scenario: Views Integration
  Given I am logged in as a user with the "administrator" role
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
  And I follow "Mentions"
  Then I should see "View mention"