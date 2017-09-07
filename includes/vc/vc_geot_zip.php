<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $zip
 * @var $exclude_zip
 * @var $this WPBakeryShortCode_VC_Geot
 */
$zip = $exclude_zip = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$geot_opts = geot_pro_settings();

if( isset( $geot_opts['ajax_mode'] ) && $geot_opts['ajax_mode'] == '1' ) {
	echo '<div class="geot-ajax geot-filter" data-action="zip_filter" data-filter="' . $zip . '" data-ex_filter="' . $exclude_zip . '">' . wpb_js_remove_wpautop( $content ) . '</div>';
} else {
	if ( geot_target_zip( $zip, $exclude_zip ) ) {
		echo wpb_js_remove_wpautop( $content );
	}
}