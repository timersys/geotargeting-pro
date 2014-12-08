<?php
/**
 * Filters helpers functions
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Filters {
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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $GeoTarget       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $GeoTarget, $version ) {

		$this->GeoTarget = $GeoTarget;
		$this->version = $version;

	}

	/**
	 * Return available posts types. Used in filters
	 * @param  array $post_types custom post types
	 * @param  array  $exclude    cpt to explude
	 * @param  array  $include    cpts to include
	 * @return array  Resulting cpts
	 */
	function get_post_types( $post_types, $exclude = array(), $include = array() ) 	{
	
		// get all custom post types
		$post_types = array_merge($post_types, get_post_types());
		
		
		// core include / exclude
		$spu_includes = array_merge( array(), $include );
		$spu_excludes = array_merge( array( 'spucpt', 'acf', 'revision', 'nav_menu_item' ), $exclude );
	 
		
		// include
		foreach( $spu_includes as $p )
		{					
			if( post_type_exists($p) )
			{							
				$post_types[ $p ] = $p;
			}
		}
		
		
		// exclude
		foreach( $spu_excludes as $p )
		{
			unset( $post_types[ $p ] );
		}
		
		
		return $post_types;
		
	}

	/**
	 * Get countries in database
	 * @return object countries codes and names
	 */
	public function get_countries()	{
		
		$countries = wp_cache_get( 'geot_countries');

		if( false === $countries ) {
			global $wpdb;

			$countries = $wpdb->get_results( "SELECT DISTINCT maxmind_country_code, maxmind_country FROM {$wpdb->prefix}Maxmind_geoIP ORDER BY maxmind_country ");
			
			wp_cache_set( 'geot_countries', $countries); 
		}

		return $countries;

	}

	/**
	 * Return saved regions
	 * @return array of regions
	 */
	public function get_regions()
	{
		$settings 	= apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );
		$regions 	= $settings['region'];

		return $regions;
	}

	/**
	 * Get post meta option
	 * @param  int $post_id [description]
	 * @return array    
	 */
	public static function get_cpt_options( $post_id ) {
		
		$defaults = array(
			'country_code'	=> '',
		);
		
		$opts = get_post_meta( $post_id, 'geot_options', true );

		return wp_parse_args( $opts, apply_filters( 'geot/metaboxes/default_options', $defaults ) );
	
	}


}	
