<?php
use GeotWP\GeotargetingWP;
use GeotFunctions\GeotUpdates;
/**
 * Fired during plugin updating
 *
 * @link       https://geotargetingwp.com/geotargeting-pro
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 */

/**
 * Fired during plugin updating.
 *
 * This class defines all code necessary to run during the plugin's updating.
 *
 * @since      1.0.0
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Updater {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $GeoTarget    The ID of this plugin.
	 */
	private $GeoTarget;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	public function __construct($GeoTarget, $version) {
		$this->GeoTarget = $GeoTarget;
		$this->version = $version;
		$this->hook = GEOT_PLUGIN_HOOK;
	}

	/**
	 * Handle Licences and updates
	 * @since 1.0.0
	 */
	public function handle_updates(){
		$opts 		= geot_settings();
		
		// Setup the updater
		$GeoUpdate = new GeotUpdates( GEOT_PLUGIN_FILE, [
				'version'   => $this->version,
				'license'   => isset($opts['license']) ? $opts['license'] : '',
			]
		);

		$this->apply_upgrade();

		return true;
	}


	protected function apply_upgrade() {

		$db_version = get_option( 'geot_version' );

		//Verify if plugin has be upgraded
		if($db_version != null && geot_version_compare( $this->version, $db_version, '!=' ) ) {

			if( geot_version_compare( $this->version, '1.8.0', '>=' ) && !get_option('geot_upgrade_1_8_0') )
				self::geot_upgrade_1_8_0();

			if( geot_version_compare( $this->version, '2.6.0', '>=' ) && !get_option('geot_upgrade_2_6_0') )
				self::geot_upgrade_2_6_0();

			do_action('geotWP/upgraded');

			update_option( 'geot_version', $this->version );
		}
	}


	/**
	 * Add mising _geot_post introduced in 1.8 to old posts
	 * @return [type] [description]
	 */
	protected static function geot_upgrade_1_8_0() {
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
		update_option( 'geot_upgrade_1_8_0', 1 );

	}


	protected function geot_upgrade_2_6_0() {
		global $wpdb;

		$array_insert = array();
		$city_regions = wp_list_pluck( geot_city_regions(), 'name' );

		$geot_posts = Geot_Helpers::get_geotarget_posts();

		if( $geot_posts ) {
			foreach( $geot_posts as $p ) {

				$to_city = $to_region_city = array();
				$opts = maybe_unserialize( $p->geot_options );

				if( empty($opts['cities']) || isset($opts['city_region']) ) continue;

				$list_cites = GeotFunctions\toArray($opts['cities']);

				foreach($list_cites as $city) {
					if( in_array( $city, $city_regions) )
						$to_region_city[] = $city;
					else
						$to_city[] = $city;
				}

				if( count($to_region_city) == 0 ) continue;

				$opts['cities'] = implode(',',$to_city);
				$opts['city_region'] = $to_region_city;

				$options = maybe_serialize($opts);

				$array_insert[] = '('.$p->geot_meta_id.', '.$p->ID.', \'geot_options\', \''.$options.'\')';
			}


			if( count($array_insert) > 0 ) {
				$sql = 'INSERT INTO '.$wpdb->postmeta.' (meta_id, post_id, meta_key, meta_value) VALUES '.implode(',',$array_insert).' ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)';
				$wpdb->query($sql);
			}

		}		

		update_option( 'geot_upgrade_2_6_0', 1 );
	}
}