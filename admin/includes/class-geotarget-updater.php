<?php
/**
 * Plugin db Updater Class
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.9
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/admin/includes
 * @author     Damian Logghe <damian@timersys.com>
 */
class GeoTarget_Updater {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.6
	 * @access   private
	 * @var      string    $GeoTarget    The ID of this plugin.
	 */
	private $GeoTarget;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.6
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.6
	 * @var      string    $GeoTarget       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 * @var      class    instance of GeotFunctions
	 */
	public function __construct( $GeoTarget, $version ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;
	}

    /**
     * Check out if plugin needs update. Triggered on activation routine
     * @return [type] [description]
     */
	public function update_notice() {
        $update = get_option( 'geot_db_update' );
        if( $update || ! file_exists( WP_CONTENT_DIR . '/uploads/geot_plugin/mmdb/GeoLite2-City.mmdb') )
            add_action( 'admin_notices', array( $this, 'show_update_notice') );
    }

    /**
     * Dislay notice asking users to update plugin
     * @return [type] [description]
     */
    public function show_update_notice() {
        $class = 'notice notice-error';
        $message = '<h4>'.__( 'Geotargeting plugin needs to update the database').'</h4>';
        $message .= '<p>'.__( 'In order to continue using Geotargeting plugin you will need to update the database first. This could take a few minutes, click the button below when you are ready.', 'geot' ).'</p>';
        $message .= '<p><a class="button button-primary" href="'.admin_url('admin.php?page=geot-settings&geot_db_update=true').'">I am ready to update</a></p>';

        printf( '<div class="%1$s">%2$s</div>', $class, $message );
    }


