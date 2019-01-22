<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
* Gutenberg Extension
*
* @link       https://geotargetingwp.com/geotargeting-pro
* @since      1.6.3
*
* @package    GeoTarget
* @subpackage GeoTarget/includes
* @author     Damian Logghe
*/
class GeoTarget_Elementor {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $GeoTarget       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $GeoTarget, $version ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;
		$this->opts = geot_settings();

	}


	public function enqueue_styles() {
		wp_enqueue_style( 'geo-elementor', GEOT_PLUGIN_URL . 'admin/css/admin.css', array(), $this->version );
	}

	public function register_init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) )
			return;

		//Register Tab
		\Elementor\Controls_Manager::add_tab(
			'geo',
			__( 'Geotargeting', 'geot' )
		);
	}


	/**
	* Get Regions
	* @var	string 	$slug_region
	*/
	static function get_regions($slug_region = 'country') {

		$dropdown_values = array();

		switch($slug_region) {
			case 'cities': $regions = geot_city_regions(); break;
			default: $regions = geot_country_regions();
		}
		
		if( !empty( $regions ) ) {
			foreach ( $regions as $r ) {
				if( isset( $r['name'] ) ) {
					$dropdown_values[$r['name']] = $r['name'];
				}
			}
		}

		return $dropdown_values;
	}


	/**
	* All Controls
	* @var	string 	$slug_region
	*/
	public function register_controls($control, $section_id, $args) {

		if( $control->get_name() == 'section' && $section_id == 'section_custom_css_pro' ) {
			$this->countries_fields($control);
			$this->cities_fields($control);
			$this->states_fields($control);
		}
	}


	/**
	* Controls Countries
	* @var	string 	$slug_region
	*/
	protected function countries_fields($control) {

		$control->start_controls_section(
			'countries_section',
			[
				'label' => __( 'Countries Settings', 'geot' ),
				'tab' => 'geo',
			]
		);


		$control->add_control(
			'in_header',
			[
				'label' => __( 'Include', 'geot' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'in_help',
			[
				//'label' => __( 'Important Note', 'geot' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __('Type country names or ISO codes separated by comma.', 'geot'),
				'content_classes' => 'elementor-descriptor',
			]
		);


		$control->add_control(
			'in_countries',
			[
				'label' => __( 'Countries', 'geot' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				//'placeholder' => __( 'Choose region name to show content to', 'geot' ),
			]
		);


		$control->add_control(
			'in_regions',
			[
				'label' => __( 'Regions', 'geot' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => '',
				'options' => GeoTarget_Elementor::get_regions('country'),
			]
		);

		$control->add_control(
			'ex_header',
			[
				'label' => __( 'Exclude', 'geot' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'ex_help',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __('Type country names or ISO codes separated by comma.', 'geot'),
				'content_classes' => 'elementor-descriptor',
			]
		);

		$control->add_control(
			'ex_countries',
			[
				'label' => __( 'Countries', 'geot' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
			]
		);

		$control->add_control(
			'ex_regions',
			[
				'label' => __( 'Regions', 'geot' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => '',
				'options' => GeoTarget_Elementor::get_regions('country'),
			]
		);

		$control->end_controls_section();
	}



	/**
	* Controls Cities
	* @var	string 	$slug_region
	*/
	protected function cities_fields($control) {

		$control->start_controls_section(
			'cities_section',
			[
				'label' => __( 'Cities Settings', 'geot' ),
				'tab' => 'geo',
			]
		);


		$control->add_control(
			'in_header',
			[
				'label' => __( 'Include', 'geot' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'in_help',
			[
				//'label' => __( 'Important Note', 'geot' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __('Type country names or ISO codes separated by comma.', 'geot'),
				'content_classes' => 'elementor-descriptor',
			]
		);


		$control->add_control(
			'in_countries',
			[
				'label' => __( 'Countries', 'geot' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				//'placeholder' => __( 'Choose region name to show content to', 'geot' ),
			]
		);


		$control->add_control(
			'in_regions',
			[
				'label' => __( 'Regions', 'geot' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => '',
				'options' => GeoTarget_Elementor::get_regions('country'),
			]
		);

		$control->add_control(
			'ex_header',
			[
				'label' => __( 'Exclude', 'geot' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$control->add_control(
			'ex_help',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __('Type country names or ISO codes separated by comma.', 'geot'),
				'content_classes' => 'elementor-descriptor',
			]
		);

		$control->add_control(
			'ex_countries',
			[
				'label' => __( 'Countries', 'geot' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
			]
		);

		$control->add_control(
			'ex_regions',
			[
				'label' => __( 'Regions', 'geot' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => '',
				'options' => GeoTarget_Elementor::get_regions('country'),
			]
		);

		$control->end_controls_section();
	}
}