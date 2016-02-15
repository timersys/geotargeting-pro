<?php
/**
 * Wrapper functions for class
 * to be used anywhere
 * @since  1.0.0
 */

/**
 * Main function that return is current user target the given countries / regions or not
 * Originally was to target also cities so I left that just in case but now we use geot_target_city
 * @param string $what what to target countries|cities
 * @param string $object single country/city or comma separated list
 * @param string $object_region
 * @param string $exclude
 * @param  string $exclude_region
 *
 * @return bool
 */
 function geot_target( $object = '', $object_region = '', $exclude = '', $exclude_region  = '', $what = 'countries' ) {
	 global $geot;

	 if( 'countries' == $what )
 	    return $geot->functions->targetCountry( $object, $object_region, $exclude, $exclude_region );

	 return $geot->functions->targetCity( $object, $object_region, $exclude, $exclude_region );

 }

/**
 * Main function that return is current user target the given city / regions or not
 *
 * @param string $city single city or comma list of cities
 * @param string $city_region
 * @param string $exclude
 * @param  string $exclude_region
 *
 * @return bool
 */
 function geot_target_city( $city = '', $city_region = '', $exclude = '', $exclude_region  = '') {
	 global $geot;

	 return $geot->functions->targetCity( $city, $city_region, $exclude, $exclude_region );

 }

/**
 * Main function that return is current user target the given state or not
 *
 * @param string $state single state or comma separated list of states
 * @param string $exclude
 *
 * @return bool
 */
 function geot_target_state( $state = '', $exclude = '') {
	 global $geot;

	 return $geot->functions->targetState( $state, $exclude );

 }

/**
 * Get current user country
 * @return object Current user country. Values are $country->isoCode $country->country
 */
 function geot_user_country( ){
 	global $geot;

 	return $geot->functions->get_user_country();
 }

/** 
 * Displays the 2 character country for the current user
 * [geot_country_code] 
 * @return  string country CODE
 **/
function geot_country_code( ) {
	
	$c = geot_user_country();

	return $c->isoCode;
}

/** 
 * Displays the country name for the current user
 * [geot_country_name]
 * @return  string country name
 **/
function geot_country_name() {
	global $geot;

	$c = $geot->functions->get_user_country();

	return $c->country;
}


/**
 * Display the user city name
 * [geot_city_name]
 * @return string
 */
function geot_city_name() {
	global $geot;

	$c = $geot->functions->get_user_city();

	return $c;
}
/**
 * Displays the zip code
 * [geot_zip]
 * @return  string zip code
 **/
function geot_zip() {
	global $geot;

	$zip = $geot->functions->get_user_zip();

	return $zip;
}


/**
 * Gets User country by ip. Is not ip given current user country will show
 *
 * @param string $ip
 *
 * @return object Current user country. Values are $country->isoCode $country->name
 */
function geot_country_by_ip( $ip = '') {
	global $geot;
	
	$data = $geot->functions->getUserDataByIp( $ip );

	return $data['country'];
}

/**
 * Grabs the whole record from Maxmind Database
 *
 * @param string $ip
 *
 * @return object
 */
function geot_data( $ip = '') {
	global $geot;

	$data = $geot->functions->getUserDataByIp( $ip );

	return $data['record'];
}
/**
 * Gets user lat / long
 *
 * @param string $ip
 *
 * @return object ->longitude , ->latitude, ->time_zone
 */
function geot_location( $ip = '') {
	global $geot;

	$data = $geot->functions->getUserDataByIp( $ip );
	if( empty( $data['record']->location ) )
		return;

	return $data['record']->location;
}



/**
 * Gets User state by ip. Is not ip given current user country will show
 *
 * @param string $ip
 *
 * @return object Current user state. Values are $state->isoCode $state->name
 */
function geot_state_by_ip( $ip = '') {
	global $geot;

	$data = $geot->functions->getUserDataByIp( $ip );

	return $data['state'];
}


/**
 * Get cities in database
 *
 * @param string $country
 *
 * @return object cities names with country codes
 */
function geot_get_cities( $country = 'US')	{

	$cities = wp_cache_get( 'geot_cities'.$country);

	if( false === $cities ) {
		global $wpdb;
		$cities = $wpdb->get_results( $wpdb->prepare( "SELECT country_code, city FROM {$wpdb->base_prefix}geot_cities WHERE country_code = %s ORDER BY city ", array($country)));

		wp_cache_set( 'geot_cities'.$country, $cities);
	}

	return $cities;

}