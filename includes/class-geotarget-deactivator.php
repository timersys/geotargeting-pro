<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://geotargetingwp.com/geotargeting-pro
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		global $wpdb;

		$current_version = get_option( 'geot_version' );

		// Only remove table if version 1.1 is being installed
		if( empty($current_version) || version_compare( '1.1', $current_version ) > 0 ) {
			$drop_table = "DROP TABLE `{$wpdb->base_prefix}geot_countries`;DROP TABLE `{$wpdb->base_prefix}geot_cities`;";

			$wpdb->query( $drop_table );
		}
		do_action('geotWP/deactivated');
	}

}
