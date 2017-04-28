<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $city
 * @var $exclude_city
 * @var $region
 * @var $exclude_region
 * @var $this WPBakeryShortCode_VC_Geot
 */
$city = $exclude_city = $region = $exclude_region = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$geot_opts = geot_pro_settings();

if( isset( $geot_opts['ajax_mode'] ) && $geot_opts['ajax_mode'] == '1' ) {
	echo '<div class="geot-ajax geot-filter" data-action="city_filter" data-filter="' . $city . '" data-region="' . $region . '" data-ex_filter="' . $exclude_city . '" data-ex_region="' . $exclude_region . '">' . wpb_js_remove_wpautop( $content ) . '</div>';
} else {
	if ( geot_target_city( $city, $region, $exclude_city, $exclude_region ) ) {
		echo wpb_js_remove_wpautop( $content );
	}
}