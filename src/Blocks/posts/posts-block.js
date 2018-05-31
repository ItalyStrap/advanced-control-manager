/**
 * wp.blocks
 */
const {
	AlignmentToolbar,
	BlockControls,
	InspectorControls,
	BlockDescription,
	QueryPanel,
} = wp.blocks;

/**
 * wp.element
 */
const {
	Component,
} = wp.element;

const { __ } = wp.i18n;

const { Spinner, withAPIData } = wp.components;

console.log( withAPIData );

// const WP_Posts = new wp.api.collections.Posts();

class PostsBlock extends Component {

	constructor() {
		super( ...arguments );

		// const { attributes, setAttributes, className, focus } = this.props;

		// this.onSelectImage = this.onSelectImage.bind( this );

		this.state = {
			selectedImage: null,
		};
	}

	// toggleSetting: () => 

	render() {
		// const { attributes, setAttributes, className, focus } = this.props;
		// console.log(setAttributes);
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
	}
}

export default PostsBlock;