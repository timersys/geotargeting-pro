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
	 * @var      string    $GeoTarget       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
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

}
