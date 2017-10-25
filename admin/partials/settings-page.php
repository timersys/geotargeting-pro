<div class="wrap geot-settings">
	<h2>Geotargeting PRO v <?= GEOT_VERSION;?></h2>
	<form name="geot-settings" method="post" enctype="multipart/form-data">
		<table class="form-table">
			<tr valign="top" class="">
				<th colspan="2"><h3><?php _e( 'GeotargetingPRO settings:', 'geot' ); ?></h3></th>
				<td colspan="2">
				</td>
			</tr>
			<tr valign="top" class="">
				<th><label for="ajax_mode"><?php _e( 'Ajax Mode', 'geot'); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="ajax_mode" name="geot_settings[ajax_mode]" value="1" <?php checked($opts['ajax_mode'],'1');?>/>
						<p class="help"><?php _e( 'In Ajax mode, after page load an extra request is made to get all data and everything is updated with javascript. That makes the plugin compatible with any cache plugin. More info on: ', 'geot'); ?><a href="https://timersys.com/geotargeting/docs/ajax-mode/">Ajax mode info</a></p>
				</td>
			</tr>
			<tr valign="top" class="">
				<th><label for="menu_integration"><?php _e( 'Disable Menu integration', 'geot'); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="menu_integration" name="geot_settings[disable_menu_integration]" value="1" <?php checked($opts['disable_menu_integration'],'1');?>/>
						<p class="help"><?php _e( 'Check this to remove geotargeting options from menus', 'geot'); ?></p>
				</td>
			</tr>
			<tr valign="top" class="">
				<th><label for="widget_integration"><?php _e( 'Disable Widget Integration', 'geot'); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="widget_integration" name="geot_settings[disable_widget_integration]" value="1" <?php checked($opts['disable_widget_integration'],'1');?>/>
						<p class="help"><?php _e( 'Check this to remove geotargeting options from widgets', 'geot'); ?></p>
				</td>
			</tr>

			<tr><td><input type="submit" class="button-primary" value="<?php _e( 'Save settings', 'geot' );?>"/></td>
				<?php wp_nonce_field('geot_pro_save_settings','geot_nonce'); ?>
		</table>
	</form>
</div>
