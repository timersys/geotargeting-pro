<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
* Elementor Extension
*
* @link       https://geotargetingwp.com/geotargeting-pro
* @since      1.6.3
*
* @package    GeoTarget
* @subpackage GeoTarget/includes
* @author     Damian Logghe
*/
class Elementor_GeoState {


	static function get_fields($control) {
		
		$control->start_controls_section(
			'states_section',
			[
				'label' => __( 'States Settings', 'geot' ),
				'tab' => 'geo',
			]
		);


		$control->add_control(
			'in_header_states',
			[
				'label' => __( 'Include', 'geot' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'in_help_states',
			[
				//'label' => __( 'Important Note', 'geot' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __('Type state names or ISO codes separated by commas.', 'geot'),
				'content_classes' => 'elementor-descriptor',
			]
		);

		$control->add_control(
			'in_states',
			[
				'label' => __( 'States', 'geot' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				//'placeholder' => __( 'Choose region name to show content to', 'geot' ),
			]
		);

		$control->add_control(
			'ex_header_states',
			[
				'label' => __( 'Exclude', 'geot' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'ex_help_states',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __('Type state names or ISO codes separated by commas.', 'geot'),
				'content_classes' => 'elementor-descriptor',
			]
		);

		$control->add_control(
			'ex_states',
			[
				'label' => __( 'States', 'geot' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
			]
		);

		$control->end_controls_section();

	}


	static function is_render($settings) {

		extract( $settings );

		if( empty($in_states) && empty($ex_states) 
		) return true;

		if ( geot_target_state( $in_states,  $ex_states ) )
			return true;
		
		return false;
	}
	
}
?>