<?php

/**
 * Grab geot settings
 * @return mixed|void
 */
function geot_pro_settings(){
	return apply_filters('geot_pro/settings_page/opts', get_option( 'geot_pro_settings' ) );
}


/**
 * Intercept Geot
 *
 */
function geot_pro_format($geot) {

	foreach( geot_pro_default() as $key => $value ) {
		if( isset($geot[$key]) )
			$output[$key] = is_array($geot[$key]) ? array_map('esc_html', $geot[$key]) : esc_html($geot[$key]);
		else
			$output[$key] = $value;
	}

	return $output;
}


function geot_pro_default() {
	$default = array(
				'in_countries'			=> '',
				'ex_countries'			=> '',
				'in_countries_regions'	=> array(),
				'ex_countries_regions'	=> array(),
				'in_cities'				=> '',
				'ex_cities'				=> '',
				'in_cities_regions'		=> array(),
				'ex_cities_regions'		=> array(),
				'in_states'				=> '',
				'ex_states'				=> '',
				'in_zipcodes'			=> '',
				'ex_zipcodes'			=> '',
			);

	return apply_filters('geot_pro/global/default', $default);
}



function geot_version_compare($version1, $version2, $operator = null) {
	$p = '#(\.0+)+($|-)#';
	$version1 = preg_replace($p, '', $version1);
	$version2 = preg_replace($p, '', $version2);
	return isset($operator) ? 
		version_compare($version1, $version2, $operator) : 
		version_compare($version1, $version2);
}