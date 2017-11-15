<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://geotargetingwp.com/geotargeting-pro
 * @since      1.0.0
 *
 * @package    GeoTarget
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$opts = get_option( 'geot_settings' );

if( isset( $opts['geot_uninstall']) && '1' == $opts['geot_uninstall'] ) {
	// delete settings
	delete_option('geot_settings');
	delete_option('geot_version');
	// delete sql data
	global $wpdb;
	$cities_table = $wpdb->base_prefix . 'geot_cities';
	$countries_table = $wpdb->base_prefix . 'geot_countries';
	$wpdb->query( "DROP TABLE IF EXISTS $cities_table;DROP TABLE IF EXISTS $countries_table;");
}