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

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in GeoTarget_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The GeoTarget_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->GeoTarget, plugin_dir_url( __FILE__ ) . 'css/geotarget-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {


		wp_enqueue_script( $this->GeoTarget, plugin_dir_url( __FILE__ ) . 'js/geotarget-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'geot-slick', plugin_dir_url( __FILE__ ) . 'js/ddslick.js', array( 'jquery' ), $this->version, false );

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
		$opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );

		if( is_admin() || defined('DOING_AJAX') || empty( $opts['redirection'] ) )
			return;

		foreach( $opts['redirection'] as $r ) {
			if( empty($r['name']) || !filter_var($r['name'], FILTER_VALIDATE_URL))
				continue;
			if( geot_target( @$r['countries'], @$r['regions'] ) ) {
				wp_redirect( $r['name'], apply_filters( 'geot/redirection_status', '301') );
				exit;
			}
		}

	}

/*	public function filter_query( $query ){

		// if (  in_array('reviews', @(array)$query->query_vars['post_type']) ) {

  //       	$query->set( 'meta_query', array(
  //       			array(
  //       				'key' 		=> 'geot_countries',
  //       				'value' 	=> 'AR',
  //       				'compare' 	=> 'LIKE',
  //       			)
  //       	) );

  //       	#add_filter( 'posts_where', array( $this, 'add_extra_meta_query') );
  //   	}
	}

	public function add_extra_meta_query( $where = '' ){

		    global $wpdb;
 
		    $where .= " AND (( $wpdb->postmeta.meta_key = 'geot_countries' AND $wpdb->postmeta.meta_value LIKE 'AR' ))";
		 
		    remove_filter( 'posts_where',  array( $this, 'add_extra_meta_query') );
		 
		    return $where;
	}*/
	public function check_if_geotargeted_content( $content ) {
		global $post;

		if( $countries = get_post_meta( $post->ID, 'geot_countries', true) ) {

			$opts = get_post_meta( $post->ID, 'geot_options', true );
			if ( $opts['geot_include_mode'] == 'include' ) {
				if ( geot_target( $countries ) ) {
					return $content;
				} else {
					return apply_filters( 'geot/forbidden_text', $opts['forbidden_text'] );
				}
			} else {
				if ( !geot_target( $countries ) ) {
					return $content;
				} else {
					return apply_filters( 'geot/forbidden_text', $opts['forbidden_text'] );
				}
			}
		}

		return $content;
	}
}
