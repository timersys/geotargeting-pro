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
use GeoIp2\Database\Reader;
use GeoIp2\Database\Client;

class GeoTarget_Functions {

	/**
	 * Current user country and cityused everywhere
	 * @var string
	 */
	protected  $userCountry;
	protected $userCity;
	protected $userState;
	protected $userZip;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( ) {
		if( !is_admin() )
			add_action('init' , array($this,'setUserData' ) );
	}

	/**
	 * Run after init to be sure db is ready
	 * @since  1.0.1
	 */
	function setUserData() {
		$this->userCountry  = apply_filters('geot/user_country', $this->calculateUserCountry());
		$this->userCity     = apply_filters('geot/user_city',    $this->calculateUserCity());
		$this->userState    = apply_filters('geot/user_state',   $this->calculateUserState());
		$this->userZip      = apply_filters('geot/user_zip',     $this->calculateUserZip());
	}

	/**
	 * Main function that return is user target the given countries / regions or not
	 * @param  string $country         
	 * @param  string $region          
	 * @param  string $exclude_country 
	 * @param  string $exclude_region  
	 * @return bool      
	 */
	public function targetCountry( $country = '', $region = '', $exclude_country = '', $exclude_region  = '')
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

				if ( strtolower( $user_country->name ) == strtolower( $c )|| strtolower( $user_country->isoCode ) == strtolower( $c ) ) {
					$target = true;
				}

			}
		} else {
			// If we don't have countries to target return true
			$target = true;

		}
		
		if ( count( $exclude_country ) > 0 ) {

			foreach ( $exclude_country as $c ) {

				if ( strtolower( $user_country->name ) == strtolower( $c ) || strtolower( $user_country->isoCode ) == strtolower( $c ) ) {
					$target = false;
				}

			}
		}	
		

		return $target;
	}

	/**
	 * Main function that return is user target the given cities / regions or not
	 *
	 * @param string $city
	 * @param  string $region
	 * @param string $exclude_city
	 * @param  string $exclude_region
	 *
	 * @return bool
	 */
	public function targetCity( $city = '', $region = '', $exclude_city = '', $exclude_region  = '')
	{

		//Push city list into array
		$city 			= $this->toArray( $city );

		$exclude_city 	= $this->toArray( $exclude_city );

		$saved_regions 	= apply_filters('geot/get_city_regions', array());

		//Append any regions
		if ( !empty( $region ) && ! empty( $saved_regions ) ) {

			$region = $this->toArray( $region );

			foreach ($region as $region_name) {

				foreach ($saved_regions as $key => $saved_region) {

					if ( strtolower( $region_name ) == strtolower( $saved_region['name'] ) ) {

						$city = array_merge( (array)$city, (array)$saved_region['cities']);

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

						$exclude_city = array_merge((array)$exclude_city, (array)$saved_region['cities']);

					}
				}
			}
		}

		//set target to false
		$target = false;

		$user_city = $this->userCity;

		
		if ( count( $city ) > 0 ) {

			foreach ( $city as $c ) {

				if ( strtolower( $user_city ) == strtolower( $c ) ) {
					$target = true;
				}

			}
		} else {
			// If we don't have countries to target return true
			$target = true;

		}

		if ( count( $exclude_city ) > 0 ) {

			foreach ( $exclude_city as $c ) {

				if ( strtolower( $user_city ) == strtolower( $c ) ) {
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
		if( empty( $this->userCountry ) ) {
			$this->userCountry = $this->calculateUserCountry();
		}

		return $this->userCountry;
	}

	/**
	 * Retrieve the current User city
	 * @return array City array object
	 */
	public function get_user_city()
	{
		if( empty( $this->userCity ) ) {
			$this->userCity = $this->calculateUserCity();
		}

		return $this->userCity;
	}

	/**
	 * Retrieve the current User State
	 * @return array City array object
	 */
	public function get_user_state()
	{
		if( empty( $this->userState ) ) {
			$this->userState = $this->calculateUserState();
		}

		return $this->userState;
	}

	/**
	 * Retrieve the current User Zip
	 * @return array City array object
	 */
	public function get_user_zip()
	{
		if( empty( $this->userZip ) ) {
			$this->userZip = $this->calculateUserZip();
		}

		return $this->userZip;
	}

	/**
	 * Get user Country
	 * @return array     country array
	 */
	public function calculateUserCountry() {
		
		global $wpdb;
		
		// If user set cookie use instead
		if( ! empty( $_COOKIE['geot_country']) ) {

			$query 	 = "SELECT * FROM {$wpdb->prefix}geot_countries WHERE iso_code = %s";
	
			$result = $wpdb->get_row( $wpdb->prepare($query, array($_COOKIE['geot_country'])), ARRAY_A );
			$country = new StdClass;

			$country->name      = $result['country'];
			$country->isoCode   = $result['iso_code'];

			return $country;
		}
		// if we have a session it means we already calculated country on session
		if( !empty($_SESSION['geot_country']) ) {
			return unserialize($_SESSION['geot_country']);
		}

		$data = $this->getUserDataByIp();

		return $data['country'];

	}

	/**
	 * Get user City
	 * @return array     country array
	 */
	public function calculateUserCity() {


		// if we have a session it means we already calculated city on session
		if( !empty($_SESSION['geot_city']) ) {
			return unserialize($_SESSION['geot_city']);
		}

		$data = $this->getUserDataByIp();

		return $data['city'];

	}

	/**
	 * Get user State
	 * @return object state->name state->isoCode
	 */
	public function calculateUserState() {


		// if we have a session it means we already calculated city on session
		if( !empty($_SESSION['geot_state']) ) {
			return unserialize($_SESSION['geot_state']);
		}

		$data = $this->getUserDataByIp();

		return $data['state'];

	}

	/**
	 * Get user Zip
	 * @return string   zip code
	 */
	public function calculateUserZip() {


		// if we have a session it means we already calculated city on session
		if( !empty($_SESSION['geot_zip']) ) {
			return unserialize($_SESSION['geot_zip']);
		}

		$data = $this->getUserDataByIp();

		return $data['zip'];

	}

	/**
	 * Get Country by ip
	 * @param  string $ip 
	 * @return array     country and city array
	 */
	public function getUserDataByIp( $ip = "" ) {

		if( empty( $ip) ) {
			$ip = apply_filters( 'geot/user_ip', $_SERVER['REMOTE_ADDR']);		
		}
		$opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );

		if( !empty($opts['maxm_id']) && !empty($opts['maxm_license']) ) {
			$reader = new Client($opts['maxm_id'], $opts['maxm_license']);
		} else {
			$reader = new Reader(plugin_dir_path( dirname( __FILE__ ) ) . 'includes/data/GeoLite2-City.mmdb');
		}

		$record = $reader->city($ip);

		$country = $record->country;
		$city    = $record->city->name;
		$cp      = $record->postal->code;
		$state   = $record->mostSpecificSubdivision;

		$_SESSION['geot_country']   = serialize($country);
		$_SESSION['geot_city']      = serialize($city);
		$_SESSION['geot_zip']        = serialize($cp);
		$_SESSION['geot_state']     = serialize($state);

		return array(
			'country' => $country,
			'city'    => $city,
			'zip'     => $cp,
			'state'   => $state,
		);

	}

}	
