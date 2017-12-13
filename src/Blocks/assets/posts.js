/**
 * {@link http://gutenberg-devdoc.surge.sh/}
 */
// const { registerBlockType } = wp.blocks;
// const blockStyle = { backgroundColor: '#900', color: '#fff', padding: '20px' };

// registerBlockType( 'italystrap/posts', {
// 	title: 'Posts',

// 	icon: 'universal-access-alt',

// 	category: 'widgets',

// 	edit() {
// 		return '<p style={ blockStyle }>Block Posts</p>';
// 	},

// 	save() {
// 		return '<p style={ blockStyle }>Block Posts</p>';
// 	},
// } );

( function( blocks, i18n, element, api ) {
	var registerBlockType = blocks.registerBlockType;
	var el = element.createElement;
	var __ = i18n.__;
	var Editable = wp.blocks.Editable;
	var AlignmentToolbar = wp.blocks.AlignmentToolbar;
	var BlockControls = blocks.BlockControls;
	var InspectorControls = blocks.InspectorControls;
	var restApi = api;

	function getLatestPosts( postsToShow = 5 ) {
		const postsCollection = new wp.api.collections.Posts();

		const posts = postsCollection.fetch( {
			data: {
				per_page: postsToShow,
			},
		} );

		return posts;
	}

	registerBlockType( 'italystrap/posts', {
		title: __( 'ItalyStrap Posts', 'italystrap' ),
		icon: 'universal-access-alt',
		category: 'widgets',

		attributes: {
			url: {
				type: 'string',
				source: 'attribute',
				selector: 'img',
				attribute: 'src',
			},
			alt: {
				type: 'string',
				source: 'attribute',
				selector: 'img',
				attribute: 'alt',
			},
			caption: {
				type: 'array',
				source: 'children',
				selector: 'figcaption',
			},
			href: {
				type: 'string',
				source: 'attribute',
				selector: 'a',
				attribute: 'href',
			},
			id: {
				type: 'number',
			},
			align: {
				type: 'string',
			},
			width: {
				type: 'number',
			},
			height: {
				type: 'number',
			},
		},

		edit: function( props ) {
			// console.log( props );
			// return __( 'ItalyStrap Posts.', 'italystrap' );
			return [
				!! focus && el(
					BlockControls,
					{ key: 'controls' },
					el(
						AlignmentToolbar,
						{
							// value: alignment,
							// onChange: onChangeAlignment
						}
					)
				)
			];
		},
		save: function() {
			return null;
		},
	} );
} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
	window.wp.api
);