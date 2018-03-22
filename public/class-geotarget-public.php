<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://geotargetingwp.com/geotargeting-pro
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/public
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Public {

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
	 * Plugin settings
	 * @var array
	 */
	protected $opts;
	protected $geot_opts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param string $GeoTarget
	 * @param string $version
	 *
	 */
	public function __construct( $GeoTarget, $version ) {

		$this->GeoTarget 	= $GeoTarget;
		$this->version 		= $version;
		$this->opts = geot_settings();
		$this->geot_opts = geot_pro_settings();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->GeoTarget, plugin_dir_url( __FILE__ ) . 'css/geotarget-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$src = 'js/geotarget-public.js';
		if( ! isset( $this->opts['debug_mode'] ) && !isset( $_GET['geot_debug']) ) {
			$src = 'js/min/geotarget-public-min.js';
		}

		wp_enqueue_script( $this->GeoTarget, plugin_dir_url( __FILE__ ) . $src , array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'geot-slick', plugin_dir_url( __FILE__ ) . 'js/min/selectize.min.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->GeoTarget, 'geot', array(
			'ajax_url'      => admin_url( 'admin-ajax.php'),
			'ajax'          => isset( $this->geot_opts['ajax_mode'] ) ? '1' : '',
			'is_archives'   => is_archive(),
			'is_search'     => is_search(),
			'is_singular'   => is_singular(),
			'is_page'       => is_page(),
			'is_single'     => is_single(),
			'dropdown_search' => apply_filters('geot/dropdown_widget/disable_search', false ),
			'dropdown_redirect' => apply_filters('geot/dropdown_widget/redirect_url', '' ),
		) );

	}


	/**
	 * Add rules to Popups plugin
	 *
	 * @param $choices
	 *
	 * @return mixed
	 */
	public function add_popups_rules( $choices ) {
		$choices['Geotargeting'] = array(
			'geot_country'          => 'Country',
			'geot_country_region'   => 'Country Region',
			'geot_city_region'      => 'City Region',
			'geot_state'            => 'State',			'geot_country'          => 'Country',
			'geot_country_region'   => 'Country Region',
			'geot_city_region'      => 'City Region',
			'geot_state'            => 'State',
		);
		return $choices;
	}

	/**
	 * Return countries for popup rules
	 *
	 * @param $choices
	 *
	 * @return mixed
	 */
	public function add_country_choices($choices) {
		$countries = geot_countries();
		foreach( $countries as $c ) {
			$choices[$c->iso_code] = $c->country;
		}
		return $choices;
	}

	/**
	 * Return countries regions for popup rules
	 *
	 * @param $choices
	 *
	 * @return mixed
	 */
	public function add_country_region_choices($choices) {
		$regions = geot_country_regions();
		foreach( $regions as $r ) {

			$choices[$r['name']] = $r['name'];
		}
		return $choices;
	}

	/**
	 * Return cities regions for popup rules
	 *
	 * @param $choices
	 *
	 * @return mixed
	 */
	public function add_city_region_choices($choices) {
		$regions = geot_city_regions();
		foreach( $regions as $r ) {

			$choices[$r['name']] = $r['name'];
		}
		return $choices;
	}

	/**
	 * [rule_match_logged_user description]
	 * @param  bool $match false default
	 * @param  array $rule rule to compare
	 * @return boolean true if match
	 */
	function popup_country_match( $match, $rule ) {

		if ( $rule['operator'] == "==" ) {

			return geot_target( $rule['value'] );

		} else {

			return !geot_target( $rule['value'] );

		}

	}

	/**
	 * @param  bool $match false default
	 * @param  array $rule rule to compare
	 * @return boolean true if match
	 */
	function popup_country_region_match( $match, $rule ) {

		if ( $rule['operator'] == "==" ) {

			return geot_target('',$rule['value']);

		} else {

			return !geot_target('',$rule['value']);

		}

	}

	/**
	 * @param  bool $match false default
	 * @param  array $rule rule to compare
	 * @return boolean true if match
	 */
	function popup_city_region_match( $match, $rule ) {

		if ( $rule['operator'] == "==" ) {
			return geot_target_city('',$rule['value'],'','');

		} else {

			return !geot_target_city('',$rule['value'],'','');

		}

	}
	/**
	 * @param  bool $match false default
	 * @param  array $rule rule to compare
	 * @return boolean true if match
	 */
	function popup_state_match( $match, $rule ) {

		if ( $rule['operator'] == "==" ) {

			return geot_target_state($rule['value'],'');

		} else {

			return !geot_target_state($rule['value'],'');

		}

	}

	/**
	 * Filter where argument of main query to exclude geotargeted posts
	 * @param $where
	 *
	 * @return string
	 */
	public function handle_geotargeted_posts( $where ){
		global $wpdb;

		// let users cancel the removal of posts
		// for example they can check if is_search() and show the post in search results
		if( apply_filters( 'geot/cancel_posts_where', false, $where ) )
			return $where;

		if( ( isset( $this->geot_opts['ajax_mode'] ) && $this->geot_opts['ajax_mode'] == '1' ) )
			return $where;

		if ( ! is_admin() ) {
			// Get all posts that are being geotargeted
			$post_to_exclude = $this->get_geotargeted_posts( );
			if( !empty( $post_to_exclude ) ) {
				$where .= " AND {$wpdb->posts}.ID NOT IN ('". implode( "','", $post_to_exclude )."')";
				// Sticky posts needs to be filtered differently
				add_filter('option_sticky_posts', function( $posts ) use( $post_to_exclude ) {
					if( !empty($posts) ){
						foreach ( $posts as $key => $id ) {
							if( in_array( $id, $post_to_exclude) )
								unset($posts[$key]);
						}
					}
					return $posts;
				});
			}
		}
		return $where;
	}
	/**
	 * Then we get all the posts with geotarget options and
	 * check each of them to see which one we need to exclude from loop
	 *
	 * @return array|void
	 */
	private function get_geotargeted_posts( ) {
		global $wpdb;

		$posts_to_exclude = array();
		// get all posts with geo options set ( ideally would be to retrieve just for the post type queried but I can't get post_type
		$geot_posts = Geot_Helpers::get_geotarget_posts();

		if( $geot_posts ) {
			foreach( $geot_posts as $p ) {
				$options = unserialize( $p->geot_options );
				// if remove for loop is off continue
				if( ! isset( $options['geot_remove_post'])
				    || '1' != $options['geot_remove_post']
				)
					continue;


				$target  = Geot_Helpers::user_is_targeted( $options, $p->ID );
				if( $target )
					$posts_to_exclude[] = $p->ID;

			}
		}
		return $posts_to_exclude;
	}

	/**
	 * Function that filter the_content and show message if post is geotargeted
	 * @param $content
	 *
	 * @return mixed|void
	 */
	public function check_if_geotargeted_content( $content ) {
		global $post;

		if( isset( $this->geot_opts['ajax_mode'] ) && $this->geot_opts['ajax_mode'] == '1' )
			return $content;

		if( !isset( $post->ID ) )
			return $content;

		$opts  = get_post_meta( $post->ID, 'geot_options', true );

		if ( Geot_Helpers::user_is_targeted( $opts, $post->ID ) )
			return apply_filters( 'geot/forbidden_text', '<p>' . $opts['forbidden_text'] . '</p>' );

		return $content;
	}

	/**
	 * Check if user is targeted for post and disable woo product
	 * On ajax mode this function will consume an extra credit to the user
	 * if cache mode is off
	 */
	public function disable_woo_product(){
		global $post;
		if( ! class_exists( 'WooCommerce' ) || ! isset( $post->ID ) )
			return;
		$opts  = get_post_meta( $post->ID, 'geot_options', true );

		if ( Geot_Helpers::user_is_targeted( $opts, $post->ID ) )
			add_filter('woocommerce_is_purchasable', '__return_false');
	}
	/**
	 * Print current user data in footer
	 */
	public function print_debug_info() {
		$opts = geot_settings();
		if( empty( $opts['debug_mode'] )  )
			return;

		?>
		<!-- Geotargeting plugin Debug Info START-->
		<div id="geot-debug-info" style="display: none;"><!--<?php if( empty( $this->geot_opts['ajax_mode']) ) echo geot_debug_data();?>--></div>
		<!-- Geotargeting plugin Debug Info END-->
		<?php
	}

}
