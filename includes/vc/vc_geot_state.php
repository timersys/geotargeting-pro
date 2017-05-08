<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $state
 * @var $exclude_state
 * @var $this WPBakeryShortCode_VC_Geot
 */
$state = $exclude_state = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$geot_opts = geot_pro_settings();

if( isset( $geot_opts['ajax_mode'] ) && $geot_opts['ajax_mode'] == '1' ) {
	echo '<div class="geot-ajax geot-filter" data-action="state_filter" data-filter="' . $state . '" data-ex_filter="' . $exclude_state . '">' . wpb_js_remove_wpautop( $content ) . '</div>';
} else {
	if ( geot_target_state( $state, $exclude_state ) ) {
		echo wpb_js_remove_wpautop( $content );
	}
}