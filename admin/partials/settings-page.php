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
				<th><h3><?php _e( 'Cloudflare:', $this->GeoTarget ); ?></h3></th>
				<td colspan="3">
					<p><?php _e( 'If you want to rely country detection to your cloudflare account check below', $this->GeoTarget ); ?></p>
				</td>
			</tr>
			<tr valign="top" class="">
				<th><label for="maxm_id"><?php _e( 'Use Cloudflare', $this->GeoTarget ); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="maxm_id" name="geot_settings[cloudflare]" value="1" <?php checked($opts['cloudflare'],'1');?>/>
					<p class="help"><?php _e( 'Use Cloudflare country detection', $this->GeoTarget ); ?></p>
				</td>
			</tr>
			<tr valign="top" class="">
				<th><h3><?php _e( 'Maxmind:', $this->GeoTarget ); ?></h3></th>
				<td colspan="3">
					<p><?php echo sprintf(__( 'If you have <a href="%s">Maxmind API credentials</a>, enter them below', $this->GeoTarget ), 'https://www.maxmind.com/en/geoip2-precision-city-service?rId=timersys'); ?></p>
				</td>
			</tr>
			<tr valign="top" class="">
				<th><label for="maxm_id"><?php _e( 'Maxmind User ID', $this->GeoTarget ); ?></label></th>
				<td colspan="3">
					<label><input type="text" id="maxm_id" name="geot_settings[maxm_id]" value="<?php  echo $opts['maxm_id'];?>" class="regular-text" />
					<p class="help"><?php _e( 'Enter your Maxmind user id', $this->GeoTarget ); ?></p>
				</td>
			</tr>
			<tr valign="top" class="">
				<th><label for="maxm_license"><?php _e( 'Maxmind license key', $this->GeoTarget ); ?></label></th>
				<td colspan="3">
					<label><input type="text" id="maxm_license" name="geot_settings[maxm_license]" value="<?php  echo $opts['maxm_license'];?>" class="regular-text" />
					<p class="help"><?php _e( 'Enter your Maxmind license key', $this->GeoTarget ); ?></p>
				</td>
			</tr>
			<tr valign="top" class="">
				<th><h3><?php _e( 'Countries:', $this->GeoTarget ); ?></h3></th>
				<td colspan="3">
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
							<select name="geot_settings[region][<?php echo $i;?>][countries][]" multiple class="geot-chosen-select" data-placeholder="<?php _e('Type country name...', $this->GeoTarget );?>" >
								<?php
									foreach ($countries as $c) {
										?>
										<option value="<?php echo $c->iso_code?>" <?php selected(true, @in_array( $c->iso_code, @$region['countries']) ); ?>> <?php echo $c->country; ?></option>
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
			<tr valign="top" class="">
				<th><h3><?php _e( 'Cities:', $this->GeoTarget ); ?></h3></th>
				<td colspan="3">
				</td>
			</tr>
			<tr valign="top" class="">
				<th><label for="region"><?php _e( 'Create new region', $this->GeoTarget ); ?></label></th>
				<td colspan="3">
				<?php

				if( !empty( $opts['city_region'] ) ) {
					foreach ( $opts['city_region'] as $city_region ) { @$j++;?>

						<div class="city-region-group"  data-id="<?php echo $j;?>" >
							<input type="text" placeholder="Enter region name" name="geot_settings[city_region][<?php echo $j;?>][name]" value="<?php echo !empty( $city_region['name'] )? esc_attr($city_region['name']): '' ; ?>"/>
							<select name="geot_settings[city_region][<?php echo $j;?>][countries][]"  class="geot-chosen-select country_ajax" data-counter="<?php echo $j;?>" data-placeholder="<?php _e('Type country name...', $this->GeoTarget );?>" >
								<option value=""><?php _e('Choose a Country', $this->GeoTarget );?></option>
								<?php
								foreach ($countries as $c) {
									?>
									<option value="<?php echo $c->iso_code?>" <?php selected(true, @in_array( $c->iso_code, @$city_region['countries']) ); ?>> <?php echo $c->country; ?></option>
								<?php
								}
								?>
							</select>
							<a href="#" class="remove-city-region"title="<?php _e( 'Remove Region', $this->GeoTarget );?>">-</a>
							<select name="geot_settings[city_region][<?php echo $j;?>][cities][]" multiple class="geot-chosen-select cities_container" id="<?php echo 'cities'.$j;?>" data-placeholder="<?php _e('First choose a country', $this->GeoTarget );?>" >
								<?php
								if( !empty($city_region['countries'])) {
									$cities = geot_get_cities( $city_region['countries'][0] );
									foreach ( $cities as $c ) {
										?>
										<option
											value="<?php echo strtolower( $c->city ) ?>" <?php selected( true, @in_array( strtolower( $c->city), @$city_region['cities'] ) ); ?>> <?php echo $c->city; ?></option>
									<?php
									}
								}
								?>
							</select>
						</div>
					<?php }
				}?>
					<a href="#" class="add-city-region button">Add City Region</a>
					<p class="help"><?php _e( 'Add as many cities you need for each region', $this->GeoTarget ); ?></p>
				</td>

			</tr>
			
			<?php do_action( 'geot/settings_page/after' ); ?>
			<tr><td><input type="submit" class="button-primary" value="<?php _e( 'Save settings', $this->GeoTarget );?>"/></td>
			<?php wp_nonce_field('geot_save_settings','geot_nonce'); ?>
		</table>
	</form>
</div>