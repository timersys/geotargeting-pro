<?php
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
class GeoTarget_Gutenberg {

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


	public function register_category($categories, $post) {

		return array_merge(
			$categories,
			array(
				array(
					'slug'	=> 'geot-block',
					'title'	=> __( 'Geotargeting', 'geot' ),
					'icon'	=> ''
				)
			)
		);
	}

	protected function get_regions($slug_region = 'country') {

		$dropdown_values = array();

		switch($slug_region) {
			case 'city': $regions = geot_city_regions(); break;
			default: $regions = geot_country_regions();
		}
		
		if( !empty( $regions ) ) {
			foreach ( $regions as $r ) {
				if( isset( $r['name'] ) ) {
					$dropdown_values[] = array( 'value' => $r['name'], 'label' => $r['name'] );
				}
			}
		}

		return $dropdown_values;
	}


	public function register_init() {
		register_block_type('geotargeting-pro/gutenberg-country',
							[ 'render_callback' => [$this, 'save_gutenberg_country'] ]
		);

		register_block_type('geotargeting-pro/gutenberg-city',
							[ 'render_callback' => [$this, 'save_gutenberg_city'] ]
		);

		register_block_type('geotargeting-pro/gutenberg-state',
							[ 'render_callback' => [$this, 'save_gutenberg_state'] ]
		);		
	}

	public function save_gutenberg_country($attributes, $content) {

		$in_countries = $ex_countries = $in_regions = $ex_regions = $in_regions_i = $ex_regions_i = '';
		
		extract( $attributes );

		if( is_array($in_regions) && count($in_regions) > 0 )
			$in_regions_i = implode(',',$in_regions);

		if( is_array($ex_regions) && count($ex_regions) > 0 )
			$ex_regions_i = implode(',',$ex_regions);

		$geot_opts = geot_pro_settings();

		if( isset( $geot_opts['ajax_mode'] ) && $geot_opts['ajax_mode'] == '1' ) {
			return '<div class="geot-ajax geot-filter" data-action="country_filter" data-filter="' . $in_countries . '" data-region="' . $in_regions_i . '" data-ex_filter="' . $ex_countries . '" data-ex_region="' . $ex_regions_i . '">' .  $content  . '</div>';
		} else {
			if ( geot_target( $in_countries, $in_regions, $ex_countries, $ex_regions ) ) {
				return $content;
			}
		}

		return '';
	}

	public function save_gutenberg_city($attributes, $content) {
		$in_cities = $ex_cities = $in_regions = $ex_regions = $in_regions_i = $ex_regions_i = '';
		
		extract( $attributes );

		if( is_array($in_regions) && count($in_regions) > 0 )
			$in_regions_i = implode(',',$in_regions);

		if( is_array($ex_regions) && count($ex_regions) > 0 )
			$ex_regions_i = implode(',',$ex_regions);

		$geot_opts = geot_pro_settings();

		if( isset( $geot_opts['ajax_mode'] ) && $geot_opts['ajax_mode'] == '1' ) {
			return '<div class="geot-ajax geot-filter" data-action="city_filter" data-filter="' . $in_cities . '" data-region="' . $in_regions_i . '" data-ex_filter="' . $ex_cities . '" data-ex_region="' . $ex_regions_i . '">' .  $content . '</div>';
		} else {
			if ( geot_target_city( $in_cities, $in_regions, $ex_cities, $ex_regions ) ) {
				return $content;
			}
		}

		return '';
	}


	public function save_gutenberg_state($attributes, $content) {
		$in_states = $ex_states = '';
		
		extract( $attributes );

		$geot_opts = geot_pro_settings();

		if( isset( $geot_opts['ajax_mode'] ) && $geot_opts['ajax_mode'] == '1' ) {
			return '<div class="geot-ajax geot-filter" data-action="state_filter" data-filter="' . $in_states . '" data-ex_filter="' . $ex_states . '">' . $content . '</div>';
		} else {
			if ( geot_target_state( $in_states, $ex_states ) ) {
				return $content;
			}
		}

		return '';
	}


	public function register_block() {

		/**********************
			JS to Country
		***********************/
		$localize_country = array(
								'icon'		=> GEOT_PLUGIN_URL . '/admin/img/world.png',
								'regions'	=> $this->get_regions('countries'),
							);
		
		wp_enqueue_script(
			'gutenberg-geo-country',
			GEOT_PLUGIN_URL . '/includes/gutenberg/gutenberg-geot.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-editor' )
		);

		wp_localize_script('gutenberg-geo-country', 'geotcountry', $localize_country );


		/**********************
			JS to City
		***********************/
		$localize_city = array(
							'icon'		=> GEOT_PLUGIN_URL . '/admin/img/cities.png',
							'regions'	=> $this->get_regions('cities'),
						);

		wp_enqueue_script(
			'gutenberg-geo-city',
			GEOT_PLUGIN_URL . '/includes/gutenberg/gutenberg-geot-city.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-editor' )
		);
		wp_localize_script('gutenberg-geo-city', 'geotcity', $localize_city );


		/**********************
			JS to State
		***********************/
		$localize_state = array(
							'icon'	=> GEOT_PLUGIN_URL . '/admin/img/states.png',
						);

		wp_enqueue_script(
			'gutenberg-geo-state',
			GEOT_PLUGIN_URL . '/includes/gutenberg/gutenberg-geot-state.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-editor' )
		);
		wp_localize_script('gutenberg-geo-state', 'geotstate', $localize_state );
	}
}
