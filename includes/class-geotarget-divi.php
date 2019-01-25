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
	 * Get Modules
	 * @return array
	 */
	public function get_modules() {

		$array_modules = array(
							'et_pb_section',
							'et_pb_row',
							'et_pb_row_inner',
							'et_pb_column',
							'et_pb_accordion',
							'et_pb_audio',
							'et_pb_counters',
							'et_pb_blog',
							'et_pb_blurb',
							'et_pb_button',
							'et_pb_circle_counter',
							'et_pb_code',
							'et_pb_comments',
							'et_pb_contact_form',
							'et_pb_countdown_timer',
							'et_pb_cta',
							'et_pb_divider',
							'et_pb_filterable_portfolio',
							'et_pb_fullwidth_code',
							'et_pb_fullwidth_header',
							'et_pb_fullwidth_image',
							'et_pb_fullwidth_map',
							'et_pb_fullwidth_menu',
							'et_pb_fullwidth_portfolio',
							'et_pb_fullwidth_post_slider',
							'et_pb_fullwidth_post_title',
							'et_pb_fullwidth_slider',
							'et_pb_gallery',
							'et_pb_image',
							'et_pb_login',
							'et_pb_map',
							'et_pb_number_counter',
							'et_pb_portfolio',
							'et_pb_post_slider',
							'et_pb_post_title',
							'et_pb_post_nav',
							'et_pb_pricing_tables',
							'et_pb_search',
							'et_pb_shop',
							'et_pb_sidebar',
							'et_pb_signup',
							'et_pb_slider',
							'et_pb_social_media_follow',
							'et_pb_tabs',
							'et_pb_team_member',
							'et_pb_testimonial',
							'et_pb_text',
							'et_pb_toggle',
							'et_pb_video',
							'et_pb_video_slider',
							'et_pb_accordion_item',
							'et_pb_counter',
							'et_pb_contact_field',
							'et_pb_map_pin',
							'et_pb_pricing_table',
							'et_pb_signup_custom_field',
							'et_pb_slide',
							'et_pb_social_media_follow_network',
							'et_pb_tab',
						);

		return apply_filters('geot/divi/get_modules', $array_modules);
	}

	/**
	 * Get Regions
	 * @param  string $slug_region
	 * @return array
	 */
	static function get_regions($slug_region = 'country') {

		$dropdown_values = [];

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
	 * Module Init
	 *
	 * @return array
	 */
	public function module_init() {
		require_once GEOT_PLUGIN_DIR . 'includes/divi/divi-geot-country.php';
		require_once GEOT_PLUGIN_DIR . 'includes/divi/divi-geot-city.php';
		require_once GEOT_PLUGIN_DIR . 'includes/divi/divi-geot-state.php';
	}


	/**
	 * Register Tabs
	 * @var
	 * @return array
	 */
	public function add_tabs($tabs) {

		$new_tab = [];
		$new_tab['geot'] = esc_html__( 'Geotargeting', 'geot' );

		return apply_filters( 'geot/divi/add_tabs', array_merge($tabs, $new_tab) );
	}

	/**
	 * Add the actual fields
	 * @param $fields_unprocessed
	 *
	 * @return array
	 */
	public function get_fields($fields_unprocessed) {

		$fields_geot = [];

		$fields_country = Divi_GeoCountry::get_fields();
		$fields_city 	= Divi_GeoCity::get_fields();
		$fields_states 	= Divi_GeoState::get_fields();

		$fields_geot = array_merge($fields_unprocessed, $fields_country, $fields_city, $fields_states);

		return apply_filters( 'geot/divi/get_fields', $fields_geot );
	}


	/**
	 * @param $output
	 * @param $render_slug
	 * @param $module
	 *
	 * @return string
	 */
	public function render($output, $render_slug, $module) {

		$reg_countries 	= array_values($this->get_regions('country'));
		$reg_cities 	= array_values($this->get_regions('city'));

		$geot_opts = geot_pro_settings();

		if( isset( $geot_opts['ajax_mode'] ) && $geot_opts['ajax_mode'] == '1' ) {

		} else {

			if( !Elementor_GeoCountry::is_render($module->props, $reg_countries) ||
				!Elementor_GeoCity::is_render($module->props, $reg_cities) ||
				!Elementor_GeoState::is_render($module->props)
			) return '';


			return $output;
		}




		if( 'et_pb_section' !== $render_slug )
			return $output;

		$have_countries = $have_cities = $have_states = 0;
		//$in_reg_countries = $ex_reg_countries = $in_reg_cities = $ex_reg_cities = [];

		$geot_opts = geot_pro_settings();

		$in_countries 	= esc_attr($module->props['in_country']);
		$ex_countries 	= esc_attr($module->props['ex_country']);
		$in_cities 		= esc_attr($module->props['in_city']);
		$ex_cities 		= esc_attr($module->props['ex_city']);
		$in_states 		= esc_attr($module->props['in_state']);
		$ex_states 		= esc_attr($module->props['ex_state']);

		$in_reg_countries 	= esc_attr($module->props['in_region_country']);
		$ex_reg_countries 	= esc_attr($module->props['ex_region_country']);
		$in_reg_cities 		= esc_attr($module->props['in_region_city']);
		$ex_reg_cities 		= esc_attr($module->props['ex_region_city']);


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

		// AJAX MODE only allow one geotargeting at a time
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


	/**
	 * Fromat regions and normalize
	 * @param $check_multi
	 * @param string $separator
	 * @param $regions
	 *
	 * @return array
	 */
	static function format_regions($check_multi, $separator = '|', $regions) {

		if( strpos($check_multi, $separator) === false )
			return [];

		$output_regions = [];
		
		foreach(explode($separator, $check_multi) as $key => $onoff ) {
			if( strtolower($onoff) == 'on'  && isset($regions[$key]) )
				$output_regions[] = $regions[$key];
		}

		return $output_regions;
	}

}