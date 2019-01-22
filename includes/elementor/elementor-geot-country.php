<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_Country extends \Elementor\Widget_Base {

	/**
	 * Section predefined columns presets.
	 *
	 * Holds the predefined columns width for each columns count available by
	 * default by Elementor. Default is an empty array.
	 *
	 * Note that when the user creates a section he can define custom sizes for
	 * the columns. But Elementor sets default values for predefined columns.
	 *
	 * For example two columns 50% width each one, or three columns 33.33% each
	 * one. This property hold the data for those preset values.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var array Section presets.
	 */
	private static $presets = [];

	/**
	 * Get element type.
	 * Retrieve the element type, in this case `section`.
	 *
	 * @since 2.1.0
	 * @access public
	 * @static
	 *
	 * @return string The type.
	 */
	/*public static function get_type() {
		return 'column';
	}*/

	/**
	 * Retrieve the widget name.
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'geot-country';
	}
	/**
	 * Retrieve the widget title.
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Target Countries' , 'geot' );
	}
	/**
	 * Retrieve the widget icon.
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-globe';
	}
	/**
	 * Retrieve the list of categories the widget belongs to.
	 * Used to determine where to display the widget in the editor.
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'geotargeting' ];
	}
	/**
	 * Retrieve the list of scripts the widget depended on.
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget scripts dependencies.
	 */
	/*public function get_script_depends() {
		return [ 'elementor-hello-world' ];
	}*/

	/**
	 * Register the widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Target Countries Settings', 'geot' ),
				//'tab' => \Elementor\Controls_Manager::GEO,
				'tab' => 'geo',
			]
		);


		$this->add_control(
			'in_header',
			[
				'label' => __( 'Include', 'geot' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'in_help',
			[
				//'label' => __( 'Important Note', 'geot' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __('Type country names or ISO codes separated by comma.', 'geot'),
				'content_classes' => 'elementor-descriptor',
			]
		);


		$this->add_control(
			'in_countries',
			[
				'label' => __( 'Countries', 'geot' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				//'placeholder' => __( 'Choose region name to show content to', 'geot' ),
			]
		);


		$this->add_control(
			'in_regions',
			[
				'label' => __( 'Regions', 'geot' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => '',
				'options' => GeoTarget_Elementor::get_regions('country'),
				/*'selectors' => [
					'{{WRAPPER}} .title' => 'in_regions	: {{VALUE}};',
				],*/
			]
		);

		$this->add_control(
			'ex_header',
			[
				'label' => __( 'Exclude', 'geot' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'ex_help',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __('Type country names or ISO codes separated by comma.', 'geot'),
				'content_classes' => 'elementor-descriptor',
			]
		);

		$this->add_control(
			'ex_countries',
			[
				'label' => __( 'Countries', 'geot' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
			]
		);

		$this->add_control(
			'ex_regions',
			[
				'label' => __( 'Regions', 'geot' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => '',
				'options' => GeoTarget_Elementor::get_regions('country'),
				/*'selectors' => [
					'{{WRAPPER}} .title' => 'in_regions	: {{VALUE}};',
				],*/
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render widget
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render( ) {
		$settings = $this->get_settings_for_display();

		echo '<pre>';
		//print_r($settings);
		echo 'Hello';
		echo '</pre>';
	}


	/**
	 * Get initial config.
	 *
	 * Retrieve the current section initial configuration.
	 *
	 * Adds more configuration on top of the controls list, the tabs assigned to
	 * the control, element name, type, icon and more. This method also adds
	 * section presets.
	 *
	 * @since 1.0.10
	 * @access protected
	 *
	 * @return array The initial config.
	 */
	/*protected function _get_initial_config() {
		$config = parent::_get_initial_config();
		$config['presets'] = self::get_presets();
		return $config;
	}*/

	/**
	 * Get presets.
	 *
	 * Retrieve a specific preset columns for a given columns count, or a list
	 * of all the preset if no parameters passed.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param int $columns_count Optional. Columns count. Default is null.
	 * @param int $preset_index  Optional. Preset index. Default is null.
	 *
	 * @return array Section presets.
	 */
	/*public static function get_presets( $columns_count = 1, $preset_index = null ) {
		if ( ! self::$presets ) {
			self::init_presets();
		}
		$presets = self::$presets;
		if ( null !== $columns_count ) {
			$presets = $presets[ $columns_count ];
		}
		if ( null !== $preset_index ) {
			$presets = $presets[ $preset_index ];
		}
		return $presets;
	}*/

	/**
	 * Initialize presets.
	 *
	 * Initializing the section presets and set the number of columns the
	 * section can have by default. For example a column can have two columns
	 * 50% width each one, or three columns 33.33% each one.
	 *
	 * Note that Elementor sections have default section presets but the user
	 * can set custom number of columns and define custom sizes for each column.
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	/*public static function init_presets() {
		$additional_presets = [
			2 => [
				[
					'preset' => [ 33, 66 ],
				],
				[
					'preset' => [ 66, 33 ],
				],
			],
			3 => [
				[
					'preset' => [ 25, 25, 50 ],
				],
				[
					'preset' => [ 50, 25, 25 ],
				],
				[
					'preset' => [ 25, 50, 25 ],
				],
				[
					'preset' => [ 16, 66, 16 ],
				],
			],
		];
		foreach ( range( 1, 10 ) as $columns_count ) {
			self::$presets[ $columns_count ] = [
				[
					'preset' => [],
				],
			];
			$preset_unit = floor( 1 / $columns_count * 100 );
			for ( $i = 0; $i < $columns_count; $i++ ) {
				self::$presets[ $columns_count ][0]['preset'][] = $preset_unit;
			}
			if ( ! empty( $additional_presets[ $columns_count ] ) ) {
				self::$presets[ $columns_count ] = array_merge( self::$presets[ $columns_count ], $additional_presets[ $columns_count ] );
			}
			foreach ( self::$presets[ $columns_count ] as $preset_index => & $preset ) {
				$preset['key'] = $columns_count . $preset_index;
			}
		}
	}*/
}
?>