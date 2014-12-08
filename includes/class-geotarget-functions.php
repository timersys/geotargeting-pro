<?php
/**
 * Functions for geotargeting
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Functions {

	/**
	 * Current user country used everywhere
	 * @var string
	 */
	protected  $userCountry;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( ) {

		$this->userCountry = $this->calculateUserCountry();

	}

	/**
	 * Main function that return is user target the given countries / regions or not
	 * @param  string $country         
	 * @param  string $region          
	 * @param  string $exclude_country 
	 * @param  string $exclude_region  
	 * @return bool      
	 */
	public function target( $country = '', $region = '', $exclude_country = '', $exclude_region  = '')
	{

		//Push country list into array
		$country 			= $this->toArray( $country );
		
		$exclude_country 	= $this->toArray( $exclude_country );
				
		$saved_regions 		= apply_filters('geot/get_regions', array());

		//Append any regions
		if ( !empty( $region ) && ! empty( $saved_regions ) ) {
			
			$region = $this->toArray( $region );	
				
			foreach ($region as $region_name) {
				
				foreach ($saved_regions as $key => $saved_region) {
				
					if ( strtolower( $region_name ) == strtolower( $saved_region['name'] ) ) {
					
						$country = array_merge( (array)$country, (array)$saved_region['countries']);
					
					}
				}
			}

		}	
		// append exlcluded regions to excluded countries		
		if (!empty( $exclude_region ) && ! empty( $saved_regions ) ) {

			$exclude_region = $this->toArray( $exclude_region );
			
			foreach ($exclude_region as $region_name ) {

				foreach ($saved_regions as $key => $saved_region) {
				
					if ( strtolower( $region_name ) == strtolower( $saved_region['name'] ) ) {

						$exclude_country = array_merge((array)$exclude_country, (array)$saved_region['countries']);

					}
				}	
			}
		}	
			
		//set target to false	
		$target = false;
			
		$user_country = $this->userCountry;


			
		if ( count( $country ) > 0 ) {

			foreach ( $country as $c ) {

				if ( strtolower( $user_country['maxmind_country'] ) == strtolower( $c )|| strtolower( $user_country['maxmind_country_code'] ) == strtolower( $c ) ) {
					$target = true;
				}

			}
		} else {
			// If we don't have countries to target return true
			$target = true;

		}
		
		if ( count( $exclude_country ) > 0 ) {

			foreach ( $exclude_country as $c ) {

				if ( strtolower( $user_country['maxmind_country'] ) == strtolower( $c ) || strtolower( $user_country['maxmind_country_code'] ) == strtolower( $c ) ) {
					$target = false;
				}

			}
		}	
		

		return $target;
	}

	/**
	 * Helper function to conver to array
	 * @param  string $value comma separated countries, etc
	 * @return array  
	 */
	private function toArray( $value = "" )
	{
		if ( empty( $value ) )
			return array();
		
		if ( is_array( $value ) )
			return $value;
	
		if ( stripos($value, ',') > 0)
			return explode( ',',$value );
	
		return array( $value );
	}


	/**
	 * Retrieve the current User country
	 * @return array Country array object
	 */
	public function get_user_country()
	{	
		return $this->userCountry;
	}

	/**
	 * Get user Country
	 * @param  string $ip 
	 * @return array     country array
	 */
	public function calculateUserCountry() {
		
		global $wpdb;
		

		// If user set cookie use instead
		if( ! empty( $_COOKIE['geot_country']) ) {

			$query 	 = "SELECT * FROM {$wpdb->prefix}Maxmind_geoIP WHERE maxmind_country_code = %s";
	
			$country = $wpdb->get_row( $wpdb->prepare($query, array($_COOKIE['geot_country'])), ARRAY_A );

			return $country;
		}

		$country = $this->getCountryByIp();

		return $country;

	}

	/**
	 * Get Country by ip
	 * @param  string $ip 
	 * @return array     country array
	 */
	public function getCountryByIp( $ip = "" ) {
		
		global $wpdb;
		
		if( empty( $ip) ) {
			$ip = $_SERVER['REMOTE_ADDR'];		
		}

		$query 	 = "SELECT * FROM {$wpdb->prefix}Maxmind_geoIP WHERE INET_ATON('" . ($ip) . "') BETWEEN maxmind_locid_start AND maxmind_locid_end";
		$country = $wpdb->get_row( $query, ARRAY_A );

		return $country;

	}

}	