	/**
	 * Download DB Using CURL
	 * @return [type] [description]
	 */
	function ajax_geot_updater(){
		global $wp_filesystem,$wpdb;

		if( ! WP_Filesystem() ) {
			echo json_encode( array( 'error' => __('Could not access filesystem.')));
			wp_die();
		}

		@set_time_limit( 300 );
		$object = isset($_POST['object'] ) && 'mmdb' == $_POST['object'] ? 'mmdb' : 'csv';

		$url = 'https://s3.amazonaws.com/timersys/GeoLite2-City.mmdb.zip';
		$destination = WP_CONTENT_DIR . '/uploads/geot_plugin/mmdb/';
		$file = $destination . 'localfile.tmp';

		if( $object == 'csv' ) {
			// if we already have city rows, not need to run it again
			if( $wpdb->get_var("SELECT count(id) FROM {$wpdb->base_prefix}geot_cities") ) {
				delete_option( 'geot_db_update', true);
				echo json_encode( array( 'success' => 1, 'refresh' => 1));
				wp_die();
			}

			$url = 'https://s3.amazonaws.com/timersys/geot_cities.zip';
			$destination = WP_CONTENT_DIR . '/uploads/geot_plugin/csv/';
			$file = $destination . 'localfile.tmp';
		}

		$dir = WP_CONTENT_DIR . '/uploads/';

		$dirs = array('geot_plugin', 'geot_plugin/mmdb/', 'geot_plugin/csv/' );
		foreach( $dirs as $mkdir ) {
			if ( ! $wp_filesystem->mkdir( $dir.$mkdir, FS_CHMOD_DIR ) && ! $wp_filesystem->is_dir( $dir.$mkdir ) ) {
				echo json_encode( array( 'error' => __( 'Could not create directory.' )));
				wp_die();
			}
		}
		$ch = curl_init();
		$download = fopen ( $file, 'w+');
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, array($this,'progress'));
	    curl_setopt($ch, CURLOPT_NOPROGRESS, false);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FILE, $download);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_exec($ch);
	    curl_close($ch);
		fclose($download);

		$working_dir = $this->unpack_package( $file );

		if ( is_wp_error( $working_dir ) ) {
			echo json_encode( array( 'error' => 'Failed to download package. '.$working_dir->get_error_message()));
			wp_die();
		}
		$result = $this->install_package( array(
			'source' => $working_dir,
			'destination' => $destination,
		) );

		if ( is_wp_error( $result ) ) {
			echo json_encode( array( 'error' => 'Failed to unpack package. '.$result->get_error_message()));
			wp_die();
		}

		if( $object == 'csv' ) {
			if( function_exists('is_wpe') ) {
				echo json_encode( array( 'error' => 'Failed to install database. Are you running on WpEngine ? You will need to populate database manually, check https://timersys.com/geotargeting/docs/populating-database/ '));
				wp_die();
			}
			for ( $i = 1; $i <= 6; $i ++ ) {
				$csv_file  = $destination . 'geot_cities' . $i . '.csv';
				$load_data = "LOAD DATA LOCAL INFILE '{$csv_file}' INTO TABLE `{$wpdb->base_prefix}geot_cities` CHARACTER SET UTF8 FIELDS TERMINATED BY ',' ENCLOSED BY '\"' ESCAPED BY '\\\' LINES TERMINATED BY '\\n' ( `country_code` , `city`);";
				$wpdb->query( $load_data );
			}
			delete_option( 'geot_db_update', true);
			echo json_encode( array( 'success' => 1, 'refresh' => 1));
			wp_die();
		}
		echo json_encode( array( 'success' => 1));
		wp_die();
	}

	/**
	 * Update progress file that will be used to print the bar
	 * @param  [type] $resource      [description]
	 * @param  [type] $download_size [description]
	 * @param  [type] $downloaded    [description]
	 * @param  [type] $upload_size   [description]
	 * @param  [type] $uploaded      [description]
	 * @return [type]                [description]
	 */
	function progress($resource,$download_size, $downloaded, $upload_size, $uploaded) {
		$progress = 0;
		if ($download_size > 0)
		        $progress = round($downloaded / $download_size * 100);
		$progress = array('progress' => $progress);
		$destination  = WP_CONTENT_DIR . '/uploads/geot_plugin/progress.json';
		$file = fopen($destination, "w+");
		fwrite($file, json_encode($progress, JSON_UNESCAPED_UNICODE));
		fclose($file);
	}

    /**
     * Function to unzip file,
     * @param  [type]  $package        [description]
     * @param  boolean $delete_package [description]
     * @return [type]                  [description]
     */
    private function unpack_package( $package, $delete_package = true ) {
        global $wp_filesystem;
        if( ! WP_Filesystem() ) {
            echo json_encode( array( 'error' => __('Could not access filesystem.')));
            wp_die();
        }

        $upgrade_folder = $wp_filesystem->wp_content_dir() . 'upgrade/';

		//Clean up contents of upgrade directory beforehand.
		$upgrade_files = $wp_filesystem->dirlist($upgrade_folder);
		if ( !empty($upgrade_files) ) {
			foreach ( $upgrade_files as $file )
				$wp_filesystem->delete($upgrade_folder . $file['name'], true);
		}

        // We need a working directory - Strip off any .tmp or .zip suffixes
		$working_dir = $upgrade_folder . basename( basename( $package, '.tmp' ), '.zip' );

        // Clean up working directory
		if ( $wp_filesystem->is_dir($working_dir) )
			$wp_filesystem->delete($working_dir, true);

		// Unzip package to working directory
		$result = unzip_file( $package, $working_dir );

		// Once extracted, delete the package if required.
		if ( $delete_package )
			unlink($package);

        if ( is_wp_error($result) ) {
			$wp_filesystem->delete($working_dir, true);
			if ( 'incompatible_archive' == $result->get_error_code() ) {
				return new WP_Error( 'incompatible_archive', $this->strings['incompatible_archive'], $result->get_error_data() );
			}
			return $result;
		}

		return $working_dir;
    }

    /**
     * Move file to correct place
     * @param  array  $args [description]
     * @return [type]       [description]
     */
    public function install_package( $args = array() ) {
        global $wp_filesystem;

        $defaults = array(
            'source' => '', // Please always pass this
            'destination' => '', // and this
            'clear_destination' => true,
            'clear_working' => true,
            'abort_if_destination_exists' => false
        );

        $args = wp_parse_args($args, $defaults);

        // These were previously extract()'d.
        $source = $args['source'];
        $destination = $args['destination'];
        $clear_destination = $args['clear_destination'];

        if ( empty( $source ) || empty( $destination ) ) {
            return new WP_Error( 'bad_request', __('Bad Request') );
        }

        //Retain the Original source and destinations
        $remote_source = $args['source'];
        $local_destination = $destination;

        $source_files = array_keys( $wp_filesystem->dirlist( $remote_source ) );
        $remote_destination = $wp_filesystem->find_folder( $local_destination );

        //Locate which directory to copy to the new folder, This is based on the actual folder holding the files.
        if ( 1 == count( $source_files ) && $wp_filesystem->is_dir( trailingslashit( $args['source'] ) . $source_files[0] . '/' ) ) { //Only one folder? Then we want its contents.
            $source = trailingslashit( $args['source'] ) . trailingslashit( $source_files[0] );
        } elseif ( count( $source_files ) == 0 ) {
            return new WP_Error( 'incompatible_archive_empty', $this->strings['incompatible_archive'], $this->strings['no_files'] ); // There are no files?
        } else { // It's only a single file, the upgrader will use the folder name of this file as the destination folder. Folder name is based on zip filename.
            $source = trailingslashit( $args['source'] );
        }

        if ( is_wp_error( $source ) ) {
            return $source;
        }

        if ( $clear_destination ) {
            // We're going to clear the destination if there's something there.
            $removed = $this->clear_destination( $remote_destination );

            if ( is_wp_error( $removed ) ) {
                return $removed;
            }
        }

        //Create destination if needed
        if ( ! $wp_filesystem->exists( $remote_destination ) ) {
            if ( ! $wp_filesystem->mkdir( $remote_destination, FS_CHMOD_DIR ) ) {
                return new WP_Error( 'mkdir_failed_destination', __('Failed to create destination dir'), $remote_destination );
            }
        }
        // Copy new version of item into place.
        $result = copy_dir($source, $remote_destination);
        if ( is_wp_error($result) ) {
            if ( $args['clear_working'] ) {
                $wp_filesystem->delete( $remote_source, true );
            }
            return $result;
        }

        //Clear the Working folder?
        if ( $args['clear_working'] ) {
            $wp_filesystem->delete( $remote_source, true );
        }

        $destination_name = basename( str_replace($local_destination, '', $destination) );
        if ( '.' == $destination_name ) {
            $destination_name = '';
        }

        //Bombard the calling function will all the info which we've just used.
        $result = compact( 'source', 'source_files', 'destination', 'destination_name', 'local_destination', 'remote_destination', 'clear_destination' );

        return $result;
    }

    /**
     * Clear files on destination folder
     * @param  [type] $remote_destination [description]
     * @return [type]                     [description]
     */
    private function clear_destination( $remote_destination ) {
		global $wp_filesystem;

		if ( ! $wp_filesystem->exists( $remote_destination ) ) {
			return true;
		}

		// Check all files are writable before attempting to clear the destination.
		$unwritable_files = array();

		$_files = $wp_filesystem->dirlist( $remote_destination, true, true );

		// Flatten the resulting array, iterate using each as we append to the array during iteration.
		while ( $f = each( $_files ) ) {
			$file = $f['value'];
			$name = $f['key'];

			if ( ! isset( $file['files'] ) ) {
				continue;
			}

			foreach ( $file['files'] as $filename => $details ) {
				$_files[ $name . '/' . $filename ] = $details;
			}
		}

		// Check writability.
		foreach ( $_files as $filename => $file_details ) {
			if ( ! $wp_filesystem->is_writable( $remote_destination . $filename ) ) {

				// Attempt to alter permissions to allow writes and try again.
				$wp_filesystem->chmod( $remote_destination . $filename, ( 'd' == $file_details['type'] ? FS_CHMOD_DIR : FS_CHMOD_FILE ) );
				if ( ! $wp_filesystem->is_writable( $remote_destination . $filename ) ) {
					$unwritable_files[] = $filename;
				}
			}
		}

		if ( ! empty( $unwritable_files ) ) {
			return new WP_Error( 'files_not_writable', 'Files are not writable', implode( ', ', $unwritable_files ) );
		}

		if ( ! $wp_filesystem->delete( $remote_destination, true ) ) {
			return new WP_Error( 'remove_old_failed', 'Failed to remove old files' );
		}

		return true;
	}
}
