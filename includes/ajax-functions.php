<?php
/**
 * Shows provided content only if the location
 * criteria are met.
 * ajax_geot_filter(array(country => 'US,CA'),'content');
 * ajax_geot_filter(array(region => 'my_region'),'content');
 *
 * @param $args
 * @param $content
 *
 * @return string
 */
function ajax_geot_filter( $args = array(), $content = '' ) {
	extract( wp_parse_args( $args, array(
			'country'			=>'',
			'region'			=>'',
			'exclude_country'	=>'',
			'exclude_region'	=>'',
			'html_tag'			=> 'div'
		) ) );

	return '<'.$html_tag.' class="geot-ajax geot-filter" data-action="country_filter" data-filter="'.$country.'" data-region="'.$region.'" data-ex_filter="'.$exclude_country.'" data-ex_region="'.$exclude_region.'">' . do_shortcode( $content ) . '</'.$html_tag.'>';
}

/**
 * Shows provided content only if the location
 * criteria are met.
 * ajax_geot_filter_city(array('city' => 'Miami, New York'), 'content');
 * ajax_geot_filter_city(array('region' => 'my_city_region'), 'content');
 *
 * @param $args
 * @param $content
 *
 * @return string
 */
function ajax_geot_filter_city( $args = array(), $content = '' ) {
	extract( wp_parse_args( $args, array(
			'city'				=>'',
			'region'			=>'',
			'exclude_city'		=>'',
			'exclude_region'	=>'',
			'html_tag'			=> 'div'
		) ) );

	return '<'.$html_tag.' class="geot-ajax geot-filter" data-action="city_filter" data-filter="'.$city.'" data-region="'.$region.'" data-ex_filter="'.$exclude_city.'" data-ex_region="'.$exclude_region.'">' . do_shortcode( $content ) . '</'.$html_tag.'>';
}

/**
 * Shows provided content only if the location
 * criteria are met.
 * ajax_geot_filter_state(array('state' => 'Florida'), 'content');
 *
 * @param $args
 * @param $content
 *
 * @return string
 */
function ajax_geot_filter_state( $args = array(), $content = '' ) {
	extract( wp_parse_args( $args, array(
			'state'			=>'',
			'exclude_state'	=>'',
			'html_tag'		=> 'div'
		) ) );

	return '<'.$html_tag.' class="geot-ajax geot-filter" data-action="state_filter" data-filter="'.$state.'" data-ex_filter="'.$exclude_state.'" >' . do_shortcode( $content ) . '</'.$html_tag.'>';
}

/**
 * Shows provided content only if the location
 * criteria are met.
 * ajax_geot_filter_zip(array('zip' => '1212'), 'content');
 *
 * @param $args
 * @param $content
 *
 * @return string
 */
function ajax_geot_filter_zip( $args = array(), $content = '' ) {
	extract( wp_parse_args( $args, array(
			'zip'			=>'',
			'exclude_zip'	=>'',
			'html_tag'		=> 'div'
		) ) );

	return '<'.$html_tag.' class="geot-ajax geot-filter" data-action="zip_filter" data-filter="'.$zip.'" data-ex_filter="'.$exclude_zip.'" >' . do_shortcode( $content ) . '</'.$html_tag.'>';
}

/** 
 * Displays the 2 character country for the current user
 *
 * @return  string country CODE
 **/
function ajax_geot_country_code($default = '', $html_tag = 'span') {

	return '<'. $html_tag .' class="geot-ajax" data-action="country_code" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
}

/** 
 * Displays the country name for the current user
 *
 * @return  string country name
 **/
function ajax_geot_country_name($default = '', $locale = 'en', $html_tag = 'span') {
	return '<'. $html_tag .' class="geot-ajax" data-locale="'.$locale.'" data-action="country_name" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
}

/**
 * Display the city name of current user
 *
 * @return string city name
 */
function ajax_geot_city_name($default = '', $locale = 'en', $html_tag = 'span') {
	return '<'. $html_tag .' class="geot-ajax" data-locale="'.$locale.'" data-action="city_name" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
}

/**
 * Display the State name of current user
 *
 * @return string city name
 */
function ajax_geot_state_name($default = '', $locale = 'en', $html_tag = 'span') {
	return '<'. $html_tag .' class="geot-ajax" data-locale="'.$locale.'" data-action="state_name" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
}

/**
 * Display the State code of current user
 *
 * @return string state code
 */
function ajax_geot_state_code($default = '', $html_tag = 'span') {
	return '<'. $html_tag .' class="geot-ajax" data-action="state_code" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
}

/**
 * Display the Continent of current user
 *
 * @return string continent name
 */
function ajax_geot_continent($default = '', $locale = 'en', $html_tag = 'span') {
	return '<'. $html_tag .' class="geot-ajax" data-locale="'.$locale.'" data-action="continent_name" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
}

/**
 * Display the Zip code of current user
 *
 * @return string city name
 */
function ajax_geot_zip($default = '', $html_tag = 'span') {
	return '<'. $html_tag .' class="geot-ajax" data-action="zip" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
}

/**
 * Display the Regions of current user
 *
 * @return string
 */
function ajax_geot_region($default = '', $html_tag = 'span') {
	return '<'. $html_tag .' class="geot-ajax" data-action="region" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
}

/**
 * Display the Timezone of current user
 *
 * @return string
 */
function ajax_geot_time_zone($default = '', $html_tag = 'span') {
	return '<'. $html_tag .' class="geot-ajax" data-action="time_zone" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
}

/**
 * Display the latitude of current user
 *
 * @return string
 */
function ajax_geot_lat($default = '', $html_tag = 'span') {
	return '<'. $html_tag .' class="geot-ajax" data-action="latitude" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
}

/**
 * Display the longitude of current user
 *
 * @return string
 */
function ajax_geot_lng($default = '', $html_tag = 'span') {
	return '<'. $html_tag .' class="geot-ajax" data-action="longitude" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
}