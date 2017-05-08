<?php

/**
 * Grab geot settings
 * @return mixed|void
 */
function geot_pro_settings(){
	return apply_filters('geot_pro/settings_page/opts', get_option( 'geot_pro_settings' ) );
}