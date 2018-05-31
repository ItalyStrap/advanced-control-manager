/**
 * {@link http://gutenberg-devdoc.surge.sh/}
 */
const { __ } = wp.i18n;

const {
	registerBlockType,
	AlignmentToolbar,
	BlockControls,
	InspectorControls,
	BlockDescription,
	QueryPanel,
} = wp.blocks;

const { Spinner } = wp.components;

const WP_Posts = new wp.api.collections.Posts();

registerBlockType( 'italystrap/posts', {

	title: __( 'ItalyStrap Posts', 'italystrap' ),
	icon: 'universal-access-alt',
	category: 'widgets',
	keywords: [ __( 'posts', 'italystrap' ) ],

	attributes: {
		url: {
			type: 'string',
			source: 'attribute',
			selector: 'img',
			attribute: 'src',
		},
	},

	edit( props ) {
		// console.log(props);
		// console.log(wp);
		// console.log(wp.api);
		// console.log(WP_Posts);
		// console.log(WP_Posts.fetch( {
		// 	data: {
		// 		per_page: 5,
		// 	},
		// } ));

		// console.log(InspectorControls);

		return [
			focus && (
				<div key="container">
					<InspectorControls key="inspector">
						<BlockDescription>
							<p>{ __( 'Shows a list of your site\'s most recent posts.' ) }</p>
						</BlockDescription>
						<h3>{ __( 'Latest Posts Settings' ) }</h3>
					</InspectorControls>
					{ __( 'ItalyStrap Posts.', 'italystrap' ) }
				</div>
			)
		]
	},

	save() {
		return null;
	},
} );
