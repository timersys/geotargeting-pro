<?php
/**
* Visual Composer Extension
*
* @link       http://wp.timersys.com/geotargeting/
* @since      1.6.3
*
* @package    GeoTarget
* @subpackage GeoTarget/includes
* @author     Damian Logghe
*/
class GeoTarget_VC {

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
	 * @var      string    $GeoTarget       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 * @var      class    instance of GeotFunctions
	 */
	public function __construct( $GeoTarget, $version, $functions ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;
		$this->functions = $functions;
		$this->opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );

	}

	public function hook_to_visual() {
		// Check if Visual Composer is installed
		if ( ! defined( 'WPB_VC_VERSION' ) )
			return;

		$regions = apply_filters('geot/get_regions', array());
		$dropdown_values = array( __('Choose one','geot') => '');

		if( !empty( $regions ) ) {
			foreach ( $regions as $r ) {
				$values = implode( ',', $r['countries'] );
				$dropdown_values[$r['name']] = $values;
			}
		}
		/*
		Add your Visual Composer logic here.
		Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

		More info: http://kb.wpbakery.com/index.php?title=Vc_map
		*/
		vc_map( array(
			"name" => __("Geotargeting Container", 'geot'),
			"description" => __("Geotarget logic here", 'geot'),
			"base" => "geot",
			"class" => "",
			"icon" => plugins_url('assets/asterisk_yellow.png', __FILE__), // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
			"category" => __('Content', 'js_composer'),
			"params" => array(
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __("Country", 'geot'),
					"param_name" => "country",
					"value" => __("", 'geot'),
					"description" => __("Type country name or ISO code. Also you can write a comma separated list of countries", 'geot')
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __("Region", 'geot'),
					"param_name" => "region",
					"value" => $dropdown_values,
					"description" => __("Choose region name to show content to", 'geot')
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __("Exclude Country", 'geot'),
					"param_name" => "exclude_country",
					"value" => __("", 'geot'),
					"description" => __("Type country name or ISO code. Also you could write a comma separated list of countries", 'geot')
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __("Exclude Region", 'geot'),
					"param_name" => "exclude_region",
					"value" => $dropdown_values,
					"description" => __("Choose region name to exclude content.", 'geot')
				),
			)
		) );
	}
}