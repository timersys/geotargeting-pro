<?php

class acf_field_geot_field extends acf_field {
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
		
		
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct()
	{
		// vars
		$this->name = 'geot_field';
		$this->label = __('GeoTargetting');
		$this->category = __("Basic",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			// add default here to merge into your field. 
			// This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
			'geot_show' 		=> 'regions',
			'geot_condition'	=> 'include',
			'geot_regions'		=> '',
			'geot_countries'	=> ''
		);
		
		
		// do not delete!
    	parent::__construct();
    	
    	
    	// settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.0'
		);

	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like below) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field )
	{
		// defaults?
		
		$field = array_merge($this->defaults, $field);
		
		
		// key is needed in the field names to correctly save the data
		$key = $field['name'];
		
		
		// Create Field Options HTML
		?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Show Regions or Countries",'geot'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][geot_show]',
			'value'		=>	$field['geot_show'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'regions' => __('Regions' ,'geot'),
				'city-regions' => __('City Regions' ,'geot'),
				'countries' => __('Countries', 'geot'),
			)
		));
		
		?>
	</td>
</tr>
		<?php
		
	}
	
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function create_field( $field )
	{
		// defaults?
		
		$field = array_merge($this->defaults, $field);
		
		?>
		<div>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	$field['name'].'[geot_condition]',
			'value'		=>	$field['value']['geot_condition'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'exclude' => __('Exclude to' ,'geot'),
				'include' => __('Show to', 'geot'),
			)
		));
		
		if( 'regions' == $field['geot_show']) {
			
			$regions 	= geot_country_regions();
				
			if( is_array( $regions ) ) { 
				
				foreach ($regions as $r) {
					if (!empty( $r['name'] ) ) {
						$choices[$r['name']] = $r['name'];
					}	
				}

				do_action('acf/create_field', array(
					'type'		=>	'select',
					'multiple'	=>	true,
					'name'		=>	$field['name'].'[geot_regions]',
					'value'		=>	$field['value']['geot_regions'],
					'choices'	=>	$choices
				));				
			
			} else { ?>
			
				<p> Add some regions first.</p>
			
			<?php	
			}				
		} elseif( 'city-regions' == $field['geot_show']) {

			$regions 	= geot_city_regions();

			if( is_array( $regions ) ) {

				foreach ($regions as $r) {
					if (!empty( $r['name'] ) ) {
						$choices[$r['name']] = $r['name'];
					}
				}

				do_action('acf/create_field', array(
					'type'		=>	'select',
					'multiple'	=>	true,
					'name'		=>	$field['name'].'[geot_city_regions]',
					'value'		=>	$field['value']['geot_city_regions'],
					'choices'	=>	$choices
				));

			} else { ?>

				<p> Add some regions first.</p>

			<?php
			}
		} else {

			$countries 	= geot_countries();
			
			if( is_array( $countries ) ) { 
				
				foreach ($countries as $r) {
					if( !empty( $r->country ) ) {

						$choices[$r->iso_code] = $r->country;
						
					}
				}

				do_action('acf/create_field', array(
					'type'		=>	'select',
					'multiple'	=>	true,
					'name'		=>	$field['name'].'[geot_countries]',
					'value'		=>	$field['value']['geot_countries'],
					'choices'	=>	$choices
				));				
			
			} else { ?>
			
				<p> Add some countries first.</p>
			
			<?php	
			}							
		}
		?>			
		</div>
		<?php
	}
	


	
}


// create field
new acf_field_geot_field();

?>
