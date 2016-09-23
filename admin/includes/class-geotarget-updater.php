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
        if( $update )
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

}
