<?php
namespace GeotWP;

/**
 * Grab user IP from different possible sources
 * @return string
 */
function getUserIP() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '1.1.1.1';
	// cloudflare
	$ip = isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $ip;
	// reblaze
	$ip = isset( $_SERVER['X-Real-IP'] ) ? $_SERVER['X-Real-IP'] : $ip;
	// Sucuri
	$ip = isset( $_SERVER['HTTP_X_SUCURI_CLIENTIP'] ) ? $_SERVER['HTTP_X_SUCURI_CLIENTIP'] : $ip;
	// Ezoic
	$ip = isset( $_SERVER['X-FORWARDED-FOR'] ) ? $_SERVER['X-FORWARDED-FOR'] : $ip;
	// akamai
	$ip = isset( $_SERVER['True-Client-IP'] ) ? $_SERVER['True-Client-IP'] : $ip;
	// Clouways
	$ip = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $ip;
	// varnish trash ?
	$ip = str_replace( array( '::ffff:', ', 127.0.0.1'), '', $ip );
	// get varnish first ip
	$ip = strstr( $ip, ',') === false ? $ip : strstr( $ip, ',');

	return $ip;
}

/**
 * Check if session is running
 * @return bool
 */
function is_session_started()
{
	if ( php_sapi_name() !== 'cli' ) {
		if ( version_compare(phpversion(), '5.4.0', '>=') ) {
			return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
		} else {
			return session_id() === '' ? FALSE : TRUE;
		}
	}
	return FALSE;
}

function getCountryByIsoCode( $iso_code ){

}