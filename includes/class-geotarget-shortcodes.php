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
	 * @since   1.6
	 * @access  private
	 * @var     Array of plugin settings
	 */
	private $opts;
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
		$this->opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );
	}

	/**
	 * Register shortcodes
	 * @since 1.6
	 */
	public function register_shortcodes() {

		if( isset( $this->opts['ajax_mode'] ) && $this->opts['ajax_mode'] == '1' )
			return;

		add_shortcode('geot', array( $this, 'geot_filter') );
		add_shortcode('geot_city', array( $this, 'geot_filter_cities') );
		add_shortcode('geot_state', array( $this, 'geot_filter_states') );
		add_shortcode('geot_country_code', array( $this, 'geot_show_country_code') );
		add_shortcode('geot_country_name', array( $this, 'geot_show_country_name') );
		add_shortcode('geot_city_name', array( $this, 'geot_show_city_name') );
		add_shortcode('geot_state_name', array( $this, 'geot_show_state_name') );
		add_shortcode('geot_state_code', array( $this, 'geot_show_state_code') );
		add_shortcode('geot_zip', array( $this, 'geot_show_zip_code') );
		add_shortcode('geot_region', array( $this, 'geot_show_regions') );
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
			'ip' 				=> $this->functions->getUserIP(),
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
			'ip' 				=> $this->functions->getUserIP(),
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
			'ip' 				=> $this->functions->getUserIP(),
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

		if ( !empty( $c->names ) || !empty( $c->name ) )
			return apply_filters( 'geot/shortcodes/country_name', $c->name, $c );

		return  apply_filters( 'geot/shortcodes/country_name_default', $default );
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

		if ( !empty( $c ) )
			return apply_filters( 'geot/shortcodes/city_name', $c );

		return  apply_filters( 'geot/shortcodes/city_name_default', $default );

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

		if ( !empty( $state->names ) )
			return apply_filters( 'geot/shortcodes/state_name', $state->name, $state );

		return  apply_filters( 'geot/shortcodes/state_name_default', $default );
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

	/**
	 * Display the regions of current user
	 * [geot_region]
	 * @return string city name
	 */
	function geot_show_regions($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
		), $atts ) );

		$regions = geot_user_country_region( $default );

		if( is_array( $regions ) )
			return implode( ', ', $regions );

		return $regions;
	}

}	
