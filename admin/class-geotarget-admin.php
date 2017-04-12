<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/admin
 */
use GeotWP\GeotargetingWP;
use GeotFunctions\GeotUpdates;


/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/admin
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Admin {

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
		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for admin area.
	 *
	 *
	 * - GeoTarget_Settings. Settings page and functions
	 *
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/class-geotarget-admin-settings.php';
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $pagenow;

		if( 'post.php' == $pagenow ) {
   			wp_enqueue_style('wp-jquery-ui-dialog');
   		}
		wp_enqueue_style( 'geot-chosen', plugin_dir_url( __FILE__ ) . 'css/chosen.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->GeoTarget, plugin_dir_url( __FILE__ ) . 'css/geotarget.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'geot-chosen', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->GeoTarget, plugin_dir_url( __FILE__ ) . 'js/geotargeting-admin.js', array( 'jquery','geot-chosen','jquery-ui-dialog'), $this->version, false );
		wp_localize_script(  $this->GeoTarget, 'geot', array(
			'ajax_url'  => admin_url('admin-ajax.php')
		));
	}

	/**
	 * Register the metaboxes on all posts types
	 */
	public function add_meta_boxes()
	{

		$post_types = apply_filters( 'geot/get_post_types', Geot_Helpers::get_post_types() );

		foreach ($post_types as $cpt) {

			add_meta_box(
				'geot-settings',
				__( 'GeoTargeting Options', 'geot' ),
				array( $this, 'geot_options_view' ),
				$cpt,
				'normal',
				'core'
			);

		}

	}

	/**
	 * Display the view for Geot metabox options
	 * @return mixed
	 */
	public function geot_options_view( $post, $metabox )
	{
		$opts 		= apply_filters('geot/metaboxes/get_cpt_options', Geot_Helpers::get_cpt_options( $post->ID ), $post->ID );
		$countries 	= geot_countries();
		$regions 	= geot_country_regions();

		if( !isset( $opts['forbidden_text'] ) )
			$opts['forbidden_text'] = __( 'This content is restricted in your region', $this->GeoTarget);

		if( !isset( $opts['geot_remove_post'] ) )
			$opts['geot_remove_post'] = '';

		if( !isset( $opts['geot_include_mode'] ) )
			$opts['geot_include_mode'] = 'include';


		include 'partials/metabox-options.php';
	}

	/**
	 * Add menu for Settings page of the plugin
	 * @since  1.0.0
	 * @return  void
	 */
	public function add_settings_menu() {

		$settings = new GeoTarget_Settings( $this->GeoTarget, $this->version );

		add_menu_page('GeoTargeting', 'GeoTargeting', 'manage_options', 'geot-settings', array($settings, 'settings_page'), 'dashicons-share-alt' );
		add_submenu_page( 'geot-settings', 'Settings', 'Settings', 'manage_options', 'geot-settings',array($settings, 'settings_page') );
		add_submenu_page( 'geot-settings', 'Ip test', 'Ip test', 'manage_options', 'geot-ip-test',array($settings, 'ip_test_page') );
	}

	/**
	 * Save the settings page
	 * @since 1.9.2
	 * @return void
	 */
	public function save_settings(){
		if (  isset( $_POST['geot_nonce'] ) && wp_verify_nonce( $_POST['geot_nonce'], 'geot_save_settings' ) ) {
			$settings = esc_sql( $_POST['geot_settings'] );
			if( isset($_FILES['geot_settings_json']) && 'application/json' == $_FILES['geot_settings_json']['type'] ) {
				$file = file_get_contents($_FILES['geot_settings_json']['tmp_name']);
				$settings = json_decode($file,true);

			}
			update_option( 'geot_settings' ,  $settings);

		}
	}
	/**
	 * Saves popup options and rules
	 *
	 * @param $post_id
	 *
	 * @return
	 */
	public function save_meta_options( $post_id ) {

		// Verify that the nonce is set and valid.
		if ( !isset( $_POST['geot_options_nonce'] ) || ! wp_verify_nonce( $_POST['geot_options_nonce'], 'geot_options' ) ) {
			return $post_id;
		}

		// can user edit this post?
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		if( did_action( 'save_post' ) !== 1 )
			return $post_id;

		$opts = $_POST['geot'];
		unset( $_POST['geot'] );

		// save box settings
		update_post_meta( $post_id, 'geot_options', apply_filters( 'geot/metaboxes/sanitized_options', $opts ) );
		// add one post meta to let us retrieve only posts that need to be geotarted ( used on helpers class )
		$geot_post = false;
		if( !empty( $opts['country_code'] ) || !empty( $opts['region'] ) || !empty( $opts['cities'] ) || !empty( $opts['states'] ) )
			$geot_post = true;
		update_post_meta( $post_id, '_geot_post', $geot_post );
	}

	/**
	 * Add filters for tinymce buttons
	 */
	public function register_tiny_buttons() {
		add_filter( "mce_external_plugins", array( $this, "add_button" ) );
    	add_filter( 'mce_buttons', array( $this, 'register_button' ) );
	}

	/**
	 * Add buton js file
	 * @param [type] $plugin_array [description]
	 */
	function add_button( $plugin_array ) {

    	$plugin_array['geot'] = plugins_url( 'js/geot-tinymce.js' , __FILE__ );
   	 	return $plugin_array;

	}

	/**
	 * Register button
	 * @param  [type] $buttons [description]
	 * @return [type]          [description]
	 */
	function register_button( $buttons ) {
	    array_push( $buttons, '|', 'geot_button' ); // dropcap', 'recentposts
	    return $buttons;
	}

	/**
	 * Add popup editor for
	 */
	function add_editor() {

		include 'partials/tinymce-editor.php';

	}

	/**
	 * Register all plugin widgets
	 * @return mixed
	 */
	public function register_widgets() {

     	register_widget( 'Geot_Widget' );

	}

	/**
	 * Add geot to Advanced custom fields v5
	 * @since 1.0.0
	 */
	function add_geot_to_acfv5(){

		include 'includes/acf-geot-v5.php';

	}

	/**
	 * Add geot to Advanced custom fields v4
	 * @since 1.0.0
	 */
	function add_geot_to_acfv4(){

		include 'includes/acf-geot-v4.php';

	}

	/**
	 * Check license
	 */
	public function check_license() {
		if( empty($_POST['license']) ){
			echo json_encode( ['error' => 'Please enter the license'] );
			die();
		}
		$license = esc_attr($_POST['license']);
		$response = GeotargetingWP::checkLicense($license);

		$result = json_decode( $response );
		$opts = geot_settings();
		$opts['license'] = $license;
		// update license
		if( isset( $result->success ) ) {
			update_option('geot_license_active', 'valid');
		} else {
			delete_option('geot_license_active');
		}
		update_option( 'geot_settings', $opts );
		echo $response; // send result to javascript
		die();
	}

	/**
	 * Handle Licences and updates
	 * @since 1.0.0
	 */
	public function handle_updates(){
		$opts = geot_settings();
		// Setup the updater
		return new GeotUpdates( GEOT_PLUGIN_FILE, [
				'version'   => $this->version,
				'license'   => $opts['license']
			]
		);
	}

	/*
	 * Get a country code and return cities
	 */
	public function geot_cities_by_country(){
		global $wpdb;

		if( empty($_POST['country']))
			die();

		$cities =  GeotargetingWP::getCities($_POST['country']);

		if( !empty( $cities ) ){
			$cities = json_decode( $cities );
			foreach( $cities as $c ) {
				echo '<option value="'.strtolower($c->city).'">'.$c->city.'</option>';
			}
		}

		die();
	}
}
