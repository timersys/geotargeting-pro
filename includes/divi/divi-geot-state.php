<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Divi Geo Module
 *
 * @link       https://geotargetingwp.com/geotargeting-pro
 * @since      1.6.3
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Damian Logghe
 */
class Divi_GeoState {

	/**
	 * Add the actual fields
	 *
	 * @return array
	 */
	static function get_fields() {

		$fields['in_states'] = [
			'label'           => esc_html__( 'Include States', 'geot' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'description'     => esc_html__( 'Type state names or ISO codes separated by comma.', 'geot' ),
			'tab_slug'        => 'geot',
		];

		$fields['ex_states'] = [
			'label'           => esc_html__( 'Exclude States', 'geot' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'description'     => esc_html__( 'Type state names or ISO codes separated by comma.', 'geot' ),
			'tab_slug'        => 'geot',
		];

		return $fields;
	}


	/**
	 * Add the actual fields
	 *
	 * @return array
	 */
	static function is_render( $settings ) {

		extract( $settings );

		if ( empty( $in_states ) && empty( $ex_states ) ) {
			return true;
		}


		if ( geot_target_state( $in_states, $ex_states ) ) {
			return true;
		}

		return false;
	}


	/**
	 * if is ajax, apply render
	 *
	 * @return array
	 */
	static function ajax_render( $settings, $output ) {

		extract( $settings );

		if ( empty( $in_states ) && empty( $ex_states ) ) {
			return $output;
		}


		return '<div class="geot-ajax geot-filter" data-action="state_filter" data-filter="' . $in_states . '" data-ex_filter="' . $ex_states . '">' . $output . '</div>';
	}

}