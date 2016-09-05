<?php
/**
 * Shortcodes  functions for AJAX mode
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Ajax_Shortcodes {
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
	 * @since   1.6
	 * @access  private
	 * @var     Array of plugin settings
	 */
	private $opts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $GeoTarget       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $GeoTarget, $version ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;
		$this->opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );
	}

	/**
	 * Register shortcodes
	 * @since 1.6
	 */
	public function register_shortcodes() {

		if( ! isset( $this->opts['ajax_mode'] ) || $this->opts['ajax_mode'] != '1' )
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
			'country'			=>'',
			'region'			=>'',
			'exclude_country'	=>'',
			'exclude_region'	=>'',
			'html_tag'          => 'div'
		), $atts ) );
		
		return '<'.$html_tag.' class="geot-ajax geot-filter" data-action="country_filter" data-filter="'.$country.'" data-region="'.$region.'" data-ex_filter="'.$exclude_country.'" data-ex_region="'.$exclude_region.'">' . do_shortcode( $content ) . '</'.$html_tag.'>';

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
			'city'			    =>'',
			'region'			=>'',
			'exclude_city'	    =>'',
			'exclude_region'	=>'',
			'html_tag'          => 'div'
		), $atts ) );

		return '<'.$html_tag.' class="geot-ajax geot-filter" data-action="city_filter" data-filter="'.$city.'" data-region="'.$region.'" data-ex_filter="'.$exclude_city.'" data-ex_region="'.$exclude_region.'">' . do_shortcode( $content ) . '</'.$html_tag.'>';

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
			'state'			    =>'',
			'exclude_state'	    =>'',
			'html_tag'          => 'div'
		), $atts ) );

		return '<'.$html_tag.' class="geot-ajax geot-filter" data-action="state_filter" data-filter="'.$state.'" data-ex_filter="'.$exclude_state.'" >' . do_shortcode( $content ) . '</'.$html_tag.'>';

	}


	/** 
	 * Displays the 2 character country for the current user
	 * [geot_country_code]   [geot_country_code]
	 * @return  string country CODE
	 **/
	function geot_show_country_code($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
			'html_tag'          => 'span'
		), $atts ) );

		return '<'. $html_tag .' class="geot-ajax" data-action="country_code" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
	}


	/** 
	 * Displays the country name for the current user
	 * [geot_country_name]
	 * @return  string country name
	 **/
	function geot_show_country_name($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
			'html_tag'          => 'span'
		), $atts ) );

		return '<'. $html_tag .' class="geot-ajax" data-action="country_name" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';

	}

	/**
	 * Display the city name of current user
	 * [geot_city_name]
	 * @return string city name
	 */
	function geot_show_city_name($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
			'html_tag'          => 'span'
		), $atts ) );

		return '<'. $html_tag .' class="geot-ajax" data-action="city_name" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';

	}

	/**
	 * Display the State name of current user
	 * [geot_state]
	 * @return string city name
	 */
	function geot_show_state_name($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
			'html_tag'          => 'span'
		), $atts ) );

		return '<'. $html_tag .' class="geot-ajax" data-action="state_name" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
	}

	/**
	 * Display the State code of current user
	 * [geot_state_code]
	 * @return string city name
	 */
	function geot_show_state_code($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
			'html_tag'          => 'span'
		), $atts ) );

		return '<'. $html_tag .' class="geot-ajax" data-action="state_code" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
	}

	/**
	 * Display the Zip code of current user
	 * [geot_zip]
	 * @return string city name
	 */
	function geot_show_zip_code($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
			'html_tag'          => 'span'
		), $atts ) );

		return '<'. $html_tag .' class="geot-ajax" data-action="zip" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
	}

	/**
	 * Display the Regions of current user
	 * [geot_region]
	 * @return string regions names
	 */
	function geot_show_regions($atts) {
		extract( shortcode_atts( array(
			'default' 			=> '',
			'html_tag'          => 'span'
		), $atts ) );

		return '<'. $html_tag .' class="geot-ajax" data-action="region" data-default="' . do_shortcode( $default ). '"></'. $html_tag .'>';
	}

}	
