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

		$fields['in_city'] = [
						'label'				=> esc_html__('Include Cities','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type city names separated by comma.', 'geot' ),
						'tab_slug'			=> 'geot'
					];

		$fields['in_region_city'] = [
						'label'				=> esc_html__('Include City Regions','geot'),
						'type'				=> 'multiple_checkboxes',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Choose region name to show content to.', 'geot' ),
						'options'			=> GeoTarget_Divi::get_regions('city'),
						'tab_slug'			=> 'geot',
					];

		$fields['ex_city'] = [
						'label'				=> esc_html__('Exclude Cities','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type city names separated by comma.', 'geot' ),
						'tab_slug'			=> 'geot'
					];

		$fields['ex_region_city'] = [
						'label'				=> esc_html__('Exclude City Regions','geot'),
						'type'				=> 'multiple_checkboxes',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Choose region name to show content to.', 'geot' ),
						'options'			=> GeoTarget_Divi::get_regions('city'),
						'tab_slug'			=> 'geot'
					];

		return $fields;
	}


}