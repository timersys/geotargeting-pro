<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $country
 * @var $exclude_country
 * @var $region
 * @var $exclude_region
 * @var $this WPBakeryShortCode_VC_Geot
 */
$country = $exclude_country = $region = $exclude_region = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$geot_opts = geot_pro_settings();

if( isset( $geot_opts['ajax_mode'] ) && $geot_opts['ajax_mode'] == '1' ) {
	echo '<div class="geot-ajax geot-filter" data-action="country_filter" data-filter="' . $country . '" data-region="' . $region . '" data-ex_filter="' . $exclude_country . '" data-ex_region="' . $exclude_region . '">' . wpb_js_remove_wpautop( $content ) . '</div>';
} else {
	if ( geot_target( $country, $region, $exclude_country, $exclude_region ) ) {
		echo wpb_js_remove_wpautop( $content );
	}
}