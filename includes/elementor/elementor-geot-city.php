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
class Elementor_GeoCity {

	/**
	*
	* Get Fields in the Elementor Admin
	* @param  Class  $control
	*
	*/
	static function get_fields($control) {
		
		$control->start_controls_section(
			'cities_section',
			[
				'label' => __( 'Cities Settings', 'geot' ),
				'tab' => 'geot',
			]
		);


		$control->add_control(
			'in_header_cities',
			[
				'label' => __( 'Include', 'geot' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'in_help_cities',
			[
				//'label' => __( 'Important Note', 'geot' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __('Type city names separated by commas.', 'geot'),
				'content_classes' => 'elementor-descriptor',
			]
		);


		$control->add_control(
			'in_cities',
			[
				'label' => __( 'Cities', 'geot' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				//'placeholder' => __( 'Choose region name to show content to', 'geot' ),
			]
		);


		$control->add_control(
			'in_regions_cities',
			[
				'label' => __( 'Regions', 'geot' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => '',
				'options' => GeoTarget_Elementor::get_regions('cities'),
			]
		);

		$control->add_control(
			'ex_header_cities',
			[
				'label' => __( 'Exclude', 'geot' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'ex_help_cities',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __('Type city names separated by commas.', 'geot'),
				'content_classes' => 'elementor-descriptor',
			]
		);

		$control->add_control(
			'ex_cities',
			[
				'label' => __( 'Cities', 'geot' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
			]
		);

		$control->add_control(
			'ex_regions_cities',
			[
				'label' => __( 'Regions', 'geot' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => '',
				'options' => GeoTarget_Elementor::get_regions('cities'),
			]
		);

		$control->end_controls_section();
		
	}


	/**
	*
	* Conditional if it apply a render
	* @param  Array  $settings
	*
	*/
	static function is_render($settings) {

		extract( $settings );

		//$in_regions_i = $ex_regions_i = '';

		if( empty($in_cities) && empty($ex_cities) &&
			empty($in_regions_cities) && empty($ex_regions_cities)
		) return true;


		if ( geot_target_city( $in_cities, $in_regions_cities, $ex_cities, $ex_regions_cities ) )
			return true;
		
		return false;
	}


	/**
	*
	* To Ajax mode, print HTML before
	* @param  Array  $settings
	*
	*/
	static function ajax_before_render($settings) {

		$in_regions_i = $ex_regions_i = '';
		extract( $settings );

		if( empty($in_cities) && empty($ex_cities) &&
			empty($in_regions_cities) && empty($ex_regions_cities)
		) return;

		
		if( is_array($in_regions_cities) && count($in_regions_cities) > 0 )
			$in_regions_i = implode( ',', $in_regions_cities );

		if( is_array($ex_regions_cities) && count($ex_regions_cities) > 0 )
			$ex_regions_i = implode( ',', $ex_regions_cities );


		echo '<div class="geot-ajax geot-filter" data-action="city_filter" data-filter="' . $in_cities . '" data-region="' . $in_regions_i . '" data-ex_filter="' . $ex_cities . '" data-ex_region="' . $ex_regions_i . '">';
	}


	/**
	*
	* To Ajax mode, print HTML after
	* @param  Array  $settings
	*
	*/
	static function ajax_after_render($settings) {
		
		extract( $settings );

		if( empty($in_cities) && empty($ex_cities) &&
			empty($in_regions_cities) && empty($ex_regions_cities)
		) return;

		echo '</div>';
	}
	
}
?>