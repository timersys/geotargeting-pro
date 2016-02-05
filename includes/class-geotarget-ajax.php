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

		$geots = array();

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
			echo json_encode( array( 'success' => 1, 'data' => $geots ) );
			die();
		}
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

}	
