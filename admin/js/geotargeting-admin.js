(function( $ ) {
	'use strict';

	$('document').ready( function() {
		$(".geot-chosen-select").chosen({width:"90%",no_results_text: "Oops, nothing found!"}); 

		$(".add-region").click( function(e){
			e.preventDefault();
			var region 		= $(this).prev('.region-group');
			var new_region 	= region.clone();
			var new_id		= parseInt( region.data('id') ) + 1;

			new_region.find('input[type="text"]').attr('name', 'geot_settings[region]['+new_id+'][name]').val('');
			new_region.find('select').attr('name', 'geot_settings[region]['+new_id+'][countries][]').find("option:selected").removeAttr("selected");
			new_region.find('.chosen-container').remove();
			new_region.insertAfter(region);
			$(".geot-chosen-select").chosen({width:"90%",no_results_text: "Oops, nothing found!"});
		});

		$(".geot-settings").on('click','.remove-region', function(e){
			e.preventDefault();
			var region 		= $(this).parent('.region-group');
			region.remove();
		});

		$(document).on('widget-updated', function(){
			
			$(".geot-chosen-select").chosen({width:"90%",no_results_text: "Oops, nothing found!"}); 
							
		});

		$(document).on('widget-added', function(ev, target){

			$(target).find('.chosen-container').remove();
			$(target).find(".geot-chosen-select").show().chosen({width:"90%",no_results_text: "Oops, nothing found!"}); 
							
		});
	});
 
})( jQuery );

