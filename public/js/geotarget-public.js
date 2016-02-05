(function( $ ) {
	'use strict';

	  $(document).ready(function() {




          $('#geot_dropdown').ddslick({
			    onSelected: function(data){
			    	var country_code = data.selectedData.value;
			        GeotCreateCookie('geot_country', country_code,999);
			        window.location.reload();
			    }
			});
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

    if( $('.geot-ajax').length ) {
        var data = {
            'action' : 'geot_ajax',
            'geots'  : {}
            },
            uniqueId = null,
            getUniqueName = function(prefix) {
                if (!uniqueId) uniqueId = (new Date()).getTime();
                return prefix + (uniqueId++);
            };
        $('.geot-ajax').each(function(){

            var uniqid = getUniqueName( 'geot' );
            $(this).attr( 'id', uniqid );
            data.geots[uniqid] = {
                'action'    : $(this).data('action') || '',
                'filter'    : $(this).data('filter') || '',
                'region'    : $(this).data('region') || '',
                'ex_filter' : $(this).data('ex_filter') || '',
                'ex_region' : $(this).data('ex_region') || '',
            }
        });
        var onSuccess = function( response ) {
            if( response.success ) {
                var results = response.data;
                var i; console.log(results);
                for( i = 0; i < results.length ; ++i ){
                    if( results[i].action.indexOf('filter') > -1 ) {
                        if( results[i].value == true ) {
                            var html = $('#' + results[i].id).html();
                            $('#' + results[i].id).replaceWith( html );
                        }
                        $('#' + results[i].id).remove();
                    } else {
                        $('#' + results[i].id).replaceWith(results[i].value);
                    }
                }
                //$('.geot-country-name').each(function(){
                //    var geo_data = $(this).data('default');
                //    if( response.data.length )
                //        geo_data = response.data;
                //    $(this).replaceWith( geo_data );
                //});
            }
        }
        GeotRequest( data, onSuccess )
    }
})( jQuery );
