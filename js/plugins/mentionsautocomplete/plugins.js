/**
* @file
* Mentions Autocomplete CKEditor plugin.
*/

(function ($, Drupal, CKEDITOR) {
'use strict';
var response = $.ajax({
    type: 'GET',
    url: '/mentions/userlist',
    async: false
}).responseJSON;
var mentions = [{'longname': 'Fred Flinstone' ,'username' : 'fred'}, {'longname': 'Barney Rubbles', 'username' : 'barney'}];
var at_config = {
    at: "@",
    data: response['data'],
    displayTpl: '<li data-value=${username}>${username}</li>',
    insertTpl: '@${username}'
    };






CKEDITOR.plugins.add('mentionsautocomplete', {
  init: function(editor) {

    function load_atwho(editor, at_config) {
    // WYSIWYG mode when switching from source mode
      if (editor.mode !== 'source') {
        editor.document.getBody().$.contentEditable = true;
        $(editor.document.getBody().$)
        .atwho('setIframe', editor.window.getFrame().$)
        .atwho(at_config);
       }
    // Source mode when switching from WYSIWYG
       else {
          $(editor.container.$).find(".cke_source").atwho(at_config);
        }
    }
    
    CKEDITOR.on('instanceReady', function(event) {
      var editor = event.editor;

      if (!editor) return;
      // Switching from and to source mode
      editor.on('mode', function(e) {
        load_atwho(this, at_config);
      });

      // First load
      load_atwho(editor, at_config);
      editor.setMode('source', function() {
	editor.setMode('wysiwyg');    	
      });      

    });
    
      
  }
});       
})(jQuery, Drupal, CKEDITOR);


