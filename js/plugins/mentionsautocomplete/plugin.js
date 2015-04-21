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
                    alert("hello");
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


