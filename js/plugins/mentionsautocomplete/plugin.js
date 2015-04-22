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
                    $.ajax({
                        type: 'GET',
                        url: '/mentions/views/userlist',
                        success: function(data) {
                            var users = JSON.parse(data);
                            var username, userid;
                            
                            var editorid = editor.name;
                            var range = editor.getSelection().getRanges()[ 0 ],
                                rangeRoot = range.root,
                            startNode = range.startContainer;
                            var selection = editor.getSelection();
                            var bookmarks = selection.createBookmarks(true);
                         
                            if (editor.contextMenu) {
                                editor.addMenuGroup('Mentions');
                            }
                            
                            users.forEach(function(user, index, userarray){
                                username = user.name;
                                userid = user.uid;
                                
                                if (editor.contextMenu) {
                                    editor.addMenuItem(username, {
                                        label: username,
                                        group: 'Mentions',
                                        command: 'mentionsautocomplete'
                                    });
                                    
                                    editor.contextMenu.show(startNode.getParent(),null, 1,30);
                                }
                                
                                
                            });
                            

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


