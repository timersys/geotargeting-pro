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
	 * @param $opts From post metabox
	 *
	 * @return boolean
	 */
	public static function user_is_targeted( $opts, $post_id ) {
		if( isset( self::$_user_is_targeted[$post_id] ) )
			return self::$_user_is_targeted[$post_id];

		$include_mode = ! empty( $opts['geot_include_mode'] ) ? $opts['geot_include_mode'] : 'include';
		if ( ! empty( $opts['country_code'] ) ||  ! empty( $opts['region'] ) ) {

			$countries  = ! empty( $opts['country_code'] ) ? $opts['country_code'] : '';
			$regions    = ! empty( $opts['region'] ) ? $opts['region'] : '';
			$target     = geot_target( $countries, $regions );
			if ( $include_mode == 'include' && ! $target )
				self::$_user_is_targeted[$post_id] = true;

			if ( $include_mode != 'include' && $target )
				self::$_user_is_targeted[$post_id] = true;

		}
		if ( ! empty( $opts['cities'] ) ) {

			$cities = ! empty( $opts['cities'] ) ? $opts['cities'] : '';
			$target = geot_target_city( $cities );
			if ( $include_mode == 'include' && ! $target )
				self::$_user_is_targeted[$post_id] = true;

			if ( $include_mode != 'include' && $target )
				self::$_user_is_targeted[$post_id] = true;
		}

		if ( ! empty( $opts['states'] ) ) {
			$states = ! empty( $opts['states'] ) ? $opts['states'] : '';
			$target = geot_target_state( $states );

			if ( $include_mode == 'include' && ! $target )
				self::$_user_is_targeted[$post_id] = true;

			if ( $include_mode != 'include' && $target )
				self::$_user_is_targeted[$post_id] = true;
		}
		return isset( self::$_user_is_targeted[$post_id] ) ? self::$_user_is_targeted[$post_id] : false;
	}
}