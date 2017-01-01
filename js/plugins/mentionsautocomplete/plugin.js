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

//Default values
var prefixes = Object.keys(response.data.config);
var entitytypeid = response.data.config[prefixes[0]].entitytypeid;

var at_config = {
    at: prefixes[0],
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

var boo =2;

    var load_atwho = function(editor, at_config) {
     if (editor.window.getFrame() === null) {
       return;  
     }   
        
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
           
           CKEDITOR.plugins.registered.mentionsautocomplete.prefixes.forEach(function(element){
             that.add(element, element, element);
           });
           
           /*
           response.data.config.forEach( function(config) {
              var prefix = 
              that.add(config.prefix, config.prefix, config.prefix); 
           });
           */
           //this.add("@", "usermentions", "usermentions");
                
        },

        onClick: function( value )
        {
                editor.focus();
                editor.fire( 'saveSnapshot' );
                editor.insertHtml('&nbsp;');
                editor.insertHtml(value);
                         

                editor.fire( 'saveSnapshot' );
                
                editor.document.getBody().$.contentEditable = true;
                $(editor.document.getBody().$)
                 .atwho('setIframe', editor.window.getFrame().$)
                 .atwho('destroy');
                
                var input_entitytype = CKEDITOR.plugins.registered.mentionsautocomplete.responsedata.data.config[value].entitytypeid;
                var input_inputvalue = CKEDITOR.plugins.registered.mentionsautocomplete.responsedata.data.config[value].inputvalue;
                var entityfield = CKEDITOR.plugins.registered.mentionsautocomplete.responsedata.data.entitydata[input_entitytype];
                var data = [];
                entityfield.forEach(function(element){
                   var f = {};
                   f['name'] = element.name;
                   f[input_inputvalue] = element[input_inputvalue]
                   data.push(f);
                });
            
                var suffix = CKEDITOR.plugins.registered.mentionsautocomplete.responsedata.data.config[value].suffix;
                var displayTpl = '<li data-value=${' + input_inputvalue + '}>${name}</li>';
                var insertTpl = value + '${' + input_inputvalue + '}' + suffix;
                //var insertTpl = '@${name}';
                
                CKEDITOR.plugins.registered.mentionsautocomplete.at_config.at = value;
                CKEDITOR.plugins.registered.mentionsautocomplete.at_config.insertTpl = insertTpl;
                CKEDITOR.plugins.registered.mentionsautocomplete.at_config.displayTpl = displayTpl;
                CKEDITOR.plugins.registered.mentionsautocomplete.at_config.data = data;                
                CKEDITOR.plugins.registered.mentionsautocomplete.load_atwho(editor,  CKEDITOR.plugins.registered.mentionsautocomplete.at_config);
                $(editor.document.getBody().$).trigger("click");
        }
    });

    
    CKEDITOR.on('instanceReady', function(event) {
      var editor = event.editor;
      CKEDITOR.plugins.registered.mentionsautocomplete.load_atwho = load_atwho;
      CKEDITOR.plugins.registered.mentionsautocomplete.at_config = at_config;
      CKEDITOR.plugins.registered.mentionsautocomplete.prefixes = prefixes;
      CKEDITOR.plugins.registered.mentionsautocomplete.responsedata = response;
      if (!editor) return;
      // Switching from and to source mode
      editor.on('mode', function(e) {
        load_atwho(this, at_config);
      });

      // First load
      load_atwho(editor, at_config);
      /*
      editor.setMode('source', function() {
	editor.setMode('wysiwyg');    	
      });      
      */
    });
    
      
  }
});    




})(jQuery, Drupal, CKEDITOR);


