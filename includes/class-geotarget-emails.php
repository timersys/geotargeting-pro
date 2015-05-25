<?php
/**
 * Filters helpers functions
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Damian Logghe <info@timersys.com>
 */
class Geotarget_Emails {

	/**
	 * Send email every two hours when user run out of queries in maxmind
	 */
	public static function OutOfQueriesException(){
		if( false === get_transient('geot_OutOfQueriesException') ) {
			set_transient( 'geot_OutOfQueriesException', true, 2 * HOUR_IN_SECONDS);
			$message = sprintf( __( 'Your <a href="%s">Maxmind account</a> have run out of queries. Please <a href="%s">add some</a> more to continue using MaxMind Api or delete api info to use free database. We will continue use free database for the time being.', 'geot' ), 'https://www.maxmind.com/?rId=timersys', 'https://www.maxmind.com/en/geoip2-precision-services?rId=timersys' );
			$subject = __( 'Geotargeting plugin Error!', 'geot');
			wp_mail( get_bloginfo('admin_email'), $subject, $message);
		}
	}

	public static function AuthenticationException() {
		if( false === get_transient('geot_AuthenticationException') ) {
			set_transient( 'geot_AuthenticationException', true, 2 * HOUR_IN_SECONDS);
			$message = sprintf( __( 'Your <a href="%s">Maxmind</a> login credentials are wrong. Please enter correct ones to continue using MaxMind Api. We will continue use free database for the time being.', 'geot' ), 'https://www.maxmind.com/?rId=timersys' );
			$subject = __( 'Geotargeting plugin Error!', 'geot');
			wp_mail( get_bloginfo('admin_email'), $subject, $message);
		}
	}
}