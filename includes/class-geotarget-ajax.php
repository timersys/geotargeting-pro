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
	 * @var      class    instance of GeotFunctions
	 */
	public function __construct( $GeoTarget, $version, $functions ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;
		$this->functions = $functions;
	}

	/**
	 * Main function that execute all shortcodes
	 * put the returned data into a array and send the ajax response
	 * @return string
	 */
	public function geot_ajax(){

		$geots = $posts = array();
		$posts = $this->get_geotargeted_posts();
		if( isset( $_POST['geots'] ) ) {
			foreach( $_POST['geots'] as $id => $geot ) {
				if( method_exists( $this, $geot['action'] ) ) {
					$geots[] = array(
						'id'        => $id,
						'action'    => $geot['action'],
						'value'     => $this->{$geot['action']}( $geot )
					);
				}
			}
		}
		echo json_encode( array( 'success' => 1, 'data' => $geots, 'posts' => $posts ) );
		die();
	}

	/**
	 * Get user country name
	 * @param $geot
	 *
	 * @return string
	 */
	private function country_name( $geot ) {

		$r = $this->functions->get_user_country();

		return !empty( $r->names ) ? $r->name : '';

	}

	/**
	 * Get user city name
	 * @param $geot
	 *
	 * @return string
	 */
	private function city_name( $geot ) {

		$r = $this->functions->get_user_city();

		return !empty( $r ) ? $r : '';

	}

	/**
	 * Get user state name
	 * @param $geot
	 *
	 * @return string
	 */
	private function state_name( $geot ) {

		$r = $this->functions->get_user_state();

		return !empty( $r->names ) ? $r->name : '';

	}

	/**
	 * Get user state code
	 * @param $geot
	 *
	 * @return string
	 */
	private function state_code( $geot ) {

		$r = $this->functions->get_user_state();

		return !empty( $r->isoCode ) ? $r->isoCode : '';

	}

	/**
	 * Get user zip code
	 * @param $geot
	 *
	 * @return string
	 */
	private function zip( $geot ) {

		$r = $this->functions->get_user_zip();

		return !empty( $r ) ? $r : '';

	}

	/**
	 * Get user country code
	 * @param $geot
	 *
	 * @return string
	 */
	private function country_code( $geot ) {

		$c = $this->functions->get_user_country();

		return !empty( $c->isoCode ) ? $c->isoCode : '';

	}

	/**
	 * Filter function for countries
	 * @param $geot
	 *
	 * @return boolean
	 */
	private function country_filter( $geot ) {

		if ( $this->functions->targetCountry( $geot['filter'], $geot['region'], $geot['ex_filter'], $geot['ex_region'] ) )
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

		if ( $this->functions->targetCity( $geot['filter'], $geot['region'], $geot['ex_filter'], $geot['ex_region'] ) )
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

		if ( $this->functions->targetState( $geot['filter'], $geot['ex_filter'] ) )
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
		// get all posts with geo options set ( ideally would be to retrieve just for the post type queried but I can't get post_type
		$sql = "SELECT ID, pm.meta_value as geot_countries, pm2.meta_value as geot_options FROM $wpdb->posts p
LEFT JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
LEFT JOIN $wpdb->postmeta pm2 ON p.ID = pm2.post_id
WHERE p.post_status = 'publish'
AND pm.meta_key = 'geot_countries'
AND pm2.meta_key = 'geot_options'
AND pm.meta_value != ''";
		$geot_posts = $wpdb->get_results( $sql );

		if( $geot_posts ) {
			foreach( $geot_posts as $p ) {
				$options = unserialize( $p->geot_options );
				$mode = $options['geot_include_mode'];
				if( 'exclude' == $mode ) {
					if( geot_target( $p->geot_countries ) ){
						if( ! isset( $options['geot_remove_post']) || '1' != $options['geot_remove_post'] )
							$content_to_hide[] = array(
								'id' => $p->ID,
								'msg'=> apply_filters( 'geot/forbidden_text', $options['forbidden_text'] )
							);
						else
							$posts_to_exclude[] = $p->ID;
					}
				} elseif ( 'include' == $mode ) {
					if( ! geot_target( $p->geot_countries ) ) {
						if ( ! isset( $options['geot_remove_post'] ) || '1' != $options['geot_remove_post'] ) {
							$content_to_hide[] = array(
								'id' => $p->ID,
								'msg'=> apply_filters( 'geot/forbidden_text', $options['forbidden_text'] )
							);
						} else {
							$posts_to_exclude[] = $p->ID;
						}
					}
				}
			}
		}
		return array(
			'remove' => $posts_to_exclude,
			'hide'   => $content_to_hide
		);
	}

}	
