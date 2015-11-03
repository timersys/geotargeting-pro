<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      GeoTarget_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $GeoTarget    The string used to uniquely identify this plugin.
	 */
	protected $GeoTarget;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Instance of GetFunctions
	 * @var object
	 */
	public $functions;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->GeoTarget = 'geotarget';
		$this->version = GEOT_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_helper_hooks();
		$this->define_public_hooks();
		$this->register_shortcodes();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - GeoTarget_Loader. Orchestrates the hooks of the plugin.
	 * - GeoTarget_i18n. Defines internationalization functionality.
	 * - GeoTarget_Admin. Defines all hooks for the dashboard.
	 * - GeoTarget_Public. Defines all hooks for the public side of the site.
	 * - GeoTarget_Function. Defines all main functions for targeting
	 * - GeoTarget_Filters. Defines all main filters helpers
	 * - GeoTarget_shortcodes. Defines all plugin shortcodes
	 * - GeoTarget_Widget. Defines plugin widget
	 * - GeoTarget_Widgets. Target all widgets with geot
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-geotarget-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-geotarget-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-filters.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-shortcodes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-emails.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/class-geotarget-dropdown-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/class-geotarget-widgets.php';



		$this->loader = new GeoTarget_Loader();

		$this->functions = new GeoTarget_Functions( $this->get_GeoTarget(), $this->get_version() );

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the GeoTarget_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new GeoTarget_i18n();
		$plugin_i18n->set_domain( $this->get_GeoTarget() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all helpers functions
	 * @since 1.0.0
	 * @access private
	 */
	private function define_helper_hooks() {
		$helpers = new GeoTarget_Filters( $this->get_GeoTarget(), $this->get_version() );

		$this->loader->add_filter( 'geot/get_post_types', $helpers, 'get_post_types',1,3 );
		$this->loader->add_filter( 'geot/get_countries', $helpers, 'get_countries',1 );
		$this->loader->add_filter( 'geot/get_regions', $helpers, 'get_regions',1 );
		$this->loader->add_filter( 'geot/get_city_regions', $helpers, 'get_city_regions',1 );
	}
	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		global $pagenow;

		$plugin_admin = new GeoTarget_Admin( $this->get_GeoTarget(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_tiny_buttons' );
   		
   		// Add html for shortcodes popup
   		if( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) {

			$this->loader->add_action( 'in_admin_footer', $plugin_admin, 'add_editor' );
   			
   		}
		
		// register dropdown widget
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_widgets');

		// settings page
		$this->loader->add_action( 'admin_menu' , $plugin_admin, 'add_settings_menu' );


		// Add geot to Advanced custom fields plugin
		$this->loader->add_action( 'acf/include_field_types', $plugin_admin, 'add_geot_to_acfv5' );
		$this->loader->add_action( 'acf/register_fields', $plugin_admin, 'add_geot_to_acfv4' );
		

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_options' , 20 );
		
		$geot_widgets = new Geot_Widgets( $this->get_GeoTarget(), $this->get_version() );

		// give users a way to disable widgets targeting
		if ( !defined('GEOT_WIDGETS') ) {
			// add geot to all widgets
			$this->loader->add_action( 'in_widget_form', $geot_widgets, 'add_geot_to_widgets', 5, 3 );
			$this->loader->add_action( 'widget_display_callback', $geot_widgets, 'target_widgets' );
			$this->loader->add_action( 'widget_update_callback', $geot_widgets, 'save_widgets_data', 5, 3 );
		}
		// License and Updates	
		$this->loader->add_action( 'admin_init' , $plugin_admin, 'handle_license', 1 );

		// Ajax admin
		$this->loader->add_action( 'wp_ajax_geot_cities_by_country' , $plugin_admin, 'geot_cities_by_country' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new GeoTarget_Public( $this->get_GeoTarget(), $this->get_version(), $this->functions );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'geot_redirections' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'print_debug_info', 999 );

		// Popups rules

		add_action( 'spu/rules/print_geot_country_field', array( 'Spu_Helper', 'print_select' ), 10, 2 );
		add_action( 'spu/rules/print_geot_country_region_field', array( 'Spu_Helper', 'print_select' ), 10, 2 );
		add_action( 'spu/rules/print_geot_city_region_field', array( 'Spu_Helper', 'print_select' ), 10, 2 );
		add_action( 'spu/rules/print_geot_state_field', array( 'Spu_Helper', 'print_textfield' ), 10, 1 );

		$this->loader->add_filter( 'spu/metaboxes/rule_types', $plugin_public, 'add_popups_rules' );

		$this->loader->add_filter( 'spu/rules/rule_values/geot_country', $plugin_public, 'add_country_choices' );
		$this->loader->add_filter( 'spu/rules/rule_values/geot_country_region', $plugin_public, 'add_country_region_choices' );
		$this->loader->add_filter( 'spu/rules/rule_values/geot_city_region', $plugin_public, 'add_city_region_choices' );

		$this->loader->add_filter( 'spu/rules/rule_match/geot_country', $plugin_public, 'popup_country_match', 10, 2 );
		$this->loader->add_filter( 'spu/rules/rule_match/geot_country_region', $plugin_public, 'popup_country_region_match', 10, 2 );
		$this->loader->add_filter( 'spu/rules/rule_match/geot_city_region', $plugin_public, 'popup_city_region_match', 10, 2 );
		$this->loader->add_filter( 'spu/rules/rule_match/geot_state', $plugin_public, 'popup_state_match', 10, 2 );

		$this->loader->add_filter( 'the_content', $plugin_public, 'check_if_geotargeted_content', 99 );

	}

	/**
	 * Register shortcodes
	 * @access   private
	 */
	private function register_shortcodes()
	{
		$shortcodes = new GeoTarget_Shortcodes( $this->get_GeoTarget(), $this->get_version(), $this->functions );

		add_shortcode('geot', array( $shortcodes, 'geot_filter') );
		add_shortcode('geot_city', array( $shortcodes, 'geot_filter_cities') );
		add_shortcode('geot_state', array( $shortcodes, 'geot_filter_states') );
		add_shortcode('geot_country_code', array( $shortcodes, 'geot_show_country_code') );
		add_shortcode('geot_country_name', array( $shortcodes, 'geot_show_country_name') );
		add_shortcode('geot_city_name', array( $shortcodes, 'geot_show_city_name') );
		add_shortcode('geot_state_name', array( $shortcodes, 'geot_show_state_name') );
		add_shortcode('geot_state_code', array( $shortcodes, 'geot_show_state_code') );
		add_shortcode('geot_zip', array( $shortcodes, 'geot_show_zip_code') );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_GeoTarget() {
		return $this->GeoTarget;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    GeoTarget_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
