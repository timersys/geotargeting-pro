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
use GeoIp2\WebService\Client;

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
		if( !is_admin()
		    && ! defined('DOING_CRON')
		    && ! defined('DOING_AJAX') )
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
		if( $this->isSearchEngine() )
			return true;
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
		if( $this->isSearchEngine() )
			return true;

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

		// if user city is not defined return false
		if ( empty( $user_city ) )
			return apply_filters('geot/target_city/return_on_user_null', false);

		if ( count( $city ) > 0 ) {

			foreach ( $city as $c ) {

				if ( strtolower( $user_city ) == strtolower( $c ) ) {
					$target = true;
				}

			}
		} else {
			// If we don't have city to target return true
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
	 * Main function that return if user target the given state or not
	 *
	 * @param string $state
	 * @param string $exclude_state
	 *
	 * @return bool
	 *
	 */
	public function targetState( $state = '', $exclude_state = '' )
	{
		if( $this->isSearchEngine() )
			return true;

		//Push state list into array
		$state 			= $this->toArray( $state );

		$exclude_state 	= $this->toArray( $exclude_state );

		//set target to false
		$target = false;

		$user_state = $this->userState;
		// if user state is not defined return false
		if ( empty( $user_state->names ) )
			return apply_filters('geot/target_state/return_on_user_null', false);

		if ( count( $state ) > 0 ) {

			foreach ( $state as $c ) {

				if ( strtolower( $user_state->name ) == strtolower( $c ) || strtolower( $user_state->isoCode ) == strtolower( $c ) ) {
					$target = true;
				}

			}
		} else {
			// If we don't have states to target return true
			$target = true;

		}

		if ( count( $exclude_state ) > 0 ) {

			foreach ( $exclude_state as $c ) {

				if ( strtolower( $user_state->name ) == strtolower( $c ) || strtolower( $user_state->isoCode ) == strtolower( $c ) ) {
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

		$opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );
		// If user set cookie use instead
		if( !defined('GEOT_DEBUG') &&  ! empty( $_COOKIE['geot_country']) || ( !empty( $opts['cloudflare']) && !empty($_SERVER["HTTP_CF_IPCOUNTRY"]) ) ) {

			$iso_code = empty( $_COOKIE['geot_country'] ) ? $_SERVER["HTTP_CF_IPCOUNTRY"] : $_COOKIE['geot_country'];

			$country = $this->getCountryByIsoCode( $iso_code );

			return $country;
		}
		// if we have a session it means we already calculated country on session
		if( !defined('GEOT_DEBUG') && !empty($_SESSION['geot_country']) ) {
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
		if( !defined('GEOT_DEBUG') && !empty($_SESSION['geot_city']) ) {
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
		if( !defined('GEOT_DEBUG') && !empty($_SESSION['geot_state']) ) {
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
		if( !defined('GEOT_DEBUG') && !empty($_SESSION['geot_zip']) ) {
			return unserialize($_SESSION['geot_zip']);
		}

		$data = $this->getUserDataByIp();

		return $data['zip'];

	}

	/**
	 * Get Country by ip
	 *
	 * @param  string $ip
	 * @param bool $maxmin_free_db
	 *
	 * @return array country and city array
	 */
	public function getUserDataByIp( $ip = "", $maxmin_free_db = false ) {

		if( empty( $ip) ) {
			$ip = apply_filters( 'geot/user_ip', $_SERVER['REMOTE_ADDR']);		
		}
		$opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );


		try {
			if ( ! empty( $opts['maxm_id'] ) && ! empty( $opts['maxm_license'] ) && !$maxmin_free_db ) {
				$reader       = new Client( $opts['maxm_id'], $opts['maxm_license'] );
				$service_func = $opts['maxm_service'];
				if ( method_exists( $reader, $service_func ) ) {
					$record = $reader->$service_func( $ip );
				}
			} else {
				$reader = new Reader( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/data/GeoLite2-City.mmdb' );
				$record = $reader->city( $ip );
			}
		} catch( GeoIp2\Exception\OutOfQueriesException $e ) {
			Geotarget_Emails::OutOfQueriesException();
			// fallback to free version
			return $this->getUserDataByIp( $ip, true );
		} catch( GeoIp2\Exception\AuthenticationException $e ) {
			Geotarget_Emails::AuthenticationException();
			// fallback to free version
			return $this->getUserDataByIp( $ip, true );
		} catch( Exception $e ) {
			//for any other exception show fallback country
			return $this->getFallbackCountry();
		}
		$country    = $record->country;
		$city       = $record->city->name ;
		$cp         = $record->postal->code ;
		$state      = $record->mostSpecificSubdivision ;
		$continent  = $record->continent->name ;
		$location   = $record->location ;

		$_SESSION['geot_country']   = serialize($country);
		$_SESSION['geot_city']      = serialize($city);
		$_SESSION['geot_zip']       = serialize($cp);
		$_SESSION['geot_state']     = serialize($state);
		$_SESSION['geot_continent'] = serialize($continent);
		$_SESSION['geot_location']  = serialize($location);

		return array(
			'country'   => $country,
			'city'      => $city,
			'zip'       => $cp,
			'state'     => $state,
			'continent' => $continent,
			'location'  => $location,
		);

	}


	/**
	 * Get country from database and return object like maxmind
	 * @param $iso_code
	 *
	 * @return StdClass
	 */
	private function getCountryByIsoCode( $iso_code ) {
		global $wpdb;
		$query 	 = "SELECT * FROM {$wpdb->base_prefix}geot_countries WHERE iso_code = %s";
		$result = $wpdb->get_row( $wpdb->prepare($query, array( $iso_code )), ARRAY_A );
		$country = new StdClass;

		$country->name      = $result['country'];
		$country->isoCode   = $result['iso_code'];

		return $country;
	}

	/**
	 * If we have a maxmind exception, return
	 * @return array|bool
	 */
	private function getFallbackCountry() {
		$opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );

		if( !empty($opts['fallback_country'])) {
			return array(
				'country' => $this->getCountryByIsoCode($opts['fallback_country']),
				'city'    => '',
				'zip'     => '',
				'state'   => '',
			);
		}
		return false;
	}

	/**
	 * Check if "user" is a search engine
	 */
	private function isSearchEngine() {
		$referrer = isset($_SERVER['HTTP_REFERRER']) ? $_SERVER['HTTP_REFERRER'] : '';

		$SE = apply_filters( 'geot/search_engines', array('/search?', '.google.', 'web.info.com', 'search.', 'del.icio.us/search', 'soso.com', '/search/', '.yahoo.', '.bing.' ) );

		foreach ($SE as $url) {
			if ( strpos( $referrer,$url ) !== false )
				return  true;
		}

		return false;
	}

}	
