const { __ } = wp.i18n;

/**
 * Internal dependencies
 */
import metadata from './block.json';
import edit from './edit';

export const name = 'italystrap/posts';

export const settings = {

	title: __( 'ItalyStrap Posts', 'italystrap' ),

	icon: 'universal-access-alt',
	category: 'widgets',
	keywords: [ __( 'posts', 'italystrap' ) ],

	supports: {
		html: false,
		// customClassName: false,
	},

	getEditWrapperProps( attributes ) {
		// console.log("Attributes");
		// console.log(attributes);
		// const { align } = attributes;
		// if ( 'left' === align || 'right' === align || 'wide' === align || 'full' === align ) {
		// 	return { 'data-align': align };
		// }
	},

	attributes: {
		exclude_current_post: {
			type: 'boolean',
			default: false,
		},
		show_thumbnail: {
			type: 'boolean',
			default: false,
		},
	},

	edit,

	save: () => null,
};
