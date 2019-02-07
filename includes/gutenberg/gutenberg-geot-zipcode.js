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

registerBlockType( 'geotargeting-pro/gutenberg-zipcode', {
	title: __( 'Target Zipcodes' , 'geot' ),
	description: __( 'You can place other blocks inside this container', 'geot' ),
	icon: el('img', { width: 20, height: 20, src: gutgeot.icon_zipcode }),
	category: 'geot-block',
	keywords: [ __( 'inner-blocks' ), ],

	attributes: {
		in_zipcodes: {
			type: 'string',
			default: '',
		},
		ex_zipcodes: {
			type: 'string',
			default: '',
		},
	},

	edit: function(props) {
		const { attributes, setAttributes, className, focus, setFocus } = props;
		const { in_zipcodes, ex_zipcodes } = attributes;

		const ALLOWED_BLOCKS = [];

		getBlockTypes().forEach( function( blockType ) {
			if( gutgeot.modules.indexOf(blockType.name) == -1 )
				ALLOWED_BLOCKS.push(blockType.name);
		} );

		var block_top_msg = __( 'You can modify the settings of the block in the sidebar.', 'geot' );
		var block_sign_msg = [];

		function onChangeInZipcodes( newContent ) {
			setAttributes( { in_zipcodes: newContent } );
		}

		function onChangeExZipcodes( newContent ) {
			setAttributes( { ex_zipcodes: newContent } );
		}

		if( in_zipcodes ) {
			block_sign_msg.push(__( 'Include Zipcodes', 'geot' ) + ' : ' + in_zipcodes);
		}

		if( ex_zipcodes ) {
			block_sign_msg.push(__( 'Exclude Zipcodes', 'geot' ) + ' : ' + ex_zipcodes);
		}


		if( block_sign_msg.length != 0 )
			block_top_msg = block_sign_msg.join(' , ');
		

		return el(Fragment, {},
			el(InspectorControls, {},
				el(PanelBody, { title: __( 'Target Zipcodes Settings' , 'geot' ) },
					el(PanelRow, {},
						el(TextControl, {
							label : __( 'Include Zipcodes', 'geot' ),
							value: in_zipcodes,
							onChange: onChangeInZipcodes,
							help : __('Type zip codes separated by commas.', 'geot')
						}),
					),
					el(PanelRow, {},
						el(TextControl, {
							label : __( 'Exclude Zipcodes', 'geot' ),
							value: ex_zipcodes,
							onChange: onChangeExZipcodes,
							help : __('Type zip codes separated by commas.', 'geot'),
						}),
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