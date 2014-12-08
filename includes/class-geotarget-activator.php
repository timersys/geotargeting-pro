<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$create_table = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}Maxmind_geoIP` (
	`id`	 		INT(1) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, -- the id just for numeric
	`maxmind_ipstart` 	VARCHAR(50) COLLATE UTF8_GENERAL_CI NOT NULL, -- the ip start from maxmind data
	`maxmind_ipend` 	VARCHAR(50) COLLATE UTF8_GENERAL_CI NOT NULL, -- the ip end of maxmind data
	`maxmind_locid_start`  	INT(1) UNSIGNED ZEROFILL NOT NULL, -- the start of maxmind location id 
	`maxmind_locid_end` 	INT(1) UNSIGNED ZEROFILL NOT NULL, -- the end of maxmind location id
	`maxmind_country_code` 	VARCHAR(4) COLLATE UTF8_GENERAL_CI NOT NULL, -- the country code
	`maxmind_country` 	VARCHAR(100) COLLATE UTF8_GENERAL_CI NOT NULL, -- the country name
 
	PRIMARY KEY( `id`,`maxmind_ipstart`,`maxmind_ipend`, `maxmind_locid_end`, `maxmind_country` )
 
) DEFAULT CHARSET=UTF8 COLLATE=UTF8_GENERAL_CI AUTO_INCREMENT=1 ;";
		
		$csv_file = dirname( __FILE__ ) . '/GeoIPCountryWhois.csv';
 
		$load_data = "LOAD DATA INFILE '{$csv_file}' INTO TABLE `{$wpdb->prefix}Maxmind_geoIP` FIELDS TERMINATED BY ',' ENCLOSED BY '\"' ESCAPED BY '\\\' LINES TERMINATED BY '\\n' ( `maxmind_ipstart` , `maxmind_ipend` , `maxmind_locid_start` , `maxmind_locid_end` , `maxmind_country_code` , `maxmind_country`);";
			
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $create_table );
		$wpdb->query( $load_data );
	}

}
