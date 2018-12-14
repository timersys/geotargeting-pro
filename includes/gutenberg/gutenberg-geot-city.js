/**
 * Register: Geotargenting Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */

registerBlockType( 'geotargeting-pro/gutenberg-city', {
	title: __( 'Target Cities' , 'geot' ),
	description: __( 'You can place other blocks inside this container', 'geot' ),
	icon: el('img', { width: 20, height: 20, src: gutgeot.icon_city }),
	category: 'geot-block',
	keywords: [ __( 'inner-blocks' ), ],

	attributes: {
		in_cities: {
			type: 'string',
			default: '',
		},
		in_regions: {
			type: 'array',
			default: [],
		},
		ex_cities: {
			type: 'string',
			default: '',
		},
		ex_regions: {
			type: 'array',
			default: [],
		},
	},

	edit: function(props) {
		const { attributes, setAttributes, className, focus, setFocus } = props;
		const { in_cities, in_regions, ex_cities, ex_regions } = attributes;

		const ALLOWED_BLOCKS = [];

		getBlockTypes().forEach( function( blockType ) {
			if( gutgeot.modules.indexOf(blockType.name) == -1 )
				ALLOWED_BLOCKS.push(blockType.name);
		} );

		var block_top_msg = __( 'You can modify the settings of the block in the sidebar.', 'geot' );
		var block_sign_msg = [];

		function onChangeInCities( newContent ) {
			setAttributes( { in_cities: newContent } );
		}

		function onChangeExCities( newContent ) {
			setAttributes( { ex_cities: newContent } );
		}

		function onChangeInRegions( newContent ) {
			setAttributes( { in_regions: newContent } );
		}

		function onChangeExRegions( newContent ) {
			setAttributes( { ex_regions: newContent } );
		}

		if( in_cities ) {
			block_sign_msg.push(__( 'Include Cities', 'geot' ) + ' : ' + in_cities);
		}

		if( ex_cities ) {
			block_sign_msg.push(__( 'Exclude Cities', 'geot' ) + ' : ' + ex_cities);
		}

		if( in_regions.length ) {
			block_sign_msg.push(__('Include Regions', 'geot') + ' : ' + in_regions.join(' , '));
		}

		if( ex_regions.length ) {
			block_sign_msg.push(__('Exclude Regions', 'geot') + ' : ' + ex_regions.join(' , '));
		}

		if( block_sign_msg.length != 0 )
			block_top_msg = block_sign_msg.join(' , ');
		

		return el(Fragment, {},
			el(InspectorControls, {},
				el(PanelBody, { title: __( 'Target Cities Block' , 'geot' ) },
					el(PanelRow, {},
						el(TextControl, {
							label : __( 'Include Cities', 'geot' ),
							value: in_cities,
							onChange: onChangeInCities,
							help : __('Type city names separated by comma.', 'geot')
						}),
					),
					el(PanelRow, {},
						el(SelectControl, {
								label: __('Include City Regions', 'geot'),
								multiple : true,
								options : gutgeot.regions_city,
								onChange: onChangeInRegions,
								help: __('Choose region name to show content to', 'geot'),
							},
						),
					),
					el(PanelRow, {},
						el(TextControl, {
							label : __( 'Exclude Cities', 'geot' ),
							value: ex_cities,
							onChange: onChangeExCities,
							help : __('Type city names separated by comma.', 'geot'),
						}),
					),
					el(PanelRow, {},
						el(SelectControl, {
								label: __('Exclude Regions', 'geot'),
								multiple : true,
								options : gutgeot.regions_city,
								onChange: onChangeExRegions,
								help: __('Choose region name to exclude content.', 'geot'),
							},
						),
					),
				),
			),
			el('div', { className: className },
				el('div', {}, block_top_msg),
				el(InnerBlocks, { allowedBlocks: ALLOWED_BLOCKS })
			)
		);
	},
	save: function() {
		return el('div', {}, el(InnerBlocks.Content) );
	}
} );