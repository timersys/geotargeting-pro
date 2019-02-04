<?php
/**
 * Adds GeoTarget to categories
 * @since  1.8
 */
class GeoTarget_Categories {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $GeoTarget The ID of this plugin.
	 */
	private $GeoTarget;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * @since   1.6
	 * @access  private
	 * @var     Array of plugin settings
	 */
	private $opts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string $GeoTarget The name of this plugin.
	 * @var      string $version The version of this plugin.
	 */
	public function __construct( $GeoTarget, $version ) {

		$this->GeoTarget = $GeoTarget;
		$this->version   = $version;
		$this->opts      = geot_settings();
		$this->geot_opts      = geot_pro_settings();
	}

	/**
	 * Render settings Category
	 */
	public function edit_category_fields($tag) {

		$extra = get_term_meta($tag->term_id,'geot', true);
		$geot = geot_pro_format($extra);


		$regions_countries = wp_list_pluck( geot_country_regions(), 'name' );
		$regions_cities = wp_list_pluck( geot_city_regions(), 'name' );

		include_once GEOT_PLUGIN_DIR . 'admin/partials/metabox-category.php';
	}


	/**
	* Save Settings Category
	*/
	public function save_category_fields($term_id) {
		if( isset($_POST['geot']) ) {			

			$array_geot = geot_pro_format( $_POST['geot'] );

			$without = array_filter(array_values($array_geot));

			if( !empty($without) )
				update_term_meta($term_id, 'geot', $array_geot);
			else
				delete_term_meta($term_id, 'geot');
		}
	}


	/**
	* Pre Get Post
	*/
	public function pre_get_posts($query) {

		if ( !is_admin() && $query->is_main_query() ) {

			$cat_exclude = [];
			$cats_ids = get_categories(array('fields' => 'ids', 'geot' => true));

			foreach( $cats_ids as $term_id ) {
				$geot = get_term_meta($term_id,'geot', true);

				if( !$geot ) continue;

				if ( ! Geot_Helpers::is_targeted_country( $geot ) ||
			     ! Geot_Helpers::is_targeted_city( $geot ) ||
			     ! Geot_Helpers::is_targeted_state( $geot ) ||
			     ! Geot_Helpers::is_targeted_zipcode( $geot )
				) $cat_exclude[] = $term_id*(-1);
			}
			
			if( count($cat_exclude) > 0 )
				$query->set( 'cat', implode(',',$cat_exclude) );
		}
	}


	/**
	* Get Terms Hook
	*/
	public function get_terms($terms, $taxonomies, $args, $term_query) {

		if( !is_admin() && !isset($args['geot']) &&
			is_array($taxonomies) && in_array('category', $taxonomies)
		) {
			foreach( $terms as $id => $term ) {
				$geot = get_term_meta($term->term_id, 'geot', true);

				if( !$geot ) continue;

				if ( ! Geot_Helpers::is_targeted_country( $geot ) ||
			     ! Geot_Helpers::is_targeted_city( $geot ) ||
			     ! Geot_Helpers::is_targeted_state( $geot ) ||
			     ! Geot_Helpers::is_targeted_zipcode( $geot )
				) unset($terms[$id]);
			}
		}

		return $terms;
	}
}
?>