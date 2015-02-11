<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/admin/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Settings {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $GeoTarget       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $GeoTarget, $version ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;

	}

	/**
	 * Settings page for plugin
	 * @return [type] [description]
	 */
	public function settings_page()
	{
		if (  isset( $_POST['geot_nonce'] ) && wp_verify_nonce( $_POST['geot_nonce'], 'geot_save_settings' ) ) {


			update_option( 'geot_settings' , esc_sql( $_POST['geot_settings'] ) );
		
		}	
		
		$opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );
		
		// initialize
		if( ! is_array( @$opts['region'] ) ) {
			$opts['region'][] = array( 'name' , 'countries' );
		}
		if( ! is_array( @$opts['city_region'] ) ) {
			$opts['city_region'][] = array( 'name' , 'cities' );
		}


		$countries 	= apply_filters('geot/get_countries', array());

		include plugin_dir_path( dirname( __FILE__ ) ) . '/partials/settings-page.php';
	}

	
}
