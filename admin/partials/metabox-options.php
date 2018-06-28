<?php

/**
 * Metabox settings
 *
 *
 * @link       https://geotargetingwp.com/geotargeting-pro
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/admin/partials
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;?>
<table class="form-table geot_table">

	<?php do_action( 'geot/metaboxes/before_display_options', $opts );?>
	<tr valign="top">
		<th><label for="geot_position"><?php _e( 'Choose:', 'geot' ); ?></label></th>
		<td>

			<input type="radio" class="geot_include_mode" name="geot[geot_include_mode]" value="include" <?php checked( $opts['geot_include_mode'], 'include', true ); ?>> <strong>Show in:</strong><br />
			<input type="radio" class="geot_include_mode" name="geot[geot_include_mode]" value="exclude" <?php checked( $opts['geot_include_mode'], 'exclude', true ); ?>> <strong>Never show in</strong><br />

		</td>
		<td colspan="2"></td>
	</tr>

	<tr valign="top">
		<th><label for="geot_position"><?php _e( 'Regions:', 'geot' ); ?></label></th>
		<td>
			<?php
			if( is_array( $regions ) ) { ?>
				<select name="geot[region][]" multiple class="geot-chosen-select-multiple" data-placeholder="<?php _e( 'Type or choose region name...', 'geot' );?>" >
					<?php
						if( is_array( $regions ) ) {
							foreach ($regions as $r) {
								if( ! is_array( $opts ) || ! isset( $r['name'] ) )
									continue;
								?>
								<option value="<?php echo $r['name'];?>" <?php
								if( isset( $opts['region'] ) )
									selected(true, @in_array($r['name'], $opts['region']) );
								?>> <?php echo $r['name']; ?></option>
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
		<th><label for="geot_position"><?php _e( 'Countries:', 'geot' ); ?></label></th>
		<td>
			<select name="geot[country_code][]" multiple class="geot-chosen-select-multiple" data-placeholder="<?php _e( 'Type or choose country name...', 'geot' );?>" >
				<?php
				if( is_array( $countries ) ) {
					foreach ($countries as $c) {
						if( ! is_array( $opts ) || ! isset( $c->iso_code ) )
							continue;
						?>
						<option value="<?php echo $c->iso_code;?>" <?php
						if( isset( $opts['country_code'] ) )
							selected(true, @in_array($c->iso_code, @(array)$opts['country_code']) );
						?>> <?php echo $c->country; ?></option>
						<?php
					}
				}
				?>
			</select>
		</td>
		<td colspan="2"></td>
	</tr>
	<tr valign="top">
		<th><label for="gcities"><?php _e( 'Cities:', 'geot' ); ?></label></th>
		<td>

			<input id="gcities" type="text" class="widefat" name="geot[cities]" value="<?php echo ! empty( $opts['cities'] ) ? $opts['cities'] :'';?>" placeholder="<?php _e( 'Or type cities or city regions (comma separated):', 'geot' );?>" />

		</td>
		<td colspan="2"></td>
	</tr>
	<tr valign="top">
		<th><label for="gstates"><?php _e( 'States:', 'geot' ); ?></label></th>
		<td>

			<input type="text" id="gstates" class="widefat" name="geot[states]" value="<?php echo ! empty( $opts['states'] ) ? $opts['states'] :'';?>" placeholder="<?php _e( 'Or type states (comma separated):', 'geot' );?>" />

		</td>
		<td colspan="2"></td>
	</tr>
	<tr valign="top">
		<th><label for="geot_position"><?php _e( 'Remove post from loop:', 'geot' ); ?></label></th>
		<td>

			<input type="checkbox" class="geot_remove_post" name="geot[geot_remove_post]" value="1" <?php checked( $opts['geot_remove_post'], '1', true ); ?>> <?php _e( 'If checked post will be removed from loop otherwise show message below', 'geot');?><br />

		</td>
		<td colspan="2"></td>
	</tr>
	<tr valign="top">
		<th><label for="geot_position"><?php _e( 'Show if user is not allowed to see content:', 'geot' ); ?></label></th>
		<td>
			<textarea style="width:90%;height: 50px;" name="geot[forbidden_text]" data-placeholder="<?php _e( 'Type the text that user will see if not allowed to view content', 'geot' );?>"><?php echo $opts['forbidden_text'];?></textarea>

		</td>
		<td colspan="2"></td>
	</tr>
</table>
<?php wp_nonce_field( 'geot_options', 'geot_options_nonce' ); ?>
