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
		vc_map(
			array(
				'name'                      => __( 'Target Countries' , 'geot' ),
				'is_container'              => true,
				'content_element'           => true,
				'base'                      => 'vc_geot',
				'icon'                      => GEOT_PLUGIN_URL . '/admin/img/world.png',
				'show_settings_on_create'   => true,
				'category'                  => __( 'Geotargeting', 'geot' ),
				'description'               => __( 'Place elements inside this geot container', 'geot' ),
				'html_template'             => GEOT_PLUGIN_DIR . '/includes/vc/vc_geot.php',
				"params"                    => array(
					array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Country", 'geot'),
						"param_name" => "country",
						"value" => __("", 'geot'),
						"description" => __("Type country name or ISO code. Also you can write a comma separated list of countries", 'geot'),
						'group' => __( 'GeoTargeting', 'geot' ),
					),
					array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __("Region", 'geot'),
						"param_name" => "region",
						"value" => $dropdown_values,
						"description" => __("Choose region name to show content to", 'geot'),
						'group' => __( 'GeoTargeting', 'geot' ),
					),
					array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Exclude Country", 'geot'),
						"param_name" => "exclude_country",
						"value" => __("", 'geot'),
						"description" => __("Type country name or ISO code. Also you could write a comma separated list of countries", 'geot'),
						'group' => __( 'GeoTargeting', 'geot' ),
					),
					array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __("Exclude Region", 'geot'),
						"param_name" => "exclude_region",
						"value" => $dropdown_values,
						"description" => __("Choose region name to exclude content.", 'geot'),
						'group' => __( 'GeoTargeting', 'geot' ),
					),
				)
			)
		);
		vc_map(
			array(
				'name'                      => __( 'Target Cities' , 'geot' ),
				'is_container'              => true,
				'content_element'           => true,
				'base'                      => 'vc_geot_city',
				'icon'                      => GEOT_PLUGIN_URL . '/admin/img/cities.png',
				'show_settings_on_create'   => true,
				'category'                  => __( 'Geotargeting', 'geot' ),
				'description'               => __( 'Place elements inside this geot container', 'geot' ),
				'html_template'             => GEOT_PLUGIN_DIR . '/includes/vc/vc_geot_city.php',
				"params"                    => array(
					array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("City", 'geot'),
						"param_name" => "city",
						"value" => __("", 'geot'),
						"description" => __("Type city name. Also you can write a comma separated list of cities", 'geot'),
						'group' => __( 'GeoTargeting', 'geot' ),
					),
					array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __("City Region", 'geot'),
						"param_name" => "region",
						"value" => $dropdown_values,
						"description" => __("Choose region name to show content to", 'geot'),
						'group' => __( 'GeoTargeting', 'geot' ),
					),
					array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Exclude City", 'geot'),
						"param_name" => "exclude_city",
						"value" => __("", 'geot'),
						"description" => __("Type city name. Also you could write a comma separated list of cities", 'geot'),
						'group' => __( 'GeoTargeting', 'geot' ),
					),
					array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __("Exclude City Region", 'geot'),
						"param_name" => "exclude_region",
						"value" => $dropdown_values,
						"description" => __("Choose region name to exclude content.", 'geot'),
						'group' => __( 'GeoTargeting', 'geot' ),
					),
				)
			)
		);
		vc_map(
			array(
				'name'                      => __( 'Target States' , 'geot' ),
				'is_container'              => true,
				'content_element'           => true,
				'base'                      => 'vc_geot_state',
				'icon'                      => GEOT_PLUGIN_URL . '/admin/img/states.png',
				'show_settings_on_create'   => true,
				'category'                  => __( 'Geotargeting', 'geot' ),
				'description'               => __( 'Place elements inside this geot container', 'geot' ),
				'html_template'             => GEOT_PLUGIN_DIR . '/includes/vc/vc_geot_state.php',
				"params"                    => array(
					array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("State", 'geot'),
						"param_name" => "state",
						"value" => __("", 'geot'),
						"description" => __("Type state name or ISO code. Also you can write a comma separated list of states", 'geot'),
						'group' => __( 'GeoTargeting', 'geot' ),
					),
					array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Exclude State", 'geot'),
						"param_name" => "exclude_state",
						"value" => __("", 'geot'),
						"description" => __("Type state name or ISO code. Also you can write a comma separated list of states", 'geot'),
						'group' => __( 'GeoTargeting', 'geot' ),
					)
				)
			)
		);

	}
}
add_action('init',function(){
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_VC_Geot extends WPBakeryShortCodesContainer {
		}
		class WPBakeryShortCode_VC_Geot_City extends WPBakeryShortCodesContainer {
		}
		class WPBakeryShortCode_VC_Geot_State extends WPBakeryShortCodesContainer {
		}
	}
});
