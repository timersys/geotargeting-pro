<tr class="form-field">
	<th colspan="2"><h2><?php _e('Geotargeting Country','geot'); ?></h2></th>
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-in-countries"><?php _e('Include Countries'); ?></label>
	</th>
	<td>
		<input type="text" name="geot[in_countries]" id="geot-in-countries" class="selectize-input" value="<?php echo $geot['in_countries']; ?>" style="width:80%;">
		<br />
		<span class="description"><?php _e('Type country names or ISO codes separated by commas.', 'geot'); ?></span>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-in-countries-regions"><?php _e('Include Regions'); ?></label>
	</th>
	<td>
		<select name="geot[in_countries_regions][]" id="geot-in-countries-regions" class="geot-chosen-select-multiple" multiple="multiple">
			<?php foreach( $regions_countries as $region ) : ?>
				<option value="<?php echo $region; ?>" <?php selected( in_array($region, $geot['in_countries_regions']), true , true ); ?>><?php echo $region; ?></option>
			<?php endforeach; ?>
		</select>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-ex-countries"><?php _e('Exclude Countries'); ?></label>
	</th>
	<td>
		<input type="text" name="geot[ex_countries]" id="geot-ex-countries" class="selectize-input" value="<?php echo $geot['ex_countries']; ?>" style="width:80%;"><br />
		<span class="description"><?php _e('Type country names or ISO codes separated by commas.', 'geot'); ?></span>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-ex-countries-regions"><?php _e('Exclude Regions'); ?></label>
	</th>
	<td>
		<select name="geot[ex_countries_regions][]" id="geot-ex-countries-regions" class="geot-chosen-select-multiple" multiple="multiple">
			<?php foreach( $regions_countries as $region ) : ?>
				<option value="<?php echo $region; ?>" <?php selected( in_array($region, $geot['ex_countries_regions']), true , true ); ?>><?php echo $region; ?></option>
			<?php endforeach; ?>
		</select>
	</td>
</tr>


<tr class="form-field">
	<th colspan="2"><h2><?php _e('Geotargeting City','geot'); ?></h2></th>
</tr>


<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-in-cities"><?php _e('Include Cities'); ?></label>
	</th>
	<td>
		<input type="text" name="geot[in_cities]" id="geot-in-cities" class="selectize-input" value="<?php echo $geot['in_cities']; ?>" style="width:80%;"><br />
		<span class="description"><?php _e('Type city names separated by commas.', 'geot'); ?></span>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-in-cities-regions"><?php _e('Include Cities Regions'); ?></label>
	</th>
	<td>
		<select name="geot[in_cities_regions][]" id="geot-in-cities-regions" class="geot-chosen-select-multiple" multiple="multiple">
			<?php foreach( $regions_cities as $region ) : ?>
				<option value="<?php echo $region; ?>" <?php selected( in_array($region, $geot['in_cities_regions']), true , true ); ?>><?php echo $region; ?></option>
			<?php endforeach; ?>
		</select>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-ex-cities-regions"><?php _e('Exclude Cities'); ?></label>
	</th>
	<td>
		<input type="text" name="geot[ex_cities]" id="geot-ex-cities-regions" class="selectize-input" value="<?php echo $geot['ex_cities']; ?>" style="width:80%;"><br />
		<span class="description"><?php _e('Type city names separated by commas.', 'geot'); ?></span>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-ex-cities-regions"><?php _e('Exclude Cities Regions'); ?></label>
	</th>
	<td>
		<select name="geot[ex_cities_regions][]" id="geot-ex-cities-regions" class="geot-chosen-select-multiple" multiple="multiple">
			<?php foreach( $regions_cities as $region ) : ?>
				<option value="<?php echo $region; ?>" <?php selected( in_array($region, $geot['ex_cities_regions']), true , true ); ?>><?php echo $region; ?></option>
			<?php endforeach; ?>
		</select>
	</td>
</tr>


<tr class="form-field">
	<th colspan="2"><h2><?php _e('Geotargeting State','geot'); ?></h2></th>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-in-states"><?php _e('Include States'); ?></label>
	</th>
	<td>
		<input type="text" name="geot[in_states]" id="geot-in-states" class="selectize-input" value="<?php echo $geot['in_states']; ?>" style="width:80%;"><br />
		<span class="description"><?php _e('Type state names or ISO codes separated by commas.', 'geot'); ?></span>
	</td>
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-ex-states"><?php _e('Exclude States'); ?></label>
	</th>
	<td>
		<input type="text" name="geot[ex_states]" id="geot-ex-states" class="selectize-input" value="<?php echo $geot['ex_states']; ?>" style="width:80%;"><br />
		<span class="description"><?php _e('Type state names or ISO codes separated by commas.', 'geot'); ?></span>
	</td>
</tr>



<tr class="form-field">
	<th colspan="2"><h2><?php _e('Geotargeting Zipcode','geot'); ?></h2></th>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-in-zipcodes"><?php _e('Include Zipcodes'); ?></label>
	</th>
	<td>
		<input type="text" name="geot[in_zipcodes]" id="geot-in-zipcodes" class="selectize-input" value="<?php echo $geot['in_zipcodes']; ?>" style="width:80%;"><br />
		<span class="description"><?php _e('Type zip codes separated by commas.', 'geot'); ?></span>
	</td>
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
		<label for="geot-ex-zipcodes"><?php _e('Exclude Zipcodes'); ?></label>
	</th>
	<td>
		<input type="text" name="geot[ex_zipcodes]" id="geot-ex-zipcodes" class="selectize-input" value="<?php echo $geot['ex_zipcodes']; ?>" style="width:80%;"><br />
		<span class="description"><?php _e('Type zip codes separated by commas.', 'geot'); ?></span>
	</td>
</tr>