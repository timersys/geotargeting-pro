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

class Divi_GeoCity {

	/**
	 * Add the actual fields
	 *
	 * @return array
	 */
	static function get_fields() {

		$fields['in_cities'] = [
						'label'				=> esc_html__('Include Cities','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type city names separated by comma.', 'geot' ),
						'tab_slug'			=> 'geot'
					];

		$fields['in_region_cities'] = [
						'label'				=> esc_html__('Include City Regions','geot'),
						'type'				=> 'multiple_checkboxes',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Choose region name to show content to.', 'geot' ),
						'options'			=> GeoTarget_Divi::get_regions('city'),
						'tab_slug'			=> 'geot',
					];

		$fields['ex_cities'] = [
						'label'				=> esc_html__('Exclude Cities','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type city names separated by comma.', 'geot' ),
						'tab_slug'			=> 'geot'
					];

		$fields['ex_region_cities'] = [
						'label'				=> esc_html__('Exclude City Regions','geot'),
						'type'				=> 'multiple_checkboxes',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Choose region name to show content to.', 'geot' ),
						'options'			=> GeoTarget_Divi::get_regions('city'),
						'tab_slug'			=> 'geot'
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

		if( empty($in_cities) && empty($ex_cities) &&
			empty($in_region_cities) && empty($ex_region_cities)
		) return true;


		$in_reg_countries = GeoTarget_Divi::format_regions($in_region_cities,'|', $regions);
		$ex_reg_countries = GeoTarget_Divi::format_regions($ex_region_cities,'|', $regions);

		if ( geot_target_city( $in_cities, $in_reg_countries, $ex_cities, $ex_reg_countries ) )
			return true;
		
		return false;
	}

}