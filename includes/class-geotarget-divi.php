<?php
/**
* Divi Extension
*
* @link       https://geotargetingwp.com/geotargeting-pro
* @since      1.6.3
*
* @package    GeoTarget
* @subpackage GeoTarget/includes
* @author     Damian Logghe
*/
class GeoTarget_Divi {

	/**
	 * @since   1.0.0
	 * @access  private
	 * @var     Array of plugin settings
	 */
	private $opts;
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
		$this->opts = geot_settings();
	}


	/**
	* Get Regions
	* @var	string 	$slug_region
	*/
	protected function get_regions($slug_region = 'country') {

		$dropdown_values = array();

		switch($slug_region) {
			case 'cities': $regions = geot_city_regions(); break;
			default: $regions = geot_country_regions();
		}
		
		if( !empty( $regions ) ) {
			foreach ( $regions as $r ) {
				if( isset( $r['name'] ) ) {
					$dropdown_values[] = array( 'value' => $r['name'], 'label' => $r['name'] );
				}
			}
		}

		return $dropdown_values;
	}


	/**
	* Register Blocks
	* @var
	*/
	public function register_init() {

		
	}

	public function section_attributes($attributes, $atts, $num) {

		update_option('momo1', print_r($attributes, true));
		update_option('momo2', print_r($atts, true));
		update_option('momo3', print_r($num, true));

		return $attributes;
	}


	public function call_module() {
		require_once GEOT_PLUGIN_DIR . '/includes/divi/geo-country.php';
	}
}
