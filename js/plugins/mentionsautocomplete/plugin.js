/**
 * @file
 * Mentions Autocomplete CKEditor plugin.
 */

(function ($, Drupal, drupalSettings, CKEDITOR) {

  "use strict";
  
  CKEDITOR.plugins.add('mentionsautocomplete', {
      init: function (editor) {
          editor.addCommand('mentionsautocomplete', {
                modes: {wysiwyg: 1},
                exec: function(editor) {
                    var userid = drupalSettings.user.uid;
                    var userpermissions = drupalSettings.user.permissionsHash; 
                    $.ajax({
                        type: 'GET',
                        url: '/mentions/views/userlist',
                        success: function(data) {
                            var users = JSON.parse(data);
                        }
                    });
                }
          });
      },
      afterInit: function(editor) {
          editor.on('key', function(evt) {
            if (evt.data.keyCode === CKEDITOR.SHIFT + 50) {
               editor.execCommand('mentionsautocomplete');
           }
      });
     }
  });
  
})(jQuery, Drupal, drupalSettings, CKEDITOR);


