<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
* Elementor Extension
*
* @link       https://geotargetingwp.com/geotargeting-pro
* @since      1.6.3
*
* @package    GeoTarget
* @subpackage GeoTarget/includes
* @author     Damian Logghe
*/
class GeoTarget_Elementor {

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
		$this->opts = geot_settings();

	}


	/**
	* Put css in admin
	* @since    1.0.0
	*/
	public function enqueue_styles() {
		wp_enqueue_style( 'geo-elementor', GEOT_PLUGIN_URL . 'admin/css/admin.css', array(), $this->version );
	}


	/**
	* Register Tab
	* @since    1.0.0
	*/
	public function register_init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) )
			return;

		//Register Tab
		\Elementor\Controls_Manager::add_tab(
			'geo',
			__( 'Geotargeting', 'geot' )
		);
	}


	/**
	* Get Regions
	* @param	string 	$slug_region
	*/
	static function get_regions($slug_region = 'countries') {

		$dropdown_values = array();

		switch($slug_region) {
			case 'cities': $regions = geot_city_regions(); break;
			default: $regions = geot_country_regions();
		}
		
		if( !empty( $regions ) ) {
			foreach ( $regions as $r ) {
				if( isset( $r['name'] ) ) {
					$dropdown_values[$r['name']] = $r['name'];
				}
			}
		}

		return $dropdown_values;
	}


	/**
	* All Controls
	* @param	class 	$control
	* @param	string 	$section_id
	* @param	array 	$args
	*/
	public function register_controls($control, $section_id, $args) {

		if( $control->get_name() == 'section' && $section_id == 'section_custom_css_pro' ) {
			Elementor_GeoCountry::get_fields($control);
			Elementor_GeoCity::get_fields($control);
			Elementor_GeoState::get_fields($control);
		}
	}


	/**
	* Is Render in the front
	* @param	string 	$should_render
	* @element	class 	$element
	*/
	public function is_render($should_render, $element) {

		$settings = $element->get_settings_for_display();

		if( !Elementor_GeoCountry::is_render($settings) ||
			!Elementor_GeoCity::is_render($settings) ||
			!Elementor_GeoState::is_render($settings)
		) return false;

		return $should_render;
	}
}