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

class Divi_GeoState {

	/**
	 * Add the actual fields
	 *
	 * @return array
	 */
	static function get_fields() {

		$fields['in_state'] = [
						'label'				=> esc_html__('Include States','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type state names or ISO codes separated by comma.', 'geot' ),
						'tab_slug'			=> 'geot'
					];

		$fields['ex_state'] = [
						'label'				=> esc_html__('Exclude States','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type state names or ISO codes separated by comma.', 'geot' ),
						'tab_slug'			=> 'geot'
					];

		return $fields;
	}


}