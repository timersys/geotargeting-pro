<?php

class Geot_Helpers {

	private static $_user_is_targeted = array();

	/**
	 * Return geotarget posts
	 * @return array|null|object
	 */
	public static function get_geotarget_posts() {
		global $wpdb;

		$sql = "SELECT ID, pm.meta_value as geot_countries, pm2.meta_value as geot_options FROM $wpdb->posts p
LEFT JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
LEFT JOIN $wpdb->postmeta pm2 ON p.ID = pm2.post_id
WHERE p.post_status = 'publish'
AND pm.meta_key = '_geot_post'
AND pm2.meta_key = 'geot_options'
AND pm.meta_value != ''";

		return $wpdb->get_results( $sql );

	}

	/**
	 * Check if user is matched
	 *
	 * @param $opts From post metabox
	 *
	 * @param $post_id
	 *
	 * @return bool
	 */
	public static function user_is_targeted( $opts, $post_id ) {
		if( isset( self::$_user_is_targeted[$post_id] ) )
			return self::$_user_is_targeted[$post_id];

		$include_mode = ! empty( $opts['geot_include_mode'] ) ? $opts['geot_include_mode'] : 'include';
		$country_remove = $state_remove = $city_remove = null;

		if ( ! empty( $opts['country_code'] ) ||  ! empty( $opts['region'] ) ) {
			$country_remove = false;
			$countries  = ! empty( $opts['country_code'] ) ? $opts['country_code'] : '';
			$regions    = ! empty( $opts['region'] ) ? $opts['region'] : '';
			$target     = geot_target( $countries, $regions );
			if ( ( $include_mode == 'include' && ! $target ) || ( $include_mode == 'exclude' && $target ) )
				$country_remove = true;

		}

		if ( ! empty( $opts['cities'] ) ) {
			$city_remove = false;
			$cities = ! empty( $opts['cities'] ) ? $opts['cities'] : '';
			$target = geot_target_city( $cities );
			if ( ( $include_mode == 'include' && ! $target ) || ( $include_mode == 'exclude' && $target ) )
				$city_remove = true;
		}

		if ( ! empty( $opts['states'] ) ) {
			$state_remove = false;
			$states = ! empty( $opts['states'] ) ? $opts['states'] : '';
			$target = geot_target_state( $states );

			if ( ( $include_mode == 'include' && ! $target ) || ( $include_mode == 'exclude' && $target ) )
				$state_remove = true;

		}
		
		if( $include_mode == 'include' &&  ( $country_remove !== false && $city_remove !== false && $state_remove !== false ) )
			self::$_user_is_targeted[$post_id] = true;

		if( $include_mode == 'exclude' && ( $country_remove || $state_remove || $city_remove ) )
			self::$_user_is_targeted[$post_id] = true;

		return isset( self::$_user_is_targeted[$post_id] ) ? self::$_user_is_targeted[$post_id] : false;
	}
}
