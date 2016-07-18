<?php
/**
 * Adds GeoTarget to menus
 * @since  1.8
 */
class GeoTarget_Menus {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $GeoTarget The ID of this plugin.
	 */
	private $GeoTarget;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * @since   1.6
	 * @access  private
	 * @var     Array of plugin settings
	 */
	private $opts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string $GeoTarget The name of this plugin.
	 * @var      string $version The version of this plugin.
	 */
	public function __construct( $GeoTarget, $version ) {

		$this->GeoTarget = $GeoTarget;
		$this->version   = $version;
		$this->opts      = apply_filters( 'geot/settings_page/opts', get_option( 'geot_settings' ) );
	}

	/**
	 * Add custom fields
	 * @param $menu_item
	 *
	 * @return mixed
	 */
	public function add_custom_fields( $menu_item ) {

        $menu_item->geot = get_post_meta( $menu_item->ID, '_menu_item_geot', true );
		return $menu_item;
		
	}

	public function save_custom_fields( $menu_id, $menu_item_db_id, $args ) {

		// Check if element is properly sent
		if ( isset( $_REQUEST['menu-item-geot'] ) && is_array( $_REQUEST['menu-item-geot'] ) ) {
			$geot_country_value = $_REQUEST['menu-item-geot'][$menu_item_db_id];
			update_post_meta( $menu_item_db_id, '_menu_item_geot', $geot_country_value );
		}

	}

	public function admin_menu_walker( $walker,$menu_id ) {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geot-admin-menu-walker.php';
		return 'Geot_Admin_Menu_Walker';

	}
}