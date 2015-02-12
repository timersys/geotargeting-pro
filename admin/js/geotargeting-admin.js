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

		$(".add-city-region").click( function(e){
			e.preventDefault();
			var region 		= $(this).prev('.city-region-group');
			var new_region 	= region.clone();
			var cities = new_region.find(".cities_container");
			var chosen = new_region.find(".country_ajax");

			var new_id		= parseInt( region.data('id') ) + 1;
			new_region.find('input[type="text"]').attr('name', 'geot_settings[city_region]['+new_id+'][name]').val('');
			chosen.attr('name', 'geot_settings[city_region]['+new_id+'][countries][]').find("option:selected").removeAttr("selected");
			cities.attr('name', 'geot_settings[city_region]['+new_id+'][cities][]').find("option:selected").removeAttr("selected");
			new_region.find('.chosen-container').remove();
			new_region.insertAfter(region);
			chosen.attr('data-counter', new_id);
			cities.attr('id', 'cities'+new_id);
			cities.chosen({width:"90%",no_results_text: "Oops, nothing found!"});
			chosen.chosen({width:"90%",no_results_text: "Oops, nothing found!"}).on('change', function(){
				load_cities(chosen);
			});
		});

		$(".geot-settings").on('click','.remove-city-region', function(e){
			e.preventDefault();
			var region 		= $(this).parent('.city-region-group');
			region.remove();
		});

		$(".country_ajax").on('change', function(){
			load_cities($(this));
		});

		function load_cities( o ) {
			var counter 		= o.data('counter');
			var cities_select 	= $("#cities"+counter);
			var cities_choosen  = cities_select.next('.chosen-container');
			$.post(
				geot.ajax_url,
				{ action: 'geot_cities_by_country', country : o.val() },
				function(response) {
					//cities_choosen.remove();
					cities_select.html(response);
					cities_select.trigger("chosen:updated");
				}
			);
		}

		$(document).on('widget-updated', function(){
			
			$(".geot-chosen-select").chosen({width:"90%",no_results_text: "Oops, nothing found!"}); 
							
		});

		$(document).on('widget-added', function(ev, target){

			$(target).find('.chosen-container').remove();
			$(target).find(".geot-chosen-select").show().chosen({width:"90%",no_results_text: "Oops, nothing found!"}); 
							
		});
	});
 
})( jQuery );

