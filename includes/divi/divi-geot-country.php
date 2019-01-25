<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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

class Divi_GeoCountry {

	/**
	 * Add the actual fields
	 *
	 * @return array
	 */
	static function get_fields() {

		$fields['in_countries'] = [
						'label'             => esc_html__('Include Countries','geot'),
						'type'              => 'text',
						'option_category'   => 'configuration',
						'description'       => esc_html__( 'Type country names or ISO codes separated by comma.', 'geot' ),
						'tab_slug'          => 'geot'
					];

		$fields['in_region_countries'] = [
						'label'             => esc_html__('Include Country Regions','geot'),
						'type'              => 'multiple_checkboxes',
						'option_category'   => 'configuration',
						'description'       => esc_html__( 'Choose region name to show content to.', 'geot' ),
						'options'           => GeoTarget_Divi::get_regions('country'),
						'option_category' => 'configuration',
						'tab_slug'          => 'geot'
					];

		$fields['ex_countries'] = [
						'label'             => esc_html__('Exclude Countries','geot'),
						'type'              => 'text',
						'option_category'   => 'configuration',
						'description'       => esc_html__( 'Type country names or ISO codes separated by comma.', 'geot' ),
						'tab_slug'          => 'geot'
					];

		$fields['ex_region_countries'] = [
						'label'             => esc_html__('Exclude Country Regions','geot'),
						'type'              => 'multiple_checkboxes',
						'option_category'   => 'configuration',
						'description'       => esc_html__( 'Choose region name to show content to.', 'geot' ),
						'options'           => GeoTarget_Divi::get_regions('country'),
						'tab_slug'          => 'geot'
					];

		return $fields;
	}


	/**
	 * Add the actual fields
	 *
	 * @return array
	 */
	static function is_render($settings, $regions) {

		extract( $settings );

		if( empty($in_countries) && empty($ex_countries) &&
			empty($in_region_countries) && empty($ex_region_countries)
		) return true;


		$in_reg_countries = GeoTarget_Divi::format_regions($in_region_countries,'|', $regions);
		$ex_reg_countries = GeoTarget_Divi::format_regions($ex_region_countries,'|', $regions);

		if ( geot_target( $in_countries, $in_reg_countries, $ex_countries, $ex_reg_countries ) )
			return true;
		
		return false;
	}

}