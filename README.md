<!--[![Build Status](https://secure.travis-ci.org/mohankumargupta/mentionsdrupal8module.png?branch=8.x-2.x-dev)](http://travis-ci.org/mohankumargupta/mentionsdrupal8module)-->
# Mentions for Drupal 8

Flexible zero-touch @mention support for your drupal site.

Tested against Drupal 8.2.4

Still in alpha but usable.

Based on work of Drupal 7 Mentions module version 7.x-2.x [https://drupal.org/project/mentions](https://drupal.org/project/mentions)

#Installation
1. Go to Releases https://github.com/mohankumargupta/mentionsdrupal8module/releases, download zip and extract to modules folder of your Drupal 8 installation (rename to mentions), then enable it through backend(Extend) or drush/drupal console

2. By default, the following mention formats are supported (for Basic and Full HTML formats):
   - **@username**           (mention by username)
   - **@#1**                 (mention by user id)
   - **\[@Barney Rubble\]**  (mention by username with spaces)

3. CKEditor autocomplete functionality is implemented. Please see the video below for a walkthrough. By default,
   the @username is used. Look for the Mentions toolbar dropdown, use it to change the mention format. 

# Configuration
1. Configuration->Text Formats -> Basic/Full HTML
   Here you can enable/disable the mentions filter. Also you can selectively enable/disable mention formats
2. Structure->Mention Types
   If the inbuilt mention formats are insufficient, create your own types.

#Feature Completion Status

- [x] @username will be rendered as hyperlink to username page 
- [x] @#userid will be rendered as hyperlink to username page 
- [x] [@Barney Rubble] will be rendered as hyperlink to username page
- [x] CKEditor autocomplete 
- [x] Manage Mention Formats
- [x] Specify order of precedence for Mention formats
- [x] When module enabled, Mentions filter enable automatically in Basic and Full HTML text formats
- [x] When module enabled, CKEditor autocomplete functionality enabled 
- [x] When module enabled, CKEditor Change Mentions Format dropdown available
- [ ] Give user ability to disable CKEditor autocomplete
- [x] View Support for Mentions
- [ ] Actions for Mentions 
- [ ] Settings Admin Page

# Development TODO List
- [ ] Conform to Coding standards
- [ ] Passing PHP unit tests
- [ ] Passing Behat tests 

# Video
https://www.youtube.com/watch?v=yUPJGLT5Y6s


