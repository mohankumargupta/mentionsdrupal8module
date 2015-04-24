/**
 * @file
 * Mentions Autocomplete CKEditor plugin.
 */

(function ($, Drupal, drupalSettings, CKEDITOR) {

  "use strict";
  CKEDITOR.plugins.add('mentionsautocomplete', {
      init: function (editor) {
          editor.mentions = [];
          editor.on('contextMenu', function(evt){
              var i=3;
          });
          editor.contextMenu.addListener(function(startElement, selection, path){
              var selectiontype = selection.getType();
              if (selection.getType() !== CKEDITOR.SELECTION_TEXT) {
                  return;
              }
              var editor = selection.root.editor;
              return editor.mentions.reduce(function(prev,curr,index,array) {
                  var newValue = prev;
                  newValue[curr] = CKEDITOR.TRISTATE_OFF;
                  return newValue;
              }, {});
          });
          editor.addCommand('mentionsautocomplete', {
                modes: {wysiwyg: 1},
                exec: function(editor, editordata) {
                    /*
                    var userStartsWith="";
                                        if (editordata.lastprefix > editordata.lastsuffix) {
                                            userStartsWith = editordata.html.substring(editordata.lastprefix + drupalSettings.mentions_prefix.length);
                                            
                                        }                    
*/                    
                    
                    $.ajax({
                        type: 'GET',
                        cache: true,
                        url: '/admin/mentions/userlist'
                    }).done(function(data) {
                            var users = data.userlist;
                            var previousChars="";
                            if (editordata.lastprefix > editordata.lastsuffix) {
                                previousChars = editordata.html.substring(editordata.lastprefix + drupalSettings.mentions_prefix.length);                                            
                            }
                            var userStartsWith = previousChars + editordata.charPressed;
                            
                            //var username, userid;
                            
                            //var editorid = editor.name;
                            //var range = editor.getSelection().getRanges()[ 0 ],
                            //rangeRoot = range.root,
                            //startNode = range.startContainer;
                            //var selection = editor.getSelection();
                            //var bookmarks = selection.createBookmarks(true);
                         
                            if (editor.contextMenu) {
                                editor.addMenuGroup('Mentions');
                                editor.removeMenuItem('paste');
                            }
                            
                            users.forEach(function(user, index, userarray){
                                //username = user.name;
                                //userid = user.uid;
                                 
                                if ( user.lastIndexOf(userStartsWith,0) !== 0) {
                                    editor.removeMenuItem(user);
                                    return;
                                }
                                
                                if (editor.contextMenu) {
                                    editor.addCommand('mentions_' + user, {
                                    modes: {wysiwyg: 1},
                                    exec: function(editor) {
                                        var command = this.name.replace('mentions_','');
                                        if (editordata.lastprefix > editordata.lastsuffix) {
                                            var newhtml = editordata.html.substring(editordata.lastprefix + drupalSettings.mentions_prefix.length);
                                            var newhtmlcount = newhtml.length;
                                            command = command.substring(newhtmlcount + 1);
                                        }
                                        //var commands = editor.commands;
                                        var range = editor.getSelection().getRanges()[ 0 ];
                                        var rangenew = editor.createRange();
                                        //rangenew.setStartAt(editor.getSelection())                
//rangenew.moveToElementEditEnd( rangenew.root );
                                        //rangenew.startOffset = rangenew.startOffset - 1;
                                        //editor.getSelection().selectRanges( [rangenew] );
                                        //var selection = editor.getSelection();
                                        //var selectedElement = selection.getSelectedElement();
                                        //selectedElement.setHtml('i wonder');
                                        editor.insertHtml(command + drupalSettings.mentions_suffix);
                                        editor.removeMenuItem(user);
                                    }                                         
                                    });
                                    editor.addMenuItem(user, {
                                        label: user,
                                        group: 'Mentions',
                                        command: 'mentions_' + user
                                    });
                                    
                                    editor.mentions.push(user);
                                    
                                    var menuitem = editor.getMenuItem(user);
                                    
                                    var range = editor.getSelection().getRanges()[ 0 ];
                                    var offset = range.endOffset;
                                    //range.moveToPosition( range.root, CKEDITOR.POSITION_BEFORE_END );
                                    var startNode = range.endContainer;
                                    //editor.getSelection().selectRanges( [ range ] );
                                    editor.contextMenu.show(startNode.getParent(), null,offset*7);
                                }
                                
                                
                            });
                            

                        });
                    //});
                }
          });
      },
      afterInit: function(editor) {
          editor.on('key', function(evt) {
             var keystroke = evt.data.domEvent.getKeystroke();
             var charPressed;
             if (keystroke > CKEDITOR.SHIFT + 16) {
                 charPressed = String.fromCharCode(keystroke - CKEDITOR.SHIFT);
             } 
             else {
                 charPressed = String.fromCharCode(evt.data.keyCode).toLowerCase();
             }
            //if (evt.data.keyCode === CKEDITOR.SHIFT + 50) {
               var range = editor.getSelection().getRanges()[ 0 ];
               var endNode = range.endContainer;
               var element = endNode.getParent();
               var html = element.getHtml();
               var lastprefix = html.lastIndexOf(drupalSettings.mentions_prefix); 
               var lastsuffix = html.lastIndexOf(drupalSettings.mentions_suffix);
               
              if (lastprefix > lastsuffix) {
                  var data = {'html':html, 'lastprefix': lastprefix, 'lastsuffix': lastsuffix, 'charPressed':charPressed};
                   editor.execCommand('mentionsautocomplete', data);
               }
                            
               //var lastchars = html.slice(-2);
               //evt.data.domEvent.preventDefault();
               //editor.execCommand('mentionsautocomplete');
           //}
      });
     }
  });
  
})(jQuery, Drupal, drupalSettings, CKEDITOR);


