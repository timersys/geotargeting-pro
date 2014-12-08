<?php
/**
 * Wrapper functions for class
 * to be used anywhere
 * @since  1.0.0
 */

/**
 * Main function that return is current user target the given countries / regions or not
 * @param  string $country         
 * @param  string $region          
 * @param  string $exclude_country 
 * @param  string $exclude_region  
 * @return bool      
 */
 function geot_target( $country = '', $region = '', $exclude_country = '', $exclude_region  = '' ) {
 	global $geot;

 	return $geot->functions->target( $country, $region, $exclude_country, $exclude_region );
 }

/**
 * Get current user country
 * @return array Current user country array
 */
 function geot_user_country( ){
 	global $geot;

 	return $geot->get_user_country();
 }

/** 
 * Displays the 2 character country for the current user
 * [geot_country_code] 
 * @return  string country CODE
 **/
function geot_country_code( ) {
	
	$c = geot_user_country();

	return $c['maxmind_country_code'];
}

/** 
 * Displays the country name for the current user
 * [geot_country_name]
 * @return  string country name
 **/
function geot_country_name() {
	global $geot;

	$c = $geot->functions->get_user_country();

	return $c['maxmind_country'];
}


/** 
 * Gets User country by ip. Is not ip given current user country will show
 * 
 * @return  array()
 **/
function geot_country_by_ip( $ip = '') {
	global $geot;
	
	return $geot->functions->getCountryByIp( $ip );
	 
}


