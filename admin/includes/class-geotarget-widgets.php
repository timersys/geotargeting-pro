<?php
/**
 * Adds GeoTarget to all Widgets
 * @since  1.0.0
 */
class Geot_Widgets  {

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

	public function add_geot_to_widgets( $t, $return, $instance ) {
		
		$countries 	= apply_filters('geot/get_countries', array());
		$regions 	= apply_filters('geot/get_regions', array());

		if ( empty( $instance['geot_include_mode'] ) )  {
			$instance['geot_include_mode'] = '';
		}
		if ( empty( $instance['geot']['region'] ) )  {
			$instance['geot']['region'] = array();
		}
		if ( empty( $instance['geot']['country_code'] ) )  {
			$instance['geot']['country_code'] = array();
		}

		?>

		<div id="geot_widget" class="widget-content">
			<strong>Geotargeting</strong>
		    <p>
		    	<label for="geot_what"><?php _e( 'Choose:', 'geot' ); ?></label><br/>
		   		<input type="radio" class="geot_include_mode" name="<?php echo $t->get_field_name('geot_include_mode');?>" value="include" <?php checked( $instance['geot_include_mode'], 'include', true ); ?>> <strong>Only show widget in</strong><br />
		      	<input type="radio" class="geot_include_mode" name="<?php echo $t->get_field_name('geot_include_mode');?>" value="exclude" <?php checked( $instance['geot_include_mode'], 'exclude', true ); ?>> <strong>Never show widget in</strong><br />
		    </p>
		    <p>
		    	<label><?php _e( 'Choose regions:', 'geot' ); ?></label>
				<?php
				if( is_array( $regions ) ) { ?>
					<select name="<?php echo $t->get_field_name('geot');?>[region][]" multiple class="geot-chosen-select" data-placeholder="<?php _e( 'Type or choose region name...', 'geot' );?>" >
						<?php
							if( is_array( $regions ) ) {
								foreach ($regions as $r) {
									?>
									<option value="<?php echo $r['name'];?>" <?php selected( in_array($r['name'], $instance['geot']['region']), true , true ); ?>> <?php echo $r['name']; ?></option>
									<?php
								}
							}
						?>
					</select>
				<?php 
				} else { ?>
				
					<p> Add some regions first.</p>
				
				<?php	
				}	?>
			</p>
				
			<p>
				<label for="geot_position"><?php _e( 'Or choose countries:', 'geot' ); ?></label>
				
				<select name="<?php echo $t->get_field_name('geot');?>[country_code][]" multiple class="geot-chosen-select" data-placeholder="<?php _e( 'Type or choose country name...', 'geot' );?>" >
					<?php
					if( is_array( $countries ) ) {
						foreach ($countries as $c) {
							?>
							<option value="<?php echo $c->iso_code;?>" <?php selected( in_array($c->iso_code, $instance['geot']['country_code']), true , true ); ?>> <?php echo $c->country; ?></option>
							<?php
						}
					}	
					?>
				</select>
			</p>
			</table>
		</div>	

		<?php	
		return array($t, $return, $instance);
	}

	/**
	 * Saves widget data
	 * @param  array $instance     Current widget instance
	 * @param  array $new_instance Saved instance
	 * @param  array $old_instance 
	 * @return array          
	 */
	public function save_widgets_data( $instance, $new_instance, $old_instance ) {
		
		$instance['geot']  				= isset( $new_instance['geot'] ) ? (array) $new_instance['geot'] : '';
		$instance['geot_include_mode'] 	= isset( $new_instance['geot_include_mode'] ) ? $new_instance['geot_include_mode'] : '';
		return $instance;
	}

	/**
	 * Check if widgets is being targeted and show it if needed
	 *
	 * @param $widget_data
	 *
	 * @return bool [type] [description]
	 */
	public function target_widgets( $widget_data ) {


		if ( !empty( $widget_data['geot']['region'] ) || !empty( $widget_data['geot']['country_code'] ) ) {
			
			if ( 'include' == @$widget_data['geot_include_mode'] ) {
				if ( ! geot_target( @$widget_data['geot']['country_code'], @$widget_data['geot']['region'] ) ) {
					return false;
				}	
			} else {
				if (! geot_target( array(), array(), @$widget_data['geot']['country_code'], @$widget_data['geot']['region'] ) ) {
					return false;
				}
				
			}
		}

		return $widget_data;
	}

} // class Geot_Widgets