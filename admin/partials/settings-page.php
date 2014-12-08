<?php 
/**
 * Settings page template
 * @since  1.0.0
 */
?>
<div class="wrap geot-settings">
	<h2>GeoTargeting <?php echo $this->version;?></h2>
	<form name="geot-settings" method="post">
		<table class="form-table">
			<?php do_action( 'geot/settings_page/before' ); ?>
			<tr valign="top" class="">
				<th><label for="license"><?php _e( 'Enter your license key', $this->GeoTarget ); ?></label></th>
				<td colspan="3">
					<label><input type="text" id="license" name="geot_settings[geot_license_key]" value="<?php  echo $opts['geot_license_key'];?>" class="regular-text <?php echo 'geot_license_' . get_option( 'geot_license_active' );?>" /> 
					<p class="help"><?php _e( 'Enter your license key to get automatic updates', $this->GeoTarget ); ?></p>
				</td>
				
			</tr>
			<tr valign="top" class="">
				<th><label for="region"><?php _e( 'Create new region', $this->GeoTarget ); ?></label></th>
				<td colspan="3">
				<?php 

				if( !empty( $opts['region'] ) ) {
					foreach ( $opts['region'] as $region ) { @$i++;?>
			
						<div class="region-group"  data-id="<?php echo $i;?>" >
							
							<input type="text" placeholder="Enter region name" name="geot_settings[region][<?php echo $i;?>][name]" value="<?php echo !empty( $region['name'] )? esc_attr($region['name']): '' ; ?>"/> 
							<a href="#" class="remove-region"title="<?php _e( 'Remove Region', $this->GeoTarget );?>">-</a>
							<select name="geot_settings[region][<?php echo $i;?>][countries][]" multiple class="geot-chosen-select" data-placeholder="Type country name..." >
								<?php
									foreach ($countries as $c) {
										?>
										<option value="<?php echo $c->maxmind_country_code?>" <?php selected(true, @in_array( $c->maxmind_country_code, @$region['countries']) ); ?>> <?php echo $c->maxmind_country; ?></option>
										<?php
									}
								?>
							</select>
						</div>	
					<?php } 
				}?>
					<a href="#" class="add-region button">Add Region</a>
					<p class="help"><?php _e( 'Add as many countries you need for each region', $this->GeoTarget ); ?></p>
				</td>
				
			</tr>
			
			<?php do_action( 'geot/settings_page/after' ); ?>
			<tr><td><input type="submit" class="button-primary" value="<?php _e( 'Save settings', $this->GeoTarget );?>"/></td>
			<?php wp_nonce_field('geot_save_settings','geot_nonce'); ?>
		</table>
	</form>
</div>