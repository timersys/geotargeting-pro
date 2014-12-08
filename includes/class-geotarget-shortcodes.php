<?php
/**
 * Shortcodes  functions
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Shortcodes {
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

	/**
	 * Plugin functions
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    Plugin functions
	 */
	private $functions;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $GeoTarget       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 * @var      class    instance of GeotFunctions
	 */
	public function __construct( $GeoTarget, $version, $functions ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;
		$this->functions = $functions;
	}



	/**
	  * Shows provided content only if the location
	  * criteria are met.
	  * [geot country="US,CA"]content[/geot]
	  * [geot region="my_region"]content[/geot]
	  */
	function geot_filter($atts, $content)
	{
		extract( shortcode_atts( array(
			'ip' 				=> $_SERVER['REMOTE_ADDR'],
			'country'			=>'',
			'region'			=>'',
			'exclude_country'	=>'',
			'exclude_region'	=>''
		), $atts ) );
		
				
		if ( $this->functions->target( $country, $region, $exclude_country, $exclude_region ) )
			return do_shortcode( $content );
			
		return '';
	}
	



	/** 
	 * Displays the 2 character country for the current user
	 * [geot_country_code]   [geot_country_code]
	 * @return  string country CODE
	 **/
	function geot_show_country_code() {

		$c = $this->functions->get_user_country();

		return $c['maxmind_country_code'];
	}


	/** 
	 * Displays the country name for the current user
	 * [geot_country_name]
	 * @return  string country name
	 **/
	function geot_show_country_name() {

		$c = $this->functions->get_user_country();

		return $c['maxmind_country'];
	}

}	
