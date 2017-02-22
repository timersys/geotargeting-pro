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
		private $license_page;

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
			$this->license_page   = 'geot-settings';

			// Setup hooks
			$this->auto_updater();
			$this->hooks();
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
		}



		/**
		 * Auto updater
		 *
		 * @access  private
		 * @global  array $edd_options
		 * @return  void
		 */
		public function auto_updater() {

			// Setup the updater
			$edd_updater = new EDD_SL_Plugin_Updater( 'https://timersys.com', $this->file, array(
					'version'   => $this->version,
					'license'   => $this->license,
					'item_name' => $this->item_name,
					'author'    => $this->author,
					'beta'		=> false
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
			if ( !isset( $_POST['geot_settings'] ) ) return;
			if ( !isset( $_POST['geot_settings'][$this->item_shortname . '_license_key'] ) ) return;

			$license = trim( sanitize_text_field( $_POST['geot_settings'][$this->item_shortname . '_license_key'] ) );
			
			if ( get_option( $this->item_shortname . '_license_active' ) == 'valid' && $license == $this->license ) return;


			// Data to send to the API
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'  => trim($license),
				'item_name'  => urlencode( $this->item_name ),
				'url'        => home_url()
			);

			// Call the API
			$response = wp_remote_post( add_query_arg( $api_params, 'https://timersys.com' ), array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

			} else {

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				if ( false === $license_data->success ) {

					switch( $license_data->error ) {

						case 'expired' :

							$message = sprintf(
								__( 'Your license key expired on %s.' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;

						case 'revoked' :

							$message = __( 'Your license key has been disabled.' );
							break;

						case 'missing' :

							$message = __( 'Invalid license.' );
							break;

						case 'invalid' :
						case 'site_inactive' :

							$message = __( 'Your license is not active for this URL.' );
							break;

						case 'item_name_mismatch' :

							$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), $this->item_name );
							break;

						case 'no_activations_left':

							$message = __( 'Your license key has reached its activation limit.' );
							break;

						default :

							$message = __( 'An error occurred, please try again.' );
							break;
					}

				}
			}

			update_option( $this->item_shortname . '_license_active', $license_data->license );
			$base_url = admin_url( 'admin.php?page=' . $this->license_page );

			// Check if anything passed on a message constituting a failure
			if ( ! empty( $message ) ) {
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'geot_message' => urlencode( $message ) ), $base_url );
				wp_redirect( $redirect );
				exit();
			}

			// $license_data->license will be either "valid" or "invalid"

			wp_redirect( $base_url );


		}


		/**
		 * Deactivate the license key
		 *
		 * @access  public
		 * @return  void
		 */
		public function deactivate_license() {
			global $edd_options;

			if ( !isset( $_POST['geot_settings'] ) ) return;
			if ( !isset( $_POST['geot_settings'][$this->item_shortname . '_license_key'] ) ) return;

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
				$response = wp_remote_get( add_query_arg( $api_params, 'https://timersys.com' ), array( 'timeout' => 15, 'sslverify' => false ) );

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
