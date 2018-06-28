<?php
$countries 	= geot_countries();
$regions 	= geot_country_regions();
$city_regions 	= geot_city_regions();
?>
<form name="form" autocomplete="off">
<div id="geot_editor" class="shortcode_editor" title="Country Geo Target Text"  style="">
	<div style="display: none;"><!--hack for chrome-->
		<input type="text" id="PreventChromeAutocomplete" name="PreventChromeAutocomplete" autocomplete="address-level4" />
	</div>
	<table class="form-table">
		<tr>
    		<th><label for="geot_what"><?php _e( 'Choose:', 'geot' ); ?></label></th>
    		<td>
    			<input type="radio" class="geot_include_mode" name="geot_include_mode" value="include"> <strong>Only show content in</strong><br />
            	<input type="radio" class="geot_include_mode" name="geot_include_mode" value="exclude"> <strong>Never show content in</strong><br /><br />
            </td>
        </tr>	
		<tr valign="top">
			<th><label for="geot_position"><?php _e( 'Choose regions:', 'geot' ); ?></label></th>
			<td>
				<?php
				if( is_array( $regions ) ) { ?>
					<select name="geot[region][]" id="geot_region" multiple class="geot-chosen-select-multiple" data-placeholder="<?php _e( 'Type or choose region name...', 'geot' );?>" >
						<?php
							if( is_array( $regions ) ) {
								foreach ($regions as $r) {
									?>
									<option value="<?php echo $r['name'];?>"> <?php echo $r['name']; ?></option>
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
			<th><label for="geot_position"><?php _e( 'Or choose countries:', 'geot' ); ?></label></th>
			<td>
				<select name="geot[country_code][]" id="geot_country" multiple class="geot-chosen-select-multiple" data-placeholder="<?php _e( 'Type or choose country name...', 'geot' );?>" >
					<?php
					if( is_array( $countries ) ) {
						foreach ($countries as $c) {
							?>
							<option value="<?php echo $c->iso_code;?>"> <?php echo $c->country; ?></option>
							<?php
						}
					}	
					?>
				</select>
			</td>
			<td colspan="2"></td>
		</tr>
		<tr valign="top">
			<th><label for="geot_position"><?php _e( 'Or type cities:', 'geot' ); ?></label></th>
			<td>
				<input type="text" name="geot[city]" id="geot_city"data-placeholder="<?php _e( 'Type cities separated by comma', 'geot' );?>"/>
			</td>
			<td colspan="2"></td>
		</tr>
		<tr valign="top">
			<th><label for="geot_position"><?php _e( 'Or choose city Regions:', 'geot' ); ?></label></th>
			<td>
				<select name="geot[city_region][]" id="geot_city_region" multiple class="geot-chosen-select-multiple" data-placeholder="<?php _e( 'Type or choose cities region name...', 'geot' );?>" >
					<?php
					if( is_array( $city_regions ) ) {
						foreach ($city_regions as $cr) {
							?>
							<option value="<?php echo strtolower($cr['name']);?>"> <?php echo $cr['name']; ?></option>
							<?php
						}
					}
					?>
				</select>
			</td>
			<td colspan="2"></td>
		</tr>
		<tr valign="top">
			<th><label for="geot_position"><?php _e( 'Or type states:', 'geot' ); ?></label></th>
			<td>
				<input type="text" name="geot[state]" id="geot_state" data-placeholder="<?php _e( 'Type states separated by comma', 'geot' );?>" />
			</td>
			<td colspan="2"></td>
		</tr>
	</table>
</div>
</form>