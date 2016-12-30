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

var entitytypeid = response["data"]["config"][0].entitytypeid;

var at_config = {
    at: response['data']['config'][0]['prefix'],
    data: response['data']['entitydata'][entitytypeid],
    displayTpl: '<li data-value=${name}>${name}</li>',
    insertTpl: '@${name}',
    callbacks: {
      tplEval: function(query, map, event) {
    var error, template;
    if (event === 'onDisplay')
      template = this.setting.displayTpl;
    else
      template = this.setting.insertTpl;  
    try {
 
      return template.replace(/\$\{([^\}]*)\}/g, function(tag, key, pos) {
        return map[key];
      });
    } catch (error1) {
      error = error1;
      return "";
    }          
    }
    
  }
    };



    var load_atwho = function(editor, at_config) {
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
    };



CKEDITOR.plugins.add('mentionsautocomplete', {
  requires: 'richcombo',
  init: function(editor) {

    editor.ui.addRichCombo('mentionsautocomplete', {
        label: 'Mentions',
        title: 'Mentions',
        voiceLabel: 'Mentions',
        className: 'cke_format',
        multiSelect: false,
        panel: {
            css: [ editor.config.contentsCss, CKEDITOR.skin.getPath('editor') ],
	    voiceLabel: editor.lang.panelVoiceLabel
        },
        init: function()
        {
           this.startGroup( "Mentions" );
           var that = this;
           
           response.data.config.forEach( function(config) {
              that.add(config.prefix, config.prefix, config.prefix); 
           });
           //this.add("@", "usermentions", "usermentions");
                
        },

        onClick: function( value )
        {
                editor.focus();
                editor.fire( 'saveSnapshot' );
              
                editor.insertHtml(value);
                         

                editor.fire( 'saveSnapshot' );
                
                editor.document.getBody().$.contentEditable = true;
                $(editor.document.getBody().$)
                 .atwho('setIframe', editor.window.getFrame().$)
                 .atwho('destroy');
                
                
                CKEDITOR.plugins.registered.mentionsautocomplete.at_config.at = value;
                CKEDITOR.plugins.registered.mentionsautocomplete.load_atwho(editor,  CKEDITOR.plugins.registered.mentionsautocomplete.at_config);
        }
    });

    
    CKEDITOR.on('instanceReady', function(event) {
      var editor = event.editor;
      CKEDITOR.plugins.registered.mentionsautocomplete.load_atwho = load_atwho;
      CKEDITOR.plugins.registered.mentionsautocomplete.at_config = at_config;
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


