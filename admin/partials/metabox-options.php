<?php

/**
 * Metabox settings
 *
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/admin/partials
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;?>
<table class="form-table">
	
	<?php do_action( 'geot/metaboxes/before_display_options', $opts );?>
	<tr valign="top">
		<th><label for="geot_position"><?php _e( 'Show to the following regions:', 'geot' ); ?></label></th>
		<td>
			<?php
			if( is_array( $regions ) ) { ?>
				<select name="geot[region][]" multiple class="geot-chosen-select" data-placeholder="<?php _e( 'Type or choose region name...', 'geot' );?>" >
					<?php
						if( is_array( $regions ) ) {
							foreach ($regions as $r) {
								?>
								<option value="<?php echo $r['name'];?>" <?php selected(true, @in_array($r['name'], @$opts['region']) ); ?>> <?php echo $r['name']; ?></option>
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
		</td>
		<td colspan="2"></td>
	</tr>
	<tr valign="top">
		<th><label for="geot_position"><?php _e( 'Show to the following countries:', 'geot' ); ?></label></th>
		<td>
			<select name="geot[country_code][]" multiple class="geot-chosen-select" data-placeholder="<?php _e( 'Type or choose country name...', 'geot' );?>" >
				<?php
				if( is_array( $countries ) ) {
					foreach ($countries as $c) {
						?>
						<option value="<?php echo $c->maxmind_country_code;?>" <?php selected(true, @in_array($c->maxmind_country_code, @(array)$opts['country_code']) ); ?>> <?php echo $c->maxmind_country; ?></option>
						<?php
					}
				}	
				?>
			</select>
		</td>
		<td colspan="2"></td>
	</tr>
</table>
<?php wp_nonce_field( 'geot_options', 'geot_options_nonce' ); ?>