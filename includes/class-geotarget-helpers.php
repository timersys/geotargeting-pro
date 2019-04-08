<?php

class Geot_Helpers {

	private static $_user_is_targeted = array();
	private static $_geotarget_posts = array();

	/**
	 * Return geotarget posts
	 * @return array|null|object
	 */
	public static function get_geotarget_posts() {
		global $wpdb;

		if( !empty(self::$_geotarget_posts) )
			return self::$_geotarget_posts;

		$sql = "SELECT ID, pm2.meta_id as geot_meta_id, pm.meta_value as geot_countries, pm2.meta_value as geot_options FROM $wpdb->posts p
LEFT JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
LEFT JOIN $wpdb->postmeta pm2 ON p.ID = pm2.post_id
WHERE p.post_status = 'publish'
AND pm.meta_key = '_geot_post'
AND pm2.meta_key = 'geot_options'
AND pm.meta_value != ''";

		return self::$_geotarget_posts = $wpdb->get_results( $sql );

	}

	/**
	 * Check if user is matched
	 *
	 * @param $opts From post metabox
	 *
	 * @param $post_id
	 *
	 * @param bool $cache
	 *
	 * @return bool
	 */
	public static function user_is_targeted( $opts, $post_id, $cache = true ) {
		if( isset( self::$_user_is_targeted[$post_id] ) && $cache )
			return self::$_user_is_targeted[$post_id];


		$_user_is_targeted = false;

		$mode = ! empty( $opts['geot_include_mode'] ) ? $opts['geot_include_mode'] : 'include';
		$country_remove = $state_remove = $city_remove = $zipcode_remove = false;
		$country_target = $state_target = $city_target = $zipcode_target = null;
		if ( ! empty( $opts['country_code'] ) ||  ! empty( $opts['region'] ) ) {
			$countries  = ! empty( $opts['country_code'] ) ? $opts['country_code'] : '';
			$regions    = ! empty( $opts['region'] ) ? $opts['region'] : '';
			$country_target     = geot_target( $countries, $regions );
			if ( $mode == 'exclude' && $country_target )
				$country_remove = true;

		}

		if ( ! empty( $opts['cities'] ) ||  ! empty( $opts['city_region'] ) ) {
			$cities 	= ! empty( $opts['cities'] ) ? $opts['cities'] : '';
			$regions 	= ! empty( $opts['city_region'] ) ? $opts['city_region'] : '';
			$city_target     = geot_target_city( $cities, $regions );
			if ( $mode == 'exclude' && $city_target )
				$city_remove = true;

		}
		
		if ( ! empty( $opts['states'] ) ) {
			$states = ! empty( $opts['states'] ) ? $opts['states'] : '';
			$state_target = geot_target_state( $states );

			if ( $mode == 'exclude' && $state_target )
				$state_remove = true;

		}
		if ( ! empty( $opts['zipcodes'] ) ) {
			$zipcodes = ! empty( $opts['zipcodes'] ) ? $opts['zipcodes'] : '';
			$zipcode_target = geot_target_zip( $zipcodes );

			if ( $mode == 'exclude' && $zipcode_target )
				$zipcode_remove = true;

		}
		if( $mode == 'include' ) {
			$_user_is_targeted = true;
			if ( ( $country_target || $state_target || $city_target || $zipcode_target ) ||
				 ( $country_target === null && $state_target === null && $city_target === null && $zipcode_target === null )
			) $_user_is_targeted = false;
		}

		if( $mode == 'exclude' && ( $country_remove || $state_remove || $city_remove || $zipcode_remove ) )
			$_user_is_targeted = true;

		return self::$_user_is_targeted[$post_id] = $_user_is_targeted;
	}

	/**
	 * Get post meta option
	 * @param  int $post_id [description]
	 * @return array
	 */
	public static function get_cpt_options( $post_id ) {

		$opts = get_post_meta( $post_id, 'geot_options', true );
		if( ! $opts )
			return array();
		return $opts;

	}

	/**
	 * Return available posts types. Used in filters
	 * @param  array  $exclude    cpt to explude
	 * @param  array  $include    cpts to include
	 * @return array  Resulting cpts
	 */
	public static function get_post_types( $exclude = array(), $include = array() ) 	{

		// get all custom post types
		$post_types = get_post_types();

		// core include / exclude
		$spu_includes = array_merge( array(), $include );
		$spu_excludes = array_merge( array( 'spucpt', 'acf', 'revision', 'nav_menu_item', 'attachment' ), $exclude );

		// include
		foreach( $spu_includes as $p )
		{
			if( post_type_exists($p) )
			{
				$post_types[ $p ] = $p;
			}
		}

		// exclude
		foreach( $spu_excludes as $p )
		{
			unset( $post_types[ $p ] );
		}

		return $post_types;

	}




	/**
	 * Check if user is matched in country
	 *
	 * @param $opts From metabox
	 *
	 * @return bool
	 */
	public static function is_targeted_country( $opts ) {

		extract($opts);

		if ( empty( $in_countries ) && empty( $ex_countries ) &&
		     count( $in_countries_regions ) == 0 && count( $ex_countries_regions ) == 0
		) {
			return true;
		}

		if ( geot_target( $in_countries, $in_countries_regions, $ex_countries, $ex_countries_regions ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Check if user is matched in city
	 *
	 * @param $opts From metabox
	 *
	 * @return bool
	 */
	public static function is_targeted_city( $opts ) {

		extract($opts);

		if ( empty( $in_cities ) && empty( $ex_cities ) &&
		     count( $in_cities_regions ) == 0 && count( $ex_cities_regions ) == 0
		) {
			return true;
		}


		if ( geot_target_city( $in_cities, $in_cities_regions, $ex_cities, $ex_cities_regions ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Check if user is matched in state
	 *
	 * @param $opts From metabox
	 *
	 * @return bool
	 */
	public static function is_targeted_state( $opts ) {

		extract($opts);

		if ( empty( $in_states ) && empty( $ex_states ) ) {
			return true;
		}


		if ( geot_target_state( $in_states, $ex_states ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Check if user is matched in zipcode
	 *
	 * @param $opts From metabox
	 *
	 * @return bool
	 */
	public static function is_targeted_zipcode( $opts ) {

		extract($opts);

		if ( empty( $in_zipcodes ) && empty( $ex_zipcodes ) ) {
			return true;
		}


		if ( geot_target_zip( $in_zipcodes, $ex_zipcodes ) ) {
			return true;
		}

		return false;
	}
}
