<?php
class ET_Builder_GeoCountry extends ET_Builder_Section {
	function init() {

		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'background'     => array(
						'title'       => esc_html__( 'Background', 'et_builder' ),
						'sub_toggles' => array(
							'main'     => '',
							'column_1' => array( 'name' => esc_html__( 'Column 1', 'et_builder' ) ),
							'column_2' => array( 'name' => esc_html__( 'Column 2', 'et_builder' ) ),
							'column_3' => array( 'name' => esc_html__( 'Column 3', 'et_builder' ) ),
						),
						'priority' => 80,
					),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'layout'          => esc_html__( 'Layout', 'et_builder' ),
					'width'           => array(
						'title'    => esc_html__( 'Sizing', 'et_builder' ),
						'priority' => 65,
					),
					'margin_padding'  => array(
						'title'       => esc_html__( 'Spacing', 'et_builder' ),
						'sub_toggles' => array(
							'main'     => '',
							'column_1' => array( 'name' => esc_html__( 'Column 1', 'et_builder' ) ),
							'column_2' => array( 'name' => esc_html__( 'Column 2', 'et_builder' ) ),
							'column_3' => array( 'name' => esc_html__( 'Column 3', 'et_builder' ) ),
						),
						'priority'   => 70,
					),
				),
			),
			'custom_css' => array(
				'toggles' => array(
					'classes' => array(
						'title'  => esc_html__( 'CSS ID & Classes', 'et_builder' ),
						'sub_toggles' => array(
							'main'     => '',
							'column_1' => array( 'name' => esc_html__( 'Column 1', 'et_builder' ) ),
							'column_2' => array( 'name' => esc_html__( 'Column 2', 'et_builder' ) ),
							'column_3' => array( 'name' => esc_html__( 'Column 3', 'et_builder' ) ),
						),
					),
					'custom_css' => array(
						'title'  => esc_html__( 'Custom CSS', 'et_builder' ),
						'sub_toggles' => array(
							'main'     => '',
							'column_1' => array( 'name' => esc_html__( 'Column 1', 'et_builder' ) ),
							'column_2' => array( 'name' => esc_html__( 'Column 2', 'et_builder' ) ),
							'column_3' => array( 'name' => esc_html__( 'Column 3', 'et_builder' ) ),
						),
					),
				),
			),
		);

		$this->advanced_fields = array(
			'background' => array(
				'use_background_color'          => 'fields_only',
				'use_background_image'          => true,
				'use_background_color_gradient' => true,
				'use_background_video'          => true,
				'css'                           => array(
					'important' => 'all',
					'main'      => 'div.et_pb_section2%%order_class%%',
				),
				'options'    => array(
					'background_color' => array(
						'default' => '',
						'hover' => 'tabs',
					),
					'allow_player_pause' => array(
						'default_on_front' => 'off',
					),
					'background_video_pause_outside_viewport' => array(
						'default_on_front' => 'on',
					),
					'parallax' => array(
						'default_on_front' => 'off',
					),
					'parallax_method' => array(
						'default_on_front' => 'on',
					),
				),
			),
			'max_width'  => array(
				'css' => array(
					'module_alignment' => '%%order_class%%',
				),
				'options' => array(
					'module_alignment' => array(
						'label' => esc_html__( 'Section Alignment', 'et_builder' ),
					),
				),
			),
			'fonts'      => false,
			'text'       => false,
			'button'     => false,
		);

		$this->help_videos = array(
			array(
				'id'   => esc_html( '3kmJ_mMVB1w' ),
				'name' => esc_html__( 'An introduction to Sections', 'et_builder' ),
			),
		);
	}

	function get_fields() {
		$fields = array(
			'inner_shadow' => array(
				'label'           => esc_html__( 'Show Inner Shadow', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'default'         => 'off',
				'description'     => esc_html__( 'Here you can select whether or not your section has an inner shadow. This can look great when you have colored backgrounds or background images.', 'et_builder' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'layout',
				'default_on_front'=> 'off',
			),
			'make_fullwidth' => array(
				'label'             => esc_html__( 'Make This Section Fullwidth', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'default'           => 'off',
				'depends_show_if'   => 'off',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'width',
				'specialty_only'    => 'yes',
			),
			'use_custom_width' => array(
				'label'             => esc_html__( 'Use Custom Width', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'default'           => 'off',
				'affects'           => array(
					'make_fullwidth',
					'custom_width',
					'width_unit',
				),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'width',
				'specialty_only'    => 'yes',
			),
			'width_unit' => array(
				'label'             => esc_html__( 'Unit', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'on'  => esc_html__( 'px', 'et_builder' ),
					'off' => '%',
				),
				'default'           => 'on',
				'button_options'    => array(
					'button_type' => 'equal',
				),
				'depends_show_if'   => 'on',
				'affects'           => array(
					'custom_width_px',
					'custom_width_percent',
				),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'width',
				'specialty_only'    => 'yes',
			),
			'custom_width_px' => array(
				'default'             => '1080px',
				'label'               => esc_html__( 'Custom Width', 'et_builder' ),
				'type'                => 'range',
				'option_category'     => 'layout',
				'depends_show_if_not' => 'off',
				'validate_unit'       => true,
				'fixed_unit'          => 'px',
				'range_settings'      => array(
					'min'  => 500,
					'max'  => 2600,
					'step' => 1,
				),
				'tab_slug'            => 'advanced',
				'toggle_slug'         => 'width',
				'specialty_only'      => 'yes',
				'hover'               => 'tabs',
			),
			'custom_width_percent' => array(
				'default'         => '80%',
				'label'           => esc_html__( 'Custom Width', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'depends_show_if' => 'off',
				'validate_unit'   => true,
				'fixed_unit'      => '%',
				'range_settings'  => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'width',
				'specialty_only'  => 'yes',
				'hover'           => 'tabs',
			),
			'make_equal' => array(
				'label'             => esc_html__( 'Equalize Column Heights', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'default'           => 'off',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'width',
				'specialty_only'    => 'yes',
			),
			'use_custom_gutter' => array(
				'label'             => esc_html__( 'Use Custom Gutter Width', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'layout',
				'options'           => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'default'           => 'off',
				'affects'           => array(
					'gutter_width',
				),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'width',
				'specialty_only'    => 'yes',
			),
			'gutter_width' => array(
				'label'            => esc_html__( 'Gutter Width', 'et_builder' ),
				'type'             => 'range',
				'option_category'  => 'layout',
				'range_settings'   => array(
					'min'       => 1,
					'max'       => 4,
					'step'      => 1,
					'min_limit' => 1,
					'max_limit' => 4,
				),
				'depends_show_if'  => 'on',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'width',
				'specialty_only'   => 'yes',
				'validate_unit'    => false,
				'fixed_range'      => true,
				'default_on_front' => et_get_option( 'gutter_width', 3 ),
				'hover'            => 'tabs',
			),
			'columns_background' => array(
				'type'            => 'column_settings_background',
				'option_category' => 'configuration',
				'toggle_slug'     => 'background',
				'specialty_only'  => 'yes',
				'priority'        => 99,
			),
			'columns_padding' => array(
				'type'            => 'column_settings_padding',
				'option_category' => 'configuration',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'margin_padding',
				'specialty_only'  => 'yes',
				'priority'        => 99,
			),
			'fullwidth' => array(
				'type'    => 'hidden',
				'default_on_front' => 'off',
			),
			'specialty' => array(
				'type'    => 'skip',
				'default_on_front' => 'off',
			),
			'columns_css' => array(
				'type'            => 'column_settings_css',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'custom_css',
				'priority'        => 20,
			),
			'columns_css_fields' => array(
				'type'            => 'column_settings_css_fields',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'classes',
				'priority'        => 20,
			),
			'custom_padding_last_edited' => array(
				'type'           => 'skip',
				'tab_slug'       => 'advanced',
				'specialty_only' => 'yes',
			),
			'__video_background' => array(
				'type' => 'computed',
				'computed_callback' => array( 'ET_Builder_Section', 'get_video_background' ),
				'computed_depends_on' => array(
					'background_video_mp4',
					'background_video_webm',
					'background_video_width',
					'background_video_height',
				),
				'computed_minimum' => array(
					'background_video_mp4',
					'background_video_webm',
				),
			),
			'prev_background_color' => array(
				'type' => 'skip',
			),
			'next_background_color' => array(
				'type' => 'skip',
			),
		);

		$column_fields = $this->get_column_fields( 3, array(
			'parallax'                                   => array(
				'default_on_front' => 'off',
			),
			'parallax_method'                            => array(
				'default_on_front' => 'on',
			),
			'background_color'                           => array(),
			'bg_img'                                     => array(),
			'background_size'                            => array(),
			'background_position'                        => array(),
			'background_repeat'                          => array(),
			'background_blend'                           => array(),
			'padding_top_bottom_link'                    => array(),
			'padding_left_right_link'                    => array(),
			'use_background_color_gradient'              => array(),
			'background_color_gradient_start'            => array(),
			'background_color_gradient_end'              => array(),
			'background_color_gradient_type'             => array(),
			'background_color_gradient_direction'        => array(),
			'background_color_gradient_direction_radial' => array(),
			'background_color_gradient_start_position'   => array(),
			'background_color_gradient_end_position'     => array(),
			'background_color_gradient_overlays_image'   => array(),
			'background_video_mp4'                       => array(
				'computed_affects' => array(
					'__video_background',
				),
			),
			'background_video_webm'                      => array(
				'computed_affects' => array(
					'__video_background',
				),
			),
			'background_video_width'                     => array(
				'computed_affects' => array(
					'__video_background',
				),
			),
			'background_video_height'                    => array(
				'computed_affects' => array(
					'__video_background',
				),
			),
			'allow_player_pause'                         => array(
				'computed_affects' => array(
					'__video_background',
				),
			),
			'background_video_pause_outside_viewport'    => array(
				'computed_affects'   => array(
					'__video_background',
				),
			),
			'__video_background'                         => array(
				'type'                => 'computed',
				'computed_callback'   => array(
					'ET_Builder_Column',
					'get_column_video_background'
				),
				'computed_depends_on' => array(
					'background_video_mp4',
					'background_video_webm',
					'background_video_width',
					'background_video_height',
				),
				'computed_minimum'    => array(
					'background_video_mp4',
					'background_video_webm',
				),
			),
			'padding_top'                                => array( 'tab_slug' => 'advanced' ),
			'padding_right'                              => array( 'tab_slug' => 'advanced' ),
			'padding_bottom'                             => array( 'tab_slug' => 'advanced' ),
			'padding_left'                               => array( 'tab_slug' => 'advanced' ),
			'padding_top_bottom_link'                    => array( 'tab_slug' => 'advanced' ),
			'padding_left_right_link'                    => array( 'tab_slug' => 'advanced' ),
			'padding_%column_index%_tablet'              => array(
				'has_custom_index_location' => true,
				'tab_slug' => 'advanced',
			),
			'padding_%column_index%_phone'               => array(
				'has_custom_index_location' => true,
				'tab_slug' => 'advanced',
			),
			'padding_%column_index%_last_edited'         => array(
				'has_custom_index_location' => true,
				'tab_slug' => 'advanced',
			),
			'module_id'                                  => array( 'tab_slug' => 'custom_css' ),
			'module_class'                               => array( 'tab_slug' => 'custom_css' ),
			'custom_css_before'                          => array( 'tab_slug' => 'custom_css' ),
			'custom_css_main'                            => array( 'tab_slug' => 'custom_css' ),
			'custom_css_after'                           => array( 'tab_slug' => 'custom_css' ),
		) );

		return array_merge( $fields, $column_fields );
	}
}
new ET_Builder_GeoCountry;
