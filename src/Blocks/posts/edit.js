/**
 * wp.element
 */
const {
	Component,
	Fragment,
} = wp.element;


// console.log(wp.element);

/**
 * wp.blocks
 */
// const {
// 	AlignmentToolbar,
// 	BlockDescription,
// 	QueryPanel,
// } = wp.blocks;

// console.log(wp.blocks);

const {
	InspectorControls,
	BlockAlignmentToolbar,
	// BlockControls,
}  = wp.editor;

// console.log(wp.editor);

const {
	__, // __()
} = wp.i18n;

const {
	PanelBody,
	ToggleControl,
	// Spinner,
	// withAPIData,
} = wp.components;

// console.log( "Data:" );
// console.log( wp.data );
// console.log( wp );
// console.log( withAPIData );
// console.log( wp.data.select( 'core' ) );

const {
	select,
} = wp.data;

// console.log( select( 'core' ) );

// const WP_Posts = new wp.api.collections.Posts();
// console.log(WP_Posts);

class PostsEdit extends Component {

	constructor() {
		super( ...arguments );

		// console.log(this.props);

		const {
			// attributes,
			setAttributes,
			className,
			focus
		} = this.props;

		// this.onSelectImage = this.onSelectImage.bind( this );

		this.state = {
			selectedImage: null,
		};

		setAttributes(
			{
				key: 'value'
			}
		);
	}

	toggleDisplayPostDate() {
		const { displayPostDate } = this.props.attributes;
		const { setAttributes } = this.props;

		setAttributes( { displayPostDate: ! displayPostDate } );
	}

	// toggleSetting: () => 

	render() {

		const {
			name
		} = this.props;

		console.log("this.props");
		console.log(this.props);
		const {
			attributes,
			setAttributes,
			// className,
			// focus
		} = this.props;

		console.log(attributes);

		const {
			displayPostDate,
			// align,
			// postLayout,
			// columns,
			// order,
			// orderBy,
			// categories,
			// postsToShow
		} = attributes;

		return (
			<Fragment>
				<InspectorControls key = "inspector" >
					<PanelBody
						title = {__('Posts Settings', 'italystrap')}
					/>
					<ToggleControl
						label={ __( 'Display post date' ) }
						checked={ displayPostDate }
						onChange={ this.toggleDisplayPostDate }
					/>
				</InspectorControls>
				<div key="container">
					<h1>{ name }</h1>
				</div>
			</Fragment>
		);
	}
}

export default PostsEdit;