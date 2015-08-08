<?php
/**
 * Shortcodes  functions
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Shortcodes {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $GeoTarget    The ID of this plugin.
	 */
	private $GeoTarget;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin functions
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    Plugin functions
	 */
	private $functions;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $GeoTarget       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 * @var      class    instance of GeotFunctions
	 */
	public function __construct( $GeoTarget, $version, $functions ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;
		$this->functions = $functions;
	}


	/**
	 * Shows provided content only if the location
	 * criteria are met.
	 * [geot country="US,CA"]content[/geot]
	 * [geot region="my_region"]content[/geot]
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	function geot_filter($atts, $content)
	{
		extract( shortcode_atts( array(
			'ip' 				=> $_SERVER['REMOTE_ADDR'],
			'country'			=>'',
			'region'			=>'',
			'exclude_country'	=>'',
			'exclude_region'	=>''
		), $atts ) );
		
				
		if ( $this->functions->targetCountry( $country, $region, $exclude_country, $exclude_region ) )
			return do_shortcode( $content );
			
		return '';
	}

	/**
	 * Shows provided content only if the location
	 * criteria are met.
	 * [geot_city city="Miami, New York"]content[/geot_city]
	 * [geot_city region="my_city_region"]content[/geot_city]
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	function geot_filter_cities($atts, $content)
	{
		extract( shortcode_atts( array(
			'ip' 				=> $_SERVER['REMOTE_ADDR'],
			'city'			    =>'',
			'region'			=>'',
			'exclude_city'	    =>'',
			'exclude_region'	=>''
		), $atts ) );


		if ( $this->functions->targetCity( $city, $region, $exclude_city, $exclude_region ) )
			return do_shortcode( $content );

		return '';
	}

	/**
	 * Shows provided content only if the location
	 * criteria are met.
	 * [geot_state state="Florida"]content[/geot_state]
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	function geot_filter_states($atts, $content)
	{
		extract( shortcode_atts( array(
			'ip' 				=> $_SERVER['REMOTE_ADDR'],
			'state'			    =>'',
			'exclude_state'	    =>'',
		), $atts ) );


		if ( $this->functions->targetState( $state, $exclude_state ) )
			return do_shortcode( $content );

		return '';
	}


	/** 
	 * Displays the 2 character country for the current user
	 * [geot_country_code]   [geot_country_code]
	 * @return  string country CODE
	 **/
	function geot_show_country_code($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
		), $atts ) );

		$c = $this->functions->get_user_country();

		return !empty($c->isoCode) ? $c->isoCode : $default;
	}


	/** 
	 * Displays the country name for the current user
	 * [geot_country_name]
	 * @return  string country name
	 **/
	function geot_show_country_name($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
		), $atts ) );

		$c = $this->functions->get_user_country();

		return !empty( $c->name ) ? $c->name : $default;
	}

	/**
	 * Display the city name of current user
	 * [geot_city_name]
	 * @return string city name
	 */
	function geot_show_city_name($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
		), $atts ) );

		$c = $this->functions->get_user_city();
		return !empty( $c ) ? $c : $default;
	}

	/**
	 * Display the State name of current user
	 * [geot_state]
	 * @return string city name
	 */
	function geot_show_state_name($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
		), $atts ) );

		$state = $this->functions->get_user_state();

		return !empty( $state->names ) ? $state->name : $default;
	}

	/**
	 * Display the State code of current user
	 * [geot_state_code]
	 * @return string city name
	 */
	function geot_show_state_code($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
		), $atts ) );

		$state = $this->functions->get_user_state();
		return !empty( $state->isoCode ) ? $state->isoCode : $default;
	}

	/**
	 * Display the Zip code of current user
	 * [geot_zip]
	 * @return string city name
	 */
	function geot_show_zip_code($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
		), $atts ) );

		$zip = $this->functions->get_user_zip();
		return !empty($zip) ? $zip : $default;
	}

}	
