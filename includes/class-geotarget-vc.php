<?php
/**
* Visual Composer Extension
*
* @link       https://geotargetingwp.com/geotargeting-pro
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

	public function hook_to_visual() {
		// Check if Visual Composer is installed
		if ( ! defined( 'WPB_VC_VERSION' ) )
			return;

		vc_add_shortcode_param( 'geot_dropdown', [$this,'dropdown_field'] );
		$regions = geot_country_regions();
		$dropdown_values = array( __('Choose one','geot') => '');

		if( !empty( $regions ) ) {
			foreach ( $regions as $r ) {
				if( isset( $r['name'] ) ) {
					$dropdown_values[ $r['name'] ] = $r['name'];
				}
			}
		}

		$city_regions = geot_city_regions();
		$city_dropdown_values = array( __('Choose one','geot') => '');

		if( !empty( $city_regions ) ) {
			foreach ( $city_regions as $k => $r ) {
				if( isset( $r['name'] ) ) {
					$city_dropdown_values[ $r['name'] ] = $r['name'];
				}
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
				'js_view'                   => 'VcColumnView',
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
						"type" => "geot_dropdown",
						"class" => "",
						"heading" => __("Region", 'geot'),
						"param_name" => "region",
						"multiple"   => true,
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
						"type" => "geot_dropdown",
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
				'js_view'                   => 'VcColumnView',
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
						"type" => "geot_dropdown",
						"class" => "",
						"heading" => __("City Region", 'geot'),
						"param_name" => "region",
						"value" => $city_dropdown_values,
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
						"type" => "geot_dropdown",
						"class" => "",
						"heading" => __("Exclude City Region", 'geot'),
						"param_name" => "exclude_region",
						"value" => $city_dropdown_values,
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
				'js_view'                   => 'VcColumnView',
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

	function dropdown_field( $settings, $value ) {
		$output = '';
		$css_option = str_replace( '#', 'hash-', vc_get_dropdown_option( $settings, $value ) );
		$output .= '<select name="'
		           . $settings['param_name']
		           . '" class="wpb_vc_param_value wpb-input wpb-select '
		           . $settings['param_name']
		           . ' ' . $settings['type']
		           . ' ' . $css_option
		           . '" data-option="' . $css_option . '" multiple>';

		$value = is_array($value)? $value : explode(',',$value);
		if ( ! empty( $settings['value'] ) ) {
			foreach ( $settings['value'] as $index => $data ) {
				if ( is_numeric( $index ) && ( is_string( $data ) || is_numeric( $data ) ) ) {
					$option_label = $data;
					$option_value = $data;
				} elseif ( is_numeric( $index ) && is_array( $data ) ) {
					$option_label = isset( $data['label'] ) ? $data['label'] : array_pop( $data );
					$option_value = isset( $data['value'] ) ? $data['value'] : array_pop( $data );
				} else {
					$option_value = $data;
					$option_label = $index;
				}
				$selected = '';
				$option_value_string = (string) $option_value;
				if ( in_array($option_value_string,$value )) {
					$selected = ' selected="selected"';
				}
				$option_class = str_replace( '#', 'hash-', $option_value );
				$output .= '<option class="' . esc_attr( $option_class ) . '" value="' . esc_attr( $option_value ) . '"' . $selected . '>'
				           . htmlspecialchars( $option_label ) . '</option>';
			}
		}
		$output .= '</select>';

		return $output;
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
