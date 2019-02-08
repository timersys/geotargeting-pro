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
class Divi_GeoZipcode {

	/**
	 * Add the actual fields
	 *
	 * @return array
	 */
	static function get_fields() {

		$fields['in_zipcodes'] = [
			'label'           => esc_html__( 'Include ZipCodes', 'geot' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'description'     => esc_html__( 'Type zip codes separated by commas.', 'geot' ),
			'tab_slug'        => 'geot',
		];

		$fields['ex_zipcodes'] = [
			'label'           => esc_html__( 'Exclude ZipCodes', 'geot' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'description'     => esc_html__( 'Type zip codes separated by comma.', 'geot' ),
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

		if ( empty( $in_zipcodes ) && empty( $ex_zipcodes ) ) {
			return true;
		}


		if ( geot_target_zip( $in_zipcodes, $ex_zipcodes ) ) {
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

		if ( empty( $in_zipcodes ) && empty( $ex_zipcodes ) ) {
			return $output;
		}


		return '<div class="geot-ajax geot-filter" data-action="zip_filter" data-filter="' . $in_zipcodes . '" data-ex_filter="' . $ex_zipcodes . '">' . $output . '</div>';
	}

}