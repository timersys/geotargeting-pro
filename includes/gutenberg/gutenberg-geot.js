var el = wp.element.createElement;
var Fragment = wp.element.Fragment;
var { __ } = wp.i18n; // Import __() from wp.i18n
var { registerBlockType, getBlockTypes } = wp.blocks; // Import registerBlockType() from wp.blocks
var { InspectorControls, InnerBlocks } = wp.editor;
var { PanelRow, PanelBody, SelectControl, TextControl } = wp.components;


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

registerBlockType( 'geotargeting-pro/gutenberg-country', {
	title: __( 'Target Countries' , 'geot' ),
	description: __( 'Place elements inside this geot container' , 'geot'),
	icon: el('img', { width: 20, height: 20, src: geotcountry.icon }),
	category: 'geot-block',
	keywords: [ __( 'inner-blocks' ), ],

	attributes: {
		in_countries: {
			//selector: 'p.include-countries',
			//source: 'text',
			type: 'string',
			default: '',
		},
		in_regions: {
			//selector: 'p.include-regions',
			//source: 'text',
			type: 'array',
			default: [],
		},
		ex_countries: {
			//selector: 'p.exclude-countries',
			//source: 'text',
			type: 'string',
			default: '',
		},
		ex_regions: {
			//selector: 'p.exclude-regions',
			//source: 'text',
			type: 'array',
			default: [],
		},
	},

	edit: function(props) {
		const { attributes, setAttributes, className, focus, setFocus } = props;
		const { in_countries, in_regions, ex_countries, ex_regions } = attributes;

		const ALLOWED_BLOCKS = [];
		const NOT_ALLOWED_BLOCKS = [
										'geotargeting-pro/gutenberg-country',
										'geotargeting-pro/gutenberg-city',
										'geotargeting-pro/gutenberg-state'
									];

		getBlockTypes().forEach( function( blockType ) {
			if( NOT_ALLOWED_BLOCKS.indexOf(blockType.name) == -1 )
				ALLOWED_BLOCKS.push(blockType.name);
		} );

		var block_top_msg = __( 'Please, custom this block in settings panel to right side', 'geot' );
		var block_sign_msg = [];

		function onChangeInCountries( newContent ) {
			setAttributes( { in_countries: newContent } );
		}

		function onChangeExCountries( newContent ) {
			setAttributes( { ex_countries: newContent } );
		}

		function onChangeInRegions( newContent ) {
			setAttributes( { in_regions: newContent } );
		}

		function onChangeExRegions( newContent ) {
			setAttributes( { ex_regions: newContent } );
		}

		if( in_countries ) {
			block_sign_msg.push(__( 'Include Countries', 'geot' ) + ' : ' + in_countries);
		}

		if( ex_countries ) {
			block_sign_msg.push(__( 'Exclude Countries', 'geot' ) + ' : ' + ex_countries);
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
				el(PanelBody, { title: __( 'Target Countries Settings' , 'geot' ) },
					el(PanelRow, {},
						el(TextControl, {
							label : __( 'Include Countries', 'geot' ),
							value: in_countries,
							onChange: onChangeInCountries,
							help : __('Type country name or ISO code. Also you can write a comma separated list of countries', 'geot')
						}),
					),
					el(PanelRow, {},
						el(SelectControl, {
								label: __('Include Regions', 'geot'),
								multiple : true,
								options : geotcountry.regions,
								onChange: onChangeInRegions,
								value: in_regions,
								help: __('Choose region name to show content to', 'geot'),
							},
						),
					),
					el(PanelRow, {},
						el(TextControl, {
							label : __( 'Exclude Countries', 'geot' ),
							value: ex_countries,
							onChange: onChangeExCountries,
							help : __('Type country name or ISO code. Also you could write a comma separated list of countries', 'geot')
						}),
					),
					el(PanelRow, {},
						el(SelectControl, {
								label: __('Exclude Regions', 'geot'),
								multiple : true,
								options : geotcountry.regions,
								onChange: onChangeExRegions,
								value: ex_regions,
								help: __('Choose region name to exclude content', 'geot'),
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