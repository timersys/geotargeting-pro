(function( $ ) {
	'use strict';


    tinymce.create('tinymce.plugins.Geot', {
        init : function(ed, url) {
            ed.addButton('geot_button', {
                title : 'Country GeoTarget Content',
                icon : true,
                image : url+'/world.png',
                onclick : function() {
                    ed.windowManager.open({
                        file : ajaxurl + '?action=geot_get_popup',
                        title: 'Geotargeting Shortcode' + ":",
                        width : 700,
                        height : 500,
                        inline : 1
                    }, {
                        editor: ed,
                        jquery: $ //jQuery Object
                    });
                }
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
})( jQuery,ajaxurl );
