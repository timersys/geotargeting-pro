<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @package    GeoTarget
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
$opts = get_option('geot_settings');

// only run If last plugin being uninstalled
if( isset( $opts['geot_uninstall']) && '1' == $opts['geot_uninstall'] && ! function_exists('geot') ) {
	require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
	GeotFunctions\uninstall();
}