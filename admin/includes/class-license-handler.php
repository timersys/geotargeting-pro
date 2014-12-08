<?php
/**
 * License handler for EDD
 *
 * This class should simplify the process of adding license information
 * to new EDD extensions.
 *
 * @author  Daniel J Griffiths
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'Geot_License' ) ) {

	class Geot_License {
		private $file;
		private $license;
		private $item_name;
		private $item_shortname;
		private $version;
		private $author;

		/**
		 * Class constructor
		 *
		 * @global  array $edd_options
		 * @param string  $_file
		 * @param string  $_item_name
		 * @param string  $_version
		 * @param string  $_author
		 * @return  void
		 */
		function __construct( $_file, $_item_name, $_version, $_author, $key ) {
			global $edd_options;

			$this->file           = $_file;
			$this->item_name      = $_item_name;
			$this->item_shortname = 'geot';
			$this->version        = $_version;
			$this->license        = isset( $key ) ? trim( $key ) : '';
			$this->author         = $_author;

			// Setup hooks
			$this->includes();
			$this->hooks();
		}


		/**
		 * Include the updater class
		 *
		 * @access  private
		 * @return  void
		 */
		private function includes() {
			if ( !class_exists( 'EDD_SL_Plugin_Updater' ) )
				require_once 'EDD_SL_Plugin_Updater.php';
		}


		/**
		 * Setup hooks
		 *
		 * @access  private
		 * @return  void
		 */
		private function hooks() {

			// Activate license key on settings save
			add_action( 'admin_init', array( $this, 'activate_license' ) );

			// Deactivate license key
			add_action( 'admin_init', array( $this, 'deactivate_license' ) );
			
			// Updater
			add_action( 'admin_init', array( $this, 'auto_updater' ), 2 );
		}



		/**
		 * Auto updater
		 *
		 * @access  private
		 * @global  array $edd_options
		 * @return  void
		 */
		public function auto_updater() {

			if ( 'valid' !== get_option( $this->item_shortname . '_license_active' ) )
				return;
			
			// Setup the updater
			$edd_updater = new EDD_SL_Plugin_Updater( 'http://wp.timersys.com', $this->file, array(
					'version'   => $this->version,
					'license'   => $this->license,
					'item_name' => $this->item_name,
					'author'    => $this->author
				)
			);
		}



		/**
		 * Activate the license key
		 *
		 * @access  public
		 * @return  void
		 */
		public function activate_license() {
			global $spu;

			if ( !isset( $_POST['spu_settings'] ) ) return;
			if ( !isset( $_POST['spu_settings'][$this->item_shortname . '_license_key'] ) ) return;

			if ( get_option( $this->item_shortname . '_license_active' ) == 'valid' && $_POST['spu_settings'][$this->item_shortname . '_license_key'] == $this->license ) return;

			$license = sanitize_text_field( $_POST['spu_settings'][$this->item_shortname . '_license_key'] ) ;

			// Data to send to the API
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'  => $license,
				'item_name'  => urlencode( $this->item_name )
			);

			// Call the API
			$response = wp_remote_get( add_query_arg( $api_params, 'http://wp.timersys.com' ), array( 'timeout' => 15, 'sslverify' => false ) );


			// Make sure there are no errors
			if ( is_wp_error( $response ) ) return false;

			// Decode license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			update_option( $this->item_shortname . '_license_active', $license_data->license );
		}


		/**
		 * Deactivate the license key
		 *
		 * @access  public
		 * @return  void
		 */
		public function deactivate_license() {
			global $edd_options;

			if ( !isset( $_POST['spu_settings'] ) ) return;
			if ( !isset( $_POST['spu_settings'][$this->item_shortname . '_license_key'] ) ) return;

			// Run on deactivate button press
			if ( isset( $_POST[$this->item_shortname . '_license_key_deactivate'] ) ) {
				// Run a quick security check
				if ( !check_admin_referer( $this->item_shortname . '_license_key_nonce', $this->item_shortname . '_license_key_nonce' ) ) return;

				// Data to send to the API
				$api_params = array(
					'edd_action' => 'deactivate_license',
					'license'  => $this->license,
					'item_name'  => urlencode( $this->item_name )
				);

				// Call the API
				$response = wp_remote_get( add_query_arg( $api_params, 'http://wp.timersys.com' ), array( 'timeout' => 15, 'sslverify' => false ) );

				// Make sure there are no errors
				if ( is_wp_error( $response ) ) return false;

				// Decode the license data
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				if ( $license_data->license == 'deactivated' )
					delete_option( $this->item_shortname . '_license_active' );
			}
		}
	}
}
