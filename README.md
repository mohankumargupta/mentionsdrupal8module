[![Build Status](https://secure.travis-ci.org/mohankumargupta/mentionsdrupal8module.png?branch=8.2.x)](http://travis-ci.org/mohankumargupta/mentionsdrupal8module)

Drupal 8 port of Mentions Module (8.2.x-devel branch).

Original Mentions module for Drupal 7
[https://drupal.org/project/mentions](https://drupal.org/project/mentions)

#Implemented 
- replace \[@admin\] and \[@#1\] with [@admin](user/1)
- allow custom actions when a mention is created/updated/deleted
- ckeditor plugin to do autocomplete of user inside CKEditor

#TODO List
- jquery textcomplete integration for non-ckeditor fields


#Installation
1. Enable module as usual
2. Go to config page 
3. Enable Mentions Filter for the text format you want 
4. Add content with [@username] or [@#userid] 