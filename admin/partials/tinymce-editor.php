<?php
$countries 	= apply_filters('geot/get_countries', array());
$regions 	= apply_filters('geot/get_regions', array());
?>
<div id="geot_editor" class="shortcode_editor" title="Country Geo Target Text"  style="display:none;height:500px">
	<table class="form-table">
		<tr>
    		<th><label for="geot_what"><?php _e( 'Choose:', 'geot' ); ?></label></th>
    		<td>
    			<input type="radio" class="geot_include_mode" name="geot_include_mode" value="include" CHECKED> <strong>Only show content in</strong><br />
            	<input type="radio" class="geot_include_mode" name="geot_include_mode" value="exclude"> <strong>Never show content in</strong><br /><br />
            </td>
        </tr>	
		<tr valign="top">
			<th><label for="geot_position"><?php _e( 'Choose regions:', 'geot' ); ?></label></th>
			<td>
				<?php
				if( is_array( $regions ) ) { ?>
					<select name="geot[region][]" id="geot_region" multiple class="geot-chosen-select" data-placeholder="<?php _e( 'Type or choose region name...', 'geot' );?>" >
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
				<select name="geot[country_code][]" id="geot_country" multiple class="geot-chosen-select" data-placeholder="<?php _e( 'Type or choose country name...', 'geot' );?>" >
					<?php
					if( is_array( $countries ) ) {
						foreach ($countries as $c) {
							?>
							<option value="<?php echo $c->maxmind_country_code;?>"> <?php echo $c->maxmind_country; ?></option>
							<?php
						}
					}	
					?>
				</select>
			</td>
			<td colspan="2"></td>
		</tr>
	</table>
</div>