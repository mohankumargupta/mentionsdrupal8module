/**
 * @file
 * Mentions Autocomplete CKEditor plugin.
 */

(function ($, Drupal, drupalSettings, CKEDITOR) {
    "use strict";
    CKEDITOR.plugins.add('mentionsautocomplete', {
        init: function (editor) {
            editor.mentions = [];
            /*jslint unparam: true*/
            editor.contextMenu.addListener(function (startElement, selection, path) {
                //var editor = selection.root.editor;

                if (selection.getType() !== CKEDITOR.SELECTION_TEXT) {
                    return;
                }

                /*jslint unparam: true*/
                return editor.mentions.reduce(function (prev, curr, index, array) {
                    var newValue = prev;
                    newValue[curr] = CKEDITOR.TRISTATE_OFF;
                    return newValue;
                }, {});
            });
            editor.addCommand('mentionsautocomplete', {
                modes: {wysiwyg: 1},
                exec: function (editor, editordata) {
                    $.ajax({
                        type: 'GET',
                        cache: true,
                        url: '/admin/mentions/userlist'
                    }).done(function (data) {
                        var users = data.userlist,
                            previousChars = "",
                            userStartsWith;

                        if (editordata.lastprefix > editordata.lastsuffix) {
                            previousChars = editordata.html.substring(editordata.lastprefix + drupalSettings.mentions_prefix.length);
                        }

                        userStartsWith = previousChars + editordata.charPressed;

                        if (editor.contextMenu) {
                            editor.addMenuGroup('Mentions');
                            editor.removeMenuItem('paste');
                        }

                        users.forEach(function (user, index, userarray) {
                            //username = user.name;
                            //userid = user.uid;

                            if (user.lastIndexOf(userStartsWith, 0) !== 0) {
                                editor.removeMenuItem(user);
                                return;
                            }

                            if (editor.contextMenu) {
                                editor.addCommand('mentions_' + user, {
                                    modes: {wysiwyg: 1},
                                    exec: function (editor) {
                                        var command = this.name.replace('mentions_', ''),
                                            newhtml,
                                            newhtmlcount;

                                        if (editordata.lastprefix > editordata.lastsuffix) {
                                            newhtml = editordata.html.substring(editordata.lastprefix + drupalSettings.mentions_prefix.length);
                                            newhtmlcount = newhtml.length;
                                            command = command.substring(newhtmlcount + 1);
                                        }
                                        //var commands = editor.commands;
                                        //var range = editor.getSelection().getRanges()[0];
                                        //var rangenew = editor.createRange();
                                        //rangenew.setStartAt(editor.getSelection())                
//rangenew.moveToElementEditEnd( rangenew.root );
                                        //rangenew.startOffset = rangenew.startOffset - 1;
                                        //editor.getSelection().selectRanges( [rangenew] );
                                        //var selection = editor.getSelection();
                                        //var selectedElement = selection.getSelectedElement();
                                        //selectedElement.setHtml('i wonder');
                                        editor.insertHtml(command + drupalSettings.mentions_suffix);
                                        editor.removeMenuItem(user);
                                        editor.addMenuItem('paste', {label: 'Paste', group: 'Paste', command: 'paste'});
                                        editor.addMenuGroup('Paste');
                                    }
                                });
                                editor.addMenuItem(user, {
                                    label: user,
                                    group: 'Mentions',
                                    command: 'mentions_' + user
                                });

                                editor.mentions.push(user);

                                //var menuitem = editor.getMenuItem(user),
                                var range = editor.getSelection().getRanges()[0],
                                    offset = range.endOffset,
                                    startNode = range.endContainer;
                                //range.moveToPosition( range.root, CKEDITOR.POSITION_BEFORE_END );
                                //editor.getSelection().selectRanges( [ range ] );

                                editor.contextMenu.show(startNode.getParent(), null, offset * 7);
                            }


                        });


                    });
                    //});
                }
            });
        },
        afterInit: function (editor) {
            editor.on('key', function (evt) {
                var keystroke = evt.data.domEvent.getKeystroke(),
                    charPressed,
                    range,
                    endNode,
                    html,
                    lastprefix,
                    lastsuffix,
                    data;

                if (keystroke > CKEDITOR.SHIFT + 16) {
                    charPressed = String.fromCharCode(keystroke - CKEDITOR.SHIFT);
                }

                else {
                        charPressed = String.fromCharCode(evt.data.keyCode).toLowerCase();
                    }

                if (keystroke === 8 || keystroke === 27) {
                    editor.addMenuItem('paste', {label: 'Paste', group: 'Paste', command: 'paste'});
                    editor.addMenuGroup('Paste');
                }

                range = editor.getSelection().getRanges()[0];
                endNode = range.endContainer;
                html = endNode.getText().replace('/<br\/>$/', '');
                /*var element = endNode.getParent();
                 var html = element.getHtml();*/
                lastprefix = html.lastIndexOf(drupalSettings.mentions_prefix);
                lastsuffix = html.lastIndexOf(drupalSettings.mentions_suffix);

                if (lastprefix > lastsuffix) {
                    data = {'html': html, 'lastprefix': lastprefix, 'lastsuffix': lastsuffix, 'charPressed': charPressed};
                    editor.execCommand('mentionsautocomplete', data);
                }

            });
        }
    });

})(jQuery, Drupal, drupalSettings, CKEDITOR);


