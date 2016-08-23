<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wp.timersys.com/geotargeting/
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
	 * Plugin functions
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    Plugin functions
	 */
	private $functions;

	/**
	 * Plugin settings
	 * @var array
	 */
	protected $opts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param string $GeoTarget
	 * @param string $version
	 * @param $functions
	 *
	 */
	public function __construct( $GeoTarget, $version, $functions ) {

		$this->GeoTarget 	= $GeoTarget;
		$this->version 		= $version;
		$this->functions 	= $functions;
		$this->opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );
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
		if( ! isset( $this->opts['debug_mode'] ) ) {
			$src = 'js/min/geotarget-public-min.js';
		}
		$opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );

		wp_enqueue_script( $this->GeoTarget, plugin_dir_url( __FILE__ ) . $src , array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'geot-slick', plugin_dir_url( __FILE__ ) . 'js/min/selectize.min.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->GeoTarget, 'geot', array(
			'ajax_url'      => admin_url( 'admin-ajax.php'),
			'ajax'          => isset( $opts['ajax_mode'] ) ? '1' : '',
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
	 * @param $choices
	 */
	public function add_popups_rules( $choices ) {
		$choices['Geotargeting'] = array(
			'geot_country'          => 'Country',
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
		$countries = apply_filters('geot/get_countries', array());
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
		$regions = apply_filters('geot/get_regions', array());
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
		$regions = apply_filters('geot/get_city_regions', array());
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

			return geot_target('',$rule['value'],'','','cities');

		} else {

			return !geot_target('',$rule['value'],'','','cities');

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
	 * If redirections are added redirect users
	 */
	function geot_redirections() {
		global $geot;
	/* TODO : Change to new helper method */
		$opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );

		if( is_admin() || defined('DOING_AJAX') || empty( $opts['redirection'] ) || $geot->functions->isSearchEngine() )
			return;

		if( strpos( $_SERVER['REQUEST_URI'], apply_filters( 'geot/login_page_string', 'wp-login' ) ) !== false )
			return;

		foreach( $opts['redirection'] as $r ) {
			if( empty($r['name']) || !filter_var($r['name'], FILTER_VALIDATE_URL))
				continue;

			$redirect = false;

			if( !empty( $r['countries'] ) || !empty( $r['regions'] ) ) {
				$countries  = !empty( $r['countries'] ) ? $r['countries'] : '';
				$regions    = !empty( $r['regions'] ) ? $r['regions'] : '';
				if ( geot_target( $countries, $regions ) ) {
					$redirect = true;
				}
			} elseif( !empty( $r['city_regions'] ) ) {
				if ( geot_target_city( '', $r['city_regions'] ) ) {
					$redirect = true;
				}
			} elseif( !empty( $r['state'] ) ) {
				if ( geot_target_state( $r['state'] ) ) {
					$redirect = true;
				}
			}

			if( $redirect ) {
				// one extra chance to let users cancel redirection
				if ( apply_filters( 'geot/perform_redirect', true, $r, $opts ) ) {
					wp_redirect( apply_filters( 'geot/redirection_url', $r['name'] ), apply_filters( 'geot/redirection_status', '301' ) );
					exit;
				}
			}
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
		if( apply_filters( 'geot/posts_where', false, $where ) )
			return $where;

		if( isset( $this->opts['ajax_mode'] ) && $this->opts['ajax_mode'] == '1' )
			return $where;

		if ( ! is_admin()  ) {
			// Get all posts that are being geotargeted
			$post_to_exclude = $this->get_geotargeted_posts( );
			if( !empty( $post_to_exclude ) ) {
				$where .= " AND {$wpdb->posts}.ID NOT IN ('". implode( "','", $post_to_exclude )."')";
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
				if( ! isset( $options['geot_remove_post']) || '1' != $options['geot_remove_post'] )
					continue;

				$mode = $options['geot_include_mode'];
				if( 'exclude' == $mode ) {
					if( geot_target( $p->geot_countries ) )
						$posts_to_exclude[] = $p->ID;
				} elseif ( 'include' == $mode ) {
					if( ! geot_target( $p->geot_countries ) )
						$posts_to_exclude[] = $p->ID;
				}
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

		if( isset( $this->opts['ajax_mode'] ) && $this->opts['ajax_mode'] == '1' )
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
	 */
	public function disable_woo_product(){
		global $post;
		if( !isset( $post->ID ) )
			return;
		$opts  = get_post_meta( $post->ID, 'geot_options', true );

		if ( Geot_Helpers::user_is_targeted( $opts, $post->ID ) )
			add_filter('woocommerce_is_purchasable', '__return_false');
	}
	/**
	 * Print current user data in footer
	 */
	public function print_debug_info() {
		$opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );
		if( !defined('GEOT_DEBUG') && empty( $opts['debug_mode'] ) )
			return;
		$user_data = $this->functions->getUserDataByIp();
		if( empty( $user_data['country'] ) )
			return;
		?>
		<!-- Geotargeting plugin Debug Info START-->
		<div id="geot-debug-info" style="display: none;"><!--
		Country: <?php echo @$user_data['country']->name . PHP_EOL;?>
		Country code: <?php echo @$user_data['country']->isoCode . PHP_EOL;?>
		State: <?php echo @$user_data['state']->name . PHP_EOL;?>
		State code: <?php echo @$user_data['state']->isoCode . PHP_EOL;?>
		City: <?php echo @$user_data['city'] . PHP_EOL;?>
		Zip: <?php echo @$user_data['zip'] . PHP_EOL;?>
		Continent: <?php echo @$user_data['continent'] . PHP_EOL;?>
		-->
		</div>
		<!-- Geotargeting plugin Debug Info END-->
		<?php
	}

}
