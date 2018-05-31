import PostsBlock from './posts-block.js';

const { __ } = wp.i18n;

const {
	registerBlockType,
} = wp.blocks;

registerBlockType( 'italystrap/posts', {

	title: __( 'ItalyStrap Posts', 'italystrap' ),
	icon: 'universal-access-alt',
	category: 'widgets',
	keywords: [ __( 'posts', 'italystrap' ) ],

	supports: {
		html: false,
		// customClassName: false,
	},

	attributes: {
		url: {
			type: 'string',
			source: 'attribute',
			selector: 'img',
			attribute: 'src',
		},
	},

	edit: PostsBlock,

	save() {
		return null;
	},
} );
