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
	 * Add custom fields to the menu item
	 * @param $menu_item
	 *
	 * @return mixed
	 */
	public function add_custom_fields( $menu_item ) {

        $menu_item->geot = get_post_meta( $menu_item->ID, '_menu_item_geot', true );
		return $menu_item;
		
	}

	/**
	 * Save custom menu fields data into db
	 * @param $menu_id
	 * @param $menu_item_db_id
	 * @param $args
	 */
	public function save_custom_fields( $menu_id, $menu_item_db_id, $args ) {

		// Check if element is properly sent
		if ( isset( $_REQUEST['menu-item-geot'] ) && is_array( $_REQUEST['menu-item-geot'] ) ) {
			$geot_country_value = $_REQUEST['menu-item-geot'][$menu_item_db_id];
			update_post_meta( $menu_item_db_id, '_menu_item_geot', $geot_country_value );
		}

	}

	/**
	 * Change admin menu walker for custom one
	 * @param $walker
	 * @param $menu_id
	 *
	 * @return string
	 */
	public function admin_menu_walker( $walker,$menu_id ) {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geot-admin-menu-walker.php';
		return 'Geot_Admin_Menu_Walker';

	}

	/**
	 * Main function that filters wp_nav_menu_objects in frontend and remove menu items accordingly
	 * @param $sorted_menu_items
	 * @param $args
	 *
	 * @return mixed
	 */
	public function geotarget_menus( $sorted_menu_items, $args ){

		if( empty( $sorted_menu_items ) || ! is_array( $sorted_menu_items ) )
			return $sorted_menu_items;

		foreach ( $sorted_menu_items as $k => $menu_item ) {
			$g = $menu_item->geot;
			// check at least one condition is filled
			if( !empty( $g['regions'] ) || !empty( $g['countries'] ) ) {

				$countries  = !empty( $g['countries'] ) ?  $g['countries'] : '';
				$regions    = !empty( $g['regions'] ) ?  $g['regions'] : '';
				$target = geot_target( $countries, $regions );
				if( $g['include_mode'] == 'include' && ! $target )
					unset( $sorted_menu_items[$k]);

				if( $g['include_mode'] != 'include' && $target )
					unset( $sorted_menu_items[$k]);

			}
			if( !empty( $g['cities'] ) ) {

				$cities     = !empty( $g['cities'] ) ?  $g['cities'] : '';
				$target     = geot_target_city( $cities );
				if( $g['include_mode'] == 'include' && ! $target )
					unset( $sorted_menu_items[$k]);

				if( $g['include_mode'] != 'include' && $target )
					unset( $sorted_menu_items[$k]);
			}

			if ( !empty( $g['states'] ) ) {
				$states     = !empty( $g['states'] ) ?  $g['states'] : '';
				$target     = geot_target_state( $states );

				if( $g['include_mode'] == 'include' && ! $target )
					unset( $sorted_menu_items[$k]);

				if( $g['include_mode'] != 'include' && $target )
					unset( $sorted_menu_items[$k]);
			}
		}
		return $sorted_menu_items;
	}
}