<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       https://geotargetingwp.com/geotargeting-pro
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 */
use GeotFunctions\Setting\GeotSettings;


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
	 * @var GeoTarget_Public $public
	 */
	public $public;

	/**
	 * @var GeoTarget_VC $vc
	 */
	public $vc;

	/**
	 * @var GeoTarget_Admin $admin
	 */
	public $admin;

	/**
	 * @var GeoTarget_Menus $menus
	 */
	public $menus;

	/**
	 * @var mixed|void Geotarget settings
	 */
	public $opts;
	public $geot_opts;

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
	 * Plugin Instance
	 * @since 1.0.0
	 * @var The Geot plugin instance
	 */
	protected static $_instance = null;

	/**
	 * Main Geot Instance
	 *
	 * Ensures only one instance of WSI is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see GEOT()
	 * @return Geot - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wsi' ), '2.1' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wsi' ), '2.1' );
	}

	/**
	 * Auto-load in-accessible properties on demand.
	 * @param mixed $key
	 * @since 1.0.0
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( in_array( $key, array( 'payment_gateways', 'shipping', 'mailer', 'checkout' ) ) ) {
			return $this->$key();
		}
	}

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


		$this->load_dependencies();
		GeotSettings::init();
		$this->GeoTarget = 'geotarget';
		$this->version = GEOT_VERSION;
		$this->opts = geot_settings();
		$this->geot_opts = geot_pro_settings();
		$this->set_locale();
		$this->define_public_hooks();
		$this->register_shortcodes();
		$this->define_admin_hooks();
		$this->register_ajax_calls();
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-geotarget-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-geotarget-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-shortcodes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-ajax-shortcodes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-ajax.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-vc.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-helpers.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/class-geotarget-dropdown-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/class-geotarget-widgets.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/class-geotarget-menus.php';


		$this->loader = new GeoTarget_Loader();

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
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		global $pagenow;

		$this->admin = new GeoTarget_Admin( $this->get_GeoTarget(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $this->admin, 'register_tiny_buttons' );

		add_filter('geot/plugin_version', function (){ return GEOT_VERSION;});
   		// Add html for shortcodes popup

		$this->loader->add_action( 'wp_ajax_geot_get_popup', $this->admin, 'add_editor' );

		// register dropdown widget
		$this->loader->add_action( 'widgets_init', $this->admin, 'register_widgets');

		// settings page
		$this->loader->add_action( 'admin_menu', $this->admin, 'add_plugin_menu' );
		$this->loader->add_action( 'admin_init', $this->admin, 'save_settings' );


		// Add geot to Advanced custom fields plugin
		$this->loader->add_action( 'acf/include_field_types', $this->admin, 'add_geot_to_acfv5' );
		$this->loader->add_action( 'acf/register_fields', $this->admin, 'add_geot_to_acfv4' );


		$this->loader->add_action( 'add_meta_boxes', $this->admin, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post', $this->admin, 'save_meta_options' , 20 );

		$geot_widgets = new Geot_Widgets( $this->get_GeoTarget(), $this->get_version() );

		// give users a way to disable widgets targeting
		if (  empty( $this->geot_opts['disable_widget_integration'] ) && empty( $this->geot_opts['ajax_mode']) ) {
			// add geot to all widgets
			$this->loader->add_action( 'in_widget_form', $geot_widgets, 'add_geot_to_widgets', 5, 3 );
			$this->loader->add_action( 'widget_display_callback', $geot_widgets, 'target_widgets' );
			$this->loader->add_action( 'widget_update_callback', $geot_widgets, 'save_widgets_data', 5, 3 );
		}
		// License and Updates
		$this->loader->add_action( 'admin_init' , $this->admin, 'handle_updates', 0 );

		//Menus
		if (  empty( $this->geot_opts['disable_menu_integration'] ) ) {
			$this->loader->add_filter( 'wp_setup_nav_menu_item', $this->menus, 'add_custom_fields' );
			$this->loader->add_filter( 'wp_edit_nav_menu_walker', $this->menus, 'admin_menu_walker', 150, 2 );
			$this->loader->add_action( 'wp_update_nav_menu_item', $this->menus, 'save_custom_fields', 10, 3 );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->public   = new GeoTarget_Public( $this->get_GeoTarget(), $this->get_version() );
		$this->vc       = new GeoTarget_VC( $this->get_GeoTarget(), $this->get_version() );
		$this->menus = new GeoTarget_Menus( $this->get_GeoTarget(), $this->get_version() );
		// if we have cache mode, load geotarget now to set session before content
		if( isset( $this->opts['cache_mode'] ) && $this->opts['cache_mode'] )
			geot();

		$this->loader->add_action( 'wp_enqueue_scripts', $this->public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->public, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_footer', $this->public, 'print_debug_info', 999 );

		$this->loader->add_filter( 'posts_where', $this->public, 'handle_geotargeted_posts' , PHP_INT_MAX);
		$this->loader->add_filter( 'the_content', $this->public, 'check_if_geotargeted_content', 99 );
		$this->loader->add_filter( 'wp', $this->public, 'disable_woo_product' );
		// Popups rules

		add_action( 'spu/rules/print_geot_country_field', array( 'Spu_Helper', 'print_select' ), 10, 2 );
		add_action( 'spu/rules/print_geot_country_region_field', array( 'Spu_Helper', 'print_select' ), 10, 2 );
		add_action( 'spu/rules/print_geot_city_region_field', array( 'Spu_Helper', 'print_select' ), 10, 2 );
		add_action( 'spu/rules/print_geot_state_field', array( 'Spu_Helper', 'print_textfield' ), 10, 1 );

		$this->loader->add_filter( 'spu/metaboxes/rule_types', $this->public, 'add_popups_rules' );

		$this->loader->add_filter( 'spu/rules/rule_values/geot_country', $this->public, 'add_country_choices' );
		$this->loader->add_filter( 'spu/rules/rule_values/geot_country_region', $this->public, 'add_country_region_choices' );
		$this->loader->add_filter( 'spu/rules/rule_values/geot_city_region', $this->public, 'add_city_region_choices' );

		$this->loader->add_filter( 'spu/rules/rule_match/geot_country', $this->public, 'popup_country_match', 10, 2 );
		$this->loader->add_filter( 'spu/rules/rule_match/geot_country_region', $this->public, 'popup_country_region_match', 10, 2 );
		$this->loader->add_filter( 'spu/rules/rule_match/geot_city_region', $this->public, 'popup_city_region_match', 10, 2 );
		$this->loader->add_filter( 'spu/rules/rule_match/geot_state', $this->public, 'popup_state_match', 10, 2 );

		// Visual composer
		$this->loader->add_action( 'init', $this->vc, 'hook_to_visual' );

		// Menus
		if (  empty( $this->geot_opts['disable_menu_integration'] ) )
			$this->loader->add_filter( 'wp_nav_menu_objects', $this->menus, 'geotarget_menus', 10, 2 );
	}

	/**
	 * Register shortcodes
	 * @access   private
	 */
	private function register_shortcodes()
	{
		$shortcodes = new GeoTarget_Shortcodes( $this->get_GeoTarget(), $this->get_version() );
		$ajax_shortcodes = new GeoTarget_Ajax_Shortcodes( $this->get_GeoTarget(), $this->get_version() );

		$this->loader->add_action( 'init', $shortcodes, 'register_shortcodes' );
		$this->loader->add_action( 'init', $ajax_shortcodes, 'register_shortcodes' );

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

	private function register_ajax_calls() {

		$ajax_class = new GeoTarget_Ajax( $this->get_GeoTarget(), $this->get_version() );

		$this->loader->add_action( 'wp_ajax_geot_ajax' , $ajax_class, 'geot_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_geot_ajax' , $ajax_class, 'geot_ajax' );


	}

}
