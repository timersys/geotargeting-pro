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
	 * @since   1.6
	 * @access  private
	 * @var     Array of plugin settings
	 */
	private $opts;
	private $geot_opts;

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
		$this->opts = geot_settings();
		$this->geot_opts = geot_pro_settings();
	}

	public function add_geot_to_widgets( $t, $return, $instance ) {

		$countries 	= geot_countries();
		$regions 	= geot_country_regions();

		if ( empty( $instance['geot_include_mode'] ) )  {
			$instance['geot_include_mode'] = '';
		}
		if ( empty( $instance['geot_states'] ) )  {
			$instance['geot_states'] = '';
		}
		if ( empty( $instance['geot_cities'] ) )  {
			$instance['geot_cities'] = '';
		}
		if ( empty( $instance['geot'] ) )  {
			$instance['geot'] = array();
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
		    	<label><?php _e( 'Choose regions( country regions ):', 'geot' ); ?></label>
				<?php
				if( is_array( $regions ) ) { ?>
					<select name="<?php echo $t->get_field_name('geot');?>[region][]" multiple class="geot-chosen-select-multiple" data-placeholder="<?php _e( 'Type or choose region name...', 'geot' );?>" >
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

				<select name="<?php echo $t->get_field_name('geot');?>[country_code][]" multiple class="geot-chosen-select-multiple" data-placeholder="<?php _e( 'Type or choose country name...', 'geot' );?>" >
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
			<p>
				<label for="geot_position"><?php _e( 'Or type cities or city regions (comma separated):', 'geot' ); ?></label><br />
				<input type="text" class="geot_text" name="<?php echo $t->get_field_name('geot_cities');?>" value="<?php echo esc_attr($instance['geot_cities']);?>" />
			</p>
			<p>
				<label for="geot_position"><?php _e( 'Or type states (comma separated):', 'geot' ); ?></label><br />
				<input type="text" class="geot_text" name="<?php echo $t->get_field_name('geot_states');?>" value="<?php echo esc_attr($instance['geot_states']);?>" />
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
		$instance['geot_cities'] 	    = isset( $new_instance['geot_cities'] ) ? $new_instance['geot_cities'] : '';
		$instance['geot_states'] 	    = isset( $new_instance['geot_states'] ) ? $new_instance['geot_states'] : '';
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

		if( ! empty( $this->geot_opts['ajax_mode'] ) )
			return $widget_data;
		if ( !empty( $widget_data['geot']['region'] ) || !empty( $widget_data['geot']['country_code'] ) || !empty( $widget_data['geot_cities'] ) || !empty( $widget_data['geot_states'] ) ) {

			if ( 'include' == @$widget_data['geot_include_mode'] ) {
				if( !empty( $widget_data['geot_cities'] ) ) {
					if ( ! geot_target_city( @$widget_data['geot_cities'], @$widget_data['geot_cities'] ) ) {
						return false;
					}
				} elseif( !empty( $widget_data['geot_states'] ) ) {
					if ( ! geot_target_state( @$widget_data['geot_states'] ) ) {
						return false;
					}
				} else {
					if ( ! geot_target( @$widget_data['geot']['country_code'], @$widget_data['geot']['region'] ) ) {
						return false;
					}
				}
			} else {
				if( !empty( $widget_data['geot_cities'] ) ) {
					if ( ! geot_target_city( array(), array(), @$widget_data['geot_cities'], @$widget_data['geot_cities'] ) ) {
						return false;
					}
				} elseif( !empty( $widget_data['geot_states'] ) ) {
					if ( ! geot_target_state( array(), @$widget_data['geot_states'] ) ) {
						return false;
					}
				} else {
					if ( ! geot_target( array(), array(), @$widget_data['geot']['country_code'], @$widget_data['geot']['region'] ) ) {
						return false;
					}
				}
			}
		}

		return $widget_data;
	}

} // class Geot_Widgets
