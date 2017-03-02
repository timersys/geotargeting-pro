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
                    height: 500,
                    width: '600px',
                    buttons: {
                        "Insert Shortcode": function() {

                            var mode = jQuery('#geot_editor .geot_include_mode:checked').val();

                            if ( jQuery('#geot_state').val() ) {
                                var str = '[geot_state ';
                                if (mode == 'include') {
                                    str += 'state="';
                                } else {
                                    str += 'exclude_state="';
                                }

                                str += jQuery('#geot_state').val();

                                str += '" ';

                                var selected_text = ed.selection.getContent();
                                if (selected_text) {

                                    str += "]" + selected_text + "[/geot_state]";

                                } else {

                                    str += "]YOUR CONTENT HERE[/geot_state]";

                                }
                            } else if ( jQuery('#geot_city').val() ) {
                                var str = '[geot_city ';
                                if (mode == 'include') {
                                    str += 'city="';
                                } else {
                                    str += 'exclude_city="';
                                }

                                str += jQuery('#geot_city').val();

                                str += '" ';

                                var selected_text = ed.selection.getContent();
                                if (selected_text) {

                                    str += "]" + selected_text + "[/geot_city]";

                                } else {

                                    str += "]YOUR CONTENT HERE[/geot_city]";

                                }
                            } else if (jQuery('#geot_city_region').val()) {
                                var str = '[geot_city ';
                                if (mode == 'include') {
                                    str += 'region="';
                                } else {
                                    str += 'exclude_region="';
                                }

                                str += jQuery('#geot_city_region').val();

                                str += '" ';

                                var selected_text = ed.selection.getContent();
                                if (selected_text) {

                                    str += "]" + selected_text + "[/geot_city]";

                                } else {

                                    str += "]YOUR CONTENT HERE[/geot_city]";

                                }
                            } else {

                                var str = '[geot ';

                                if (jQuery('#geot_region').val()) {
                                    if (mode == 'include') {
                                        str += 'region="';
                                    } else {
                                        str += 'exclude_region="';
                                    }

                                    str += jQuery('#geot_region').val();

                                    str += '" ';
                                }

                                if (jQuery('#geot_country').val()) {
                                    if (mode == 'include') {
                                        str += 'country="';
                                    } else {
                                        str += 'exclude_country="';
                                    }

                                    str += jQuery('#geot_country').val();

                                    str += '" ';
                                }


                                var selected_text = ed.selection.getContent();
                                if (selected_text) {

                                    str += "]" + selected_text + "[/geot]";

                                } else {

                                    str += "]YOUR CONTENT HERE[/geot]";

                                }

                            }

                        //    var Editor = tinyMCE.get('content');
                        //    Editor.focus();
                        //    Editor.selection.setContent(str);
							ed.execCommand('mceInsertContent', 0, str);

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
