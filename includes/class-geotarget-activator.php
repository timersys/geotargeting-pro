<?php

/**
 * Fired during plugin activation
 *
 * @link       https://geotargetingwp.com/geotargeting-pro
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


		if( version_compare(PHP_VERSION, '5.6' ) < 0 ) {

			deactivate_plugins( GEOT_PLUGIN_FILE );
			wp_die(
				'<p>' . __( 'Hey, we\'ve noticed that you\'re running an outdated version of PHP. PHP is the programming language that WordPress and this plugin are built on. The version that is currently used for your site is no longer supported. Newer versions of PHP are both faster and more secure. In fact, your version of PHP no longer receives security updates.' ) . '</p>' .
				'<p>' . __( 'Geotargeting PRO requires at least PHP 5.6 and you are running PHP ' ) . PHP_VERSION . '</p>'
			);
		}
		$current_version = get_option( 'geot_version' );

		// Upgrade post database
		if( $current_version && ! get_option( 'geot_posts_upgrade' ) ){
			self::posts_upgrade();
		}

		GeotFunctions\add_countries_to_db();

		// update version number to current one
		update_option( 'geot_version', GEOT_VERSION);

		do_action('geotWP/activated');
	}


	/**
	 * Add mising _geot_post introduced in 1.8 to old posts
	 * @return [type] [description]
	 */
	protected static function posts_upgrade() {
		global $wpdb;

		// grab all publish posts without _geot_post postmeta
		$posts = $wpdb->get_results("SELECT p.ID, pm.meta_value as geot_options FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm ON pm.post_id = p.ID  WHERE p.post_status = 'publish' AND pm.meta_key = 'geot_options'  AND p.ID NOT IN (  SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_geot_post' GROUP BY post_id ) ");
		// Loop all posts and check if( !empty( $opts['country_code'] ) || !empty( $opts['region'] ) || !empty( $opts['cities'] ) || !empty( $opts['state'] ) )
		$to_migrate = array();
		if( $posts ) {
			foreach( $posts as $p ){
				$opts = unserialize( $p->geot_options );
				if( !empty( $opts['country_code'] ) || !empty( $opts['region'] ) || !empty( $opts['cities'] ) || !empty( $opts['state'] ) )
					$to_migrate[] = $p->ID;
			}
		}
		// Save post meta to those posts
		if( !empty( $to_migrate ) ) {
			$sql_string = array();
			foreach ($to_migrate as $id) {
				$sql_string[] = "('$id', '_geot_post', '1' )";
			}
			$sql = "INSERT INTO $wpdb->postmeta (post_id,meta_key,meta_value) VALUES ".implode(',',$sql_string).";";

			$wpdb->query($sql);
		}
		update_option( 'geot_posts_upgrade', 1 );

	}
}
