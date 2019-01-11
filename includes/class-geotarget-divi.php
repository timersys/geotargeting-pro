<?php
/**
* Divi Extension
*
* @link       https://geotargetingwp.com/geotargeting-pro
* @since      1.6.3
*
* @package    GeoTarget
* @subpackage GeoTarget/includes
* @author     Damian Logghe
*/
class GeoTarget_Divi {

	/**
	 * @since   1.0.0
	 * @access  private
	 * @var     Array of plugin settings
	 */
	private $opts;
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $GeoTarget    The ID of this plugin.
	 */
	private $GeoTarget;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

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


	/**
	* Get Regions
	* @var	string 	$slug_region
	*/
	protected function get_regions($slug_region = 'country') {

		$dropdown_values = array();

		switch($slug_region) {
			case 'city': $regions = geot_city_regions(); break;
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
	* Register Blocks
	* @var
	*/
	public function add_tabs_to_section($tabs) {

		$new_tab = [];
		$new_tab['geot'] = esc_html__( 'Geotargeting', 'geot' );

		return array_merge($tabs, $new_tab);
	}

	public function add_field_to_section($fields_unprocessed) {

		$fields = [];
		$fields['in_country'] = [
						'label'				=> esc_html__('Include Countries','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type country names or ISO codes separated by comma.', 'geot' ),
						//'toggle_slug'		=> 'layout',
						'tab_slug'			=> 'geot'
					];

		$fields['in_region_country'] = [
						'label'				=> esc_html__('Include Country Regions','geot'),
						'type'				=> 'multiple_checkboxes',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Choose region name to show content to.', 'geot' ),
						'options'			=> $this->get_regions('country'),
						//'additional_att'  => 'disable_on',
						'option_category' => 'configuration',
						//'toggle_slug'		=> 'layout',
						'tab_slug'			=> 'geot'
					];

		$fields['ex_country'] = [
						'label'				=> esc_html__('Exclude Countries','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type country names or ISO codes separated by comma.', 'geot' ),
						//'toggle_slug'		=> 'layout',
						'tab_slug'			=> 'geot'
					];

		$fields['ex_region_country'] = [
						'label'				=> esc_html__('Exclude Country Regions','geot'),
						'type'				=> 'multiple_checkboxes',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Choose region name to show content to.', 'geot' ),
						'options'			=> $this->get_regions('country'),
						//'toggle_slug'		=> 'layout',
						'tab_slug'			=> 'geot'
					];


		$fields['in_city'] = [
						'label'				=> esc_html__('Include Cities','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type city names separated by comma.', 'geot' ),
						//'toggle_slug'		=> 'layout',
						'tab_slug'			=> 'geot'
					];

		$fields['in_region_city'] = [
						'label'				=> esc_html__('Include City Regions','geot'),
						'type'				=> 'multiple_checkboxes',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Choose region name to show content to.', 'geot' ),
						'options'			=> $this->get_regions('city'),
						//'toggle_slug'		=> 'layout',
						'tab_slug'			=> 'geot',
					];

		$fields['ex_city'] = [
						'label'				=> esc_html__('Exclude Cities','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type city names separated by comma.', 'geot' ),
						//'toggle_slug'		=> 'layout',
						'tab_slug'			=> 'geot'
					];

		$fields['ex_region_city'] = [
						'label'				=> esc_html__('Exclude City Regions','geot'),
						'type'				=> 'multiple_checkboxes',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Choose region name to show content to.', 'geot' ),
						'options'			=> $this->get_regions('city'),
						//'toggle_slug'		=> 'layout',
						'tab_slug'			=> 'geot'
					];


		$fields['in_state'] = [
						'label'				=> esc_html__('Include States','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type state names or ISO codes separated by comma.', 'geot' ),
						//'toggle_slug'		=> 'layout',
						'tab_slug'			=> 'geot'
					];

		$fields['ex_state'] = [
						'label'				=> esc_html__('Exclude States','geot'),
						'type'				=> 'text',
						'option_category'	=> 'configuration',
						'description'		=> esc_html__( 'Type state names or ISO codes separated by comma.', 'geot' ),
						//'toggle_slug'		=> 'layout',
						'tab_slug'			=> 'geot'
					];

		return array_merge($fields_unprocessed,$fields);
	}


	public function render_section($output, $render_slug, $module) {
		if( 'et_pb_section' !== $render_slug ) return $output;

		$have_countries = $have_cities = $have_states = 0;
		//$in_reg_countries = $ex_reg_countries = $in_reg_cities = $ex_reg_cities = array();

		$geot_opts = geot_pro_settings();

		$in_countries 	= esc_html($module->props['in_country']);
		$ex_countries 	= esc_html($module->props['ex_country']);
		$in_cities 		= esc_html($module->props['in_city']);
		$ex_cities 		= esc_html($module->props['ex_city']);
		$in_states 		= esc_html($module->props['in_state']);
		$ex_states 		= esc_html($module->props['ex_state']);

		$in_reg_countries 	= esc_html($module->props['in_region_country']);
		$ex_reg_countries 	= esc_html($module->props['ex_region_country']);
		$in_reg_cities 		= esc_html($module->props['in_region_city']);
		$ex_reg_cities 		= esc_html($module->props['ex_region_city']);


		// Countries
		if( !empty($in_countries) || !empty($ex_countries) ||
			!empty($in_reg_countries) || !empty($ex_reg_countries)
		) $have_countries = 1;

		// Cities
		if( !empty($in_cities) || !empty($ex_cities) ||
			!empty($in_reg_cities) || !empty($ex_reg_cities)
		) $have_cities = 1;
	
		// States
		if( !empty($in_states) || !empty($ex_states) )
			$have_states = 1;

		$reg_countries = array_values($this->get_regions('country'));
		$reg_cities = array_values($this->get_regions('city'));

		$in_reg_countries 	= $this->format_regions($in_reg_countries,'|', $reg_countries);
		$ex_reg_countries 	= $this->format_regions($ex_reg_countries,'|', $reg_countries);
		$in_reg_cities 		= $this->format_regions($in_reg_cities,'|', $reg_cities);
		$ex_reg_cities 		= $this->format_regions($ex_reg_cities,'|', $reg_cities);


		if( isset( $geot_opts['ajax_mode'] ) && $geot_opts['ajax_mode'] == '1' ) {

			$commas_in_reg_countries 	= implode(',', $in_reg_countries);
			$commas_ex_reg_countries 	= implode(',', $ex_reg_countries);
			$commas_in_reg_cities 		= implode(',', $in_reg_cities);
			$commas_ex_reg_cities 		= implode(',', $ex_reg_cities);

			// States
			if( $have_states == 1 )
				$output = '<div class="geot-ajax geot-filter" data-action="state_filter" data-filter="' . $in_states . '" data-ex_filter="' . $ex_states . '">' . $output . '</div>';

			// Cities
			if( $have_cities == 1 )
				$output = '<div class="geot-ajax geot-filter" data-action="city_filter" data-filter="' . $in_cities . '" data-region="' . $commas_in_reg_cities . '" data-ex_filter="' . $ex_cities . '" data-ex_region="' . $commas_ex_reg_cities . '">' .  $output . '</div>';

			// Countries
			if( $have_countries == 1 )
				$output = '<div class="geot-ajax geot-filter" data-action="country_filter" data-filter="' . $in_countries . '" data-region="' . $commas_in_reg_countries . '" data-ex_filter="' . $ex_countries . '" data-ex_region="' . $commas_ex_reg_countries . '">' .  $output  . '</div>';

			return $output;

		} else {

			$have_total = $have_countries + $have_cities + $have_states;
			$inside = 0;

			if( $have_countries == 1 &&
				geot_target( $in_countries, $in_reg_countries, $ex_countries, $ex_reg_countries ) )
				$inside++;

			if( $have_cities == 1 &&
				geot_target_city( $in_cities, $in_reg_cities, $ex_cities, $ex_reg_cities ) )
				$inside++;

			if( $have_states == 1 &&
				geot_target_state( $in_states, $ex_states ) )
				$inside++;

			if( $inside == $have_total )
				return $output;
		}

		return '';
	}



	protected function format_regions($check_multi, $separator = '|', $regions) {

		if( strpos($check_multi, $separator) === false )
			return array();

		$output_regions = array();
		
		foreach(explode($separator, $check_multi) as $key => $onoff ) {
			if( ($onoff == 'on' || $onoff == 'On') && isset($regions[$key]) )
				$output_regions[] = $regions[$key];
		}

		return $output_regions;
	}


}