(function( $ ) {
	'use strict';

	  $(document).ready(function() {
          if ( geot && ( /iP(od|hone)/i.test(window.navigator.userAgent) || /IEMobile/i.test(window.navigator.userAgent) || /Windows Phone/i.test(window.navigator.userAgent) || /BlackBerry/i.test(window.navigator.userAgent) || /BB10/i.test(window.navigator.userAgent) || /Android.*Mobile/i.test(window.navigator.userAgent))) {
              geot.dropdown_search = true;
          }
          var geot_options = {
              onChange: function(country_code){
                  if( !country_code.length )
                      return;
                  GeotCreateCookie('geot_country', country_code,999);
                  if( geot.dropdown_redirect && geot.dropdown_redirect.length ) {
                      window.location.replace(geot.dropdown_redirect);
                  } else {
                      window.location.reload();
                  }
              }
          };
          if( $('.geot_dropdown').data('flags') ){
              geot_options.render = {
                  option: function(data, escape) {
                      return '<div class="option">' +
                          '<span class="geot-flag flag-' + escape(data.value.toLowerCase()) + '"></span>' +
                          '<span class="url">' + escape(data.text) + '</span>' +
                          '</div>';
                  },
                  item: function(data, escape) {
                      return '<div class="item"><span class="geot-flag flag-' + escape(data.value.toLowerCase()) + '"></span>'+ escape(data.text) + '</div>';
                  }
              };
          }
		  if( $('.geot_dropdown').length ) {
	          var $geot_select = $('.geot_dropdown').selectize( geot_options );
			  if( GeotReadCookie('geot_country') ) {
				  var selectize = $geot_select[0].selectize;
				  selectize.addItem(GeotReadCookie('geot_country'), true);
			  }
		  }
	  });


/**
 * Cookie functions
 */
function GeotCreateCookie(name, value, days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = "; expires=" + date.toGMTString();
	} else var expires = "";
	document.cookie = name + "=" + value + expires + "; path=/";
}

function GeotReadCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

/**
 * Ajax requests
 * @param data
 * @param url
 * @param success_cb
 * @param error_cb
 * @param dataType
 */
var GeotRequest = function ( data, success_cb, error_cb, dataType){
    // Prepare variables.
    var ajax       = {
            url:      geot.ajax_url,
            data:     data,
            cache:    false,
            type:     'POST',
            dataType: 'json',
            timeout:  30000
        },
        dataType   = dataType || false,
        success_cb = success_cb || false,
        error_cb   = error_cb   || false;

    // Set success callback if supplied.
    if ( success_cb ) {
        ajax.success = success_cb;
    }

    // Set error callback if supplied.
    if ( error_cb ) {
        ajax.error = error_cb;
    }

    // Change dataType if supplied.
    if ( dataType ) {
        ajax.dataType = dataType;
    }
    // Make the ajax request.
    $.ajax(ajax);

}

    const getUrlParameter = function(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(window.location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };
    const geot_debug = getUrlParameter('geot_debug'),
     geot_debug_iso  = getUrlParameter('geot_debug_iso'),
     geot_state  = getUrlParameter('geot_state'),
     geot_state_code  = getUrlParameter('geot_state_code'),
     geot_city  = getUrlParameter('geot_city'),
     geot_zip  = getUrlParameter('geot_zip');

    var data = {
        'action' : 'geot_ajax',
        'geots'  : {},
        'vars'   : geot,
        'geot_debug' : geot_debug,
        'geot_debug_iso'  : geot_debug_iso,
        'geot_state'  : geot_state,
        'geot_state_code'  : geot_state_code,
        'geot_city'  : geot_city,
        'geot_zip'  : geot_zip,
        },
        uniqueId = null,
        getUniqueName = function(prefix) {
            if (!uniqueId) uniqueId = (new Date()).getTime();
            return prefix + (uniqueId++);
        };
    $('.geot-ajax').each(function(){
        var _this = $(this);
        if( _this.hasClass('geot_menu_item') )
            _this = $(this).find('a').first();

        var uniqid = getUniqueName( 'geot' );
        _this.attr( 'id', uniqid );
        data.geots[uniqid] = {
            'action'    : _this.data('action') || '',
            'filter'    : _this.data('filter') || '',
            'region'    : _this.data('region') || '',
            'ex_filter' : _this.data('ex_filter') || '',
            'ex_region' : _this.data('ex_region') || '',
            'default'   : _this.data('default') || '',
            'locale'   : _this.data('locale') || 'en',
        }
    });
    var onSuccess = function( response ) {
        if( response.success ) {
            var results = response.data,
                i,
                remove  = response.posts.remove,
                hide    = response.posts.hide,
                debug   = response.debug;
            console.log(response);
            if( results && results.length ) {
                for (i = 0; i < results.length; ++i) {
                    if ( results[i].action == 'menu_filter' ) {
                        if( results[i].value == true )
                            $('#' + results[i].id).parent('.menu-item').remove();
                    } else if (results[i].action.indexOf('filter') > -1) {
                        if (results[i].value == true) {
                            var html = $('#' + results[i].id).html();
                            $('#' + results[i].id).replaceWith(html);
                        }
                        $('#' + results[i].id).remove();
                    } else {
                        $('#' + results[i].id).replaceWith(results[i].value);
                    }
                }
            }
            if( remove && remove.length ) {
                for (i = 0; i < remove.length; ++i) {
                    var id = remove[i];
                    $('#post-' + id + ', .post-' + id).remove();
                }
            }
            if( hide && hide.length ) {
                for (i = 0; i < hide.length; ++i) {
                    var id = hide[i].id;
                    $('#post-' + id + ' .entry-content, .post-' + id +' .entry-content').html( '<p>'+ hide[i].msg +'</p>' );
                }
            }
            if( debug && debug.length ) {
                $('#geot-debug-info').html(debug);
                $('.geot-debug-data').html(debug.replace(/<!--|-->/gi,''));
            }
        }
    }
    if( geot && geot.ajax )
        GeotRequest( data, onSuccess )

})( jQuery );
