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
	public static function user_is_targeted( $opts, $post_id, $cache = true ) {
		if( isset( self::$_user_is_targeted[$post_id] ) && $cache )
			return self::$_user_is_targeted[$post_id];

		$_user_is_targeted = false;

		$mode = ! empty( $opts['geot_include_mode'] ) ? $opts['geot_include_mode'] : 'include';
		$country_remove = $state_remove = $city_remove = false;
		$country_target = $state_target = $city_target = null;
		if ( ! empty( $opts['country_code'] ) ||  ! empty( $opts['region'] ) ) {
			$countries  = ! empty( $opts['country_code'] ) ? $opts['country_code'] : '';
			$regions    = ! empty( $opts['region'] ) ? $opts['region'] : '';
			$country_target     = geot_target( $countries, $regions );
			if ( $mode == 'exclude' && $country_target )
				$country_remove = true;

		}

		if ( ! empty( $opts['cities'] ) ) {
			$cities = ! empty( $opts['cities'] ) ? $opts['cities'] : '';
			$city_target = geot_target_city( $cities );
			if ( $mode == 'exclude' && $city_target )
				$city_remove = true;
		}

		if ( ! empty( $opts['states'] ) ) {
			$states = ! empty( $opts['states'] ) ? $opts['states'] : '';
			$state_target = geot_target_state( $states );

			if ( $mode == 'exclude' && $state_target )
				$state_remove = true;

		}
		if( $mode == 'include' ) {
			$_user_is_targeted = true;
			if ( ( $country_target || $state_target || $city_target ) || ($country_target === null && $state_target === null && $city_target === null) )
				$_user_is_targeted = false;
		}

		if( $mode == 'exclude' && ( $country_remove || $state_remove || $city_remove ) )
			$_user_is_targeted = true;


		return self::$_user_is_targeted[$post_id] = $_user_is_targeted;
	}
}
