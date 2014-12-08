(function( $ ) {
	'use strict';


    tinymce.create('tinymce.plugins.Geot', {
        init : function(ed, url) {
            ed.addButton('geot_button', {
                title : 'Country GeoTarget Content',
                cmd : 'geot_button',
                image : url+'/world.png'
            });
            ed.addCommand('geot_button', function() {
				
                jQuery('#geot_editor').dialog({
                    height: 400,
                    width: '600px',
                    buttons: {
                        "Insert Shortcode": function() {
                            var str = '[geot ';
                            
                            var mode = jQuery('.geot_include_mode:checked').val();
                            
                            if ( jQuery('#geot_region').val() ) {
                                if (mode=='include') {str+='region="';} else {str+='exclude_region="'; }
                                
                                    str+=jQuery('#geot_region').val();
                                
                                str+='" ';
                            }
                            
                            if (jQuery('#geot_country').val()) {
                                if (mode=='include') {str+='country="';} else {str+='exclude_country="'; }
                                
                                    str+=jQuery('#geot_country').val();
                                
                                str+='" ';
                            }
                            var selected_text = ed.selection.getContent();
                            if( selected_text ){

                                str+="]"+selected_text+"[/geot]";    

                            } else {

                                str+="]YOUR CONTENT HERE[/geot]";

                            }   
                            
                                        
                            
                            var Editor = tinyMCE.get('content');
                            Editor.focus();
                            Editor.selection.setContent(str);

                            
                            jQuery( this ).dialog( "close" );
                        },
                        Cancel: function() {
                            jQuery( this ).dialog( "close" );
                        }
                    }
                }).dialog('open');
               
            });	
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : 'Geotarget Button',
                author : 'Damian Logghe',
                authorurl : 'http://wp.timersys.com',
                infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
                version : "0.1"
            };
        }        
    });
    tinymce.PluginManager.add('geot', tinymce.plugins.Geot);
})( jQuery );

