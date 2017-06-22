<?php
/**
 * Ajax callbacks
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.6
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Ajax {
	/**
	 * $_POST data sent on ajax request
	 * @var Array
	 */
	protected $data;
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.6
	 * @access   private
	 * @var      string    $GeoTarget    The ID of this plugin.
	 */
	private $GeoTarget;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.6
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin functions
	 *
	 * @since    1.6
	 * @access   private
	 * @var      object    Plugin functions
	 */
	private $functions;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.6
	 * @var      string    $GeoTarget       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $GeoTarget, $version ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;
	}

	/**
	 * Main function that execute all shortcodes
	 * put the returned data into a array and send the ajax response
	 * @return string
	 */
	public function geot_ajax(){

		$geots = $posts = array();
		$posts = $this->get_geotargeted_posts();
		$this->data = $_POST;
		if( isset( $this->data['geots'] ) ) {
			foreach( $this->data['geots'] as $id => $geot ) {
				if( method_exists( $this, $geot['action'] ) ) {
					$geots[] = array(
						'id'        => $id,
						'action'    => $geot['action'],
						'value'     => $this->{$geot['action']}( $geot )
					);
				}
			}
		}
		$debug = $this->getDebugInfo();
		echo json_encode( array( 'success' => 1, 'data' => $geots, 'posts' => $posts, 'debug' => $debug ) );
		die();
	}

	/**
	 * Get user country name
	 * @param $geot
	 *
	 * @return string
	 */
	private function country_name( $geot ) {

		$name = geot_country_name();

		if ( !empty( $name ) )
			return apply_filters( 'geot/shortcodes/country_name', $name );

		return  apply_filters( 'geot/shortcodes/country_name_default', $geot['default'] );

	}

	/**
	 * Get user city name
	 * @param $geot
	 *
	 * @return string
	 */
	private function city_name( $geot ) {

		$name = geot_city_name();

		if ( !empty( $name ) )
			return apply_filters( 'geot/shortcodes/city_name', $name );

		return  apply_filters( 'geot/shortcodes/city_name_default', $geot['default'] );

	}

	/**
	 * Get user state name
	 * @param $geot
	 *
	 * @return string
	 */
	private function state_name( $geot ) {

		$name = geot_state_name();

		if ( !empty( $name ) )
			return apply_filters( 'geot/shortcodes/state_name', $name );

		return  apply_filters( 'geot/shortcodes/state_name_default', $geot['default'] );

	}

	/**
	 * Get user state code
	 * @param $geot
	 *
	 * @return string
	 */
	private function state_code( $geot ) {

		$code = geot_state_code();

		return !empty( $code ) ? $code : $geot['default'];

	}

	/**
	 * Get user zip code
	 * @param $geot
	 *
	 * @return string
	 */
	private function zip( $geot ) {

		$code = geot_zip();

		return !empty( $code ) ? $code : $geot['default'];


	}

	/**
	 * Get user current regions
	 * @param $geot
	 *
	 * @return string
	 */
	private function region( $geot ) {

		$regions = geot_user_country_region( $geot['default'] );

		if( is_array( $regions ) )
			return implode( ', ', $regions );

		return $regions;

	}

	/**
	 * Get user country code
	 * @param $geot
	 *
	 * @return string
	 */
	private function country_code( $geot ) {

		$code = geot_country_code();

		return !empty( $code ) ? $code : $geot['default'];

	}

	/**
	 * Filter function for countries
	 * @param $geot
	 *
	 * @return boolean
	 */
	private function country_filter( $geot ) {

		if ( geot_target( $geot['filter'], $geot['region'], $geot['ex_filter'], $geot['ex_region'] ) )
			return true;

		return false;
	}

	/**
	 * Filter function for cities
	 * @param $geot
	 *
	 * @return boolean
	 */
	private function city_filter( $geot ) {

		if ( geot_target_city( $geot['filter'], $geot['region'], $geot['ex_filter'], $geot['ex_region'] ) )
			return true;

		return false;
	}

	/**
	 * Filter function for states
	 * @param $geot
	 *
	 * @return boolean
	 */
	private function state_filter( $geot ) {

		if ( geot_target_state( $geot['filter'], $geot['ex_filter'] ) )
			return true;

		return false;
	}

	/**
	 * Filter function for menus
	 * @param $geot
	 *
	 * @return boolean
	 */
	private function menu_filter( $geot ) {

		$target = unserialize( base64_decode( $geot['filter'] ) );

		if ( Geot_Helpers::user_is_targeted( $target, $geot['ex_filter'] ) )
			return true;

		return false;
	}


	/**
	 * Get all post that are geotargeted
	 *
	 * @return array|void
	 */
	private function get_geotargeted_posts( ) {
		global $wpdb;

		$posts_to_exclude = array();
		$content_to_hide = array();

		// let users cancel the removal of posts
		// for example they can check if is_search() and show the post in search results
		if( apply_filters( 'geot/posts_where', false, $this->data ) )
			return array(
				'remove' => $posts_to_exclude,
				'hide'   => $content_to_hide
			);;

		// get all posts with geo options set ( ideally would be to retrieve just for the post type queried but I can't get post_type
		$geot_posts = Geot_Helpers::get_geotarget_posts();

		if( $geot_posts ) {
			foreach( $geot_posts as $p ) {
				$options = unserialize( $p->geot_options );
				$target  = Geot_Helpers::user_is_targeted( $options, $p->ID );
				if( $target ){
					if( ! isset( $options['geot_remove_post']) || '1' != $options['geot_remove_post'] )
						$content_to_hide[] = array(
							'id' => $p->ID,
							'msg'=> apply_filters( 'geot/forbidden_text', $options['forbidden_text'] )
						);
					else
						$posts_to_exclude[] = $p->ID;
				}
			}
		}
		return array(
			'remove' => $posts_to_exclude,
			'hide'   => $content_to_hide
		);
	}

	/**
	 * Grab debug info to print in footer
	 * @return string|void
	 */
	private function getDebugInfo() {
		return '<!--'.geot_debug_data().'-->';
	}

}
