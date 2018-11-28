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

// only uninstal regions/settings  If last plugin being uninstalled
if( isset( $opts['geot_uninstall']) && '1' == $opts['geot_uninstall'] && ! function_exists('geot') ) {
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
	GeotFunctions\geot_uninstall();
}
// Run if this plugin is being uninstalled and uninstall is checked
if( isset( $opts['geot_uninstall']) && '1' == $opts['geot_uninstall']  ) {
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
	try {
		//_delete all meta data
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key = '_menu_item_geot' OR meta_key = 'geot_options' OR meta_key = '_geot_post'" );
	} catch (Exception $e ){
	}
	// uncheck geot uninstall just in case after the plugin it's removed
	$opts = get_option('geot_settings');
	$opts['uninstall'] = '';
	update_option( 'geot_settings', $opts);
}
