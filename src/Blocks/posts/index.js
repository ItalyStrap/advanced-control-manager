import edit from './edit';

const { __ } = wp.i18n;

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
		url: {
			type: 'string',
			source: 'attribute',
			selector: 'img',
			attribute: 'src',
		},
	},

	edit,

	save() {
		return null;
	},
};
