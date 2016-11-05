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
	 */
	public function settings_page() {
		if( isset( $_GET['geot_db_update_finish'] ) && 'true' === $_GET['geot_db_update_finish'] )
			delete_option( 'geot_db_update', true);

		if( isset( $_GET['geot_db_update'] ) && 'true' === $_GET['geot_db_update'] )
			include plugin_dir_path( dirname( __FILE__ ) ) . '/partials/update-page.php';
		else
			include plugin_dir_path( dirname( __FILE__ ) ) . '/partials/settings-page.php';
	}
	/**
	 * [ip_test_page description]
	 * @return [type] [description]
	 */
	public function ip_test_page() {
		include plugin_dir_path( dirname( __FILE__ ) ) . '/partials/ip-test.php';
	}

}
