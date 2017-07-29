/**
 * {@link http://gutenberg-devdoc.surge.sh/}
 */
const { registerBlockType } = wp.blocks;
const blockStyle = { backgroundColor: '#900', color: '#fff', padding: '20px' };

registerBlockType( 'italystrap/posts', {
	title: 'Posts',

	icon: 'universal-access-alt',

	category: 'widgets',

	edit() {
		return '<p style={ blockStyle }>Block Posts</p>';
	},

	save() {
		return '<p style={ blockStyle }>Block Posts</p>';
	},
} );