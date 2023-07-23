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
	ServerSideRender
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

function PostsEdit( props ) {

	const toggleAttribute = ( attributeName ) => ( newValue ) =>
		setAttributes( { [ attributeName ]: newValue } );

	const {
		name,
		attributes,
		setAttributes,
		// className,
		// focus
	} = props;

	const {
		exclude_current_post,
		show_thumbnail,
		// align,
		// postLayout,
		// columns,
		// order,
		// orderBy,
		// categories,
		// postsToShow
	} = attributes;

	const controls = [
		{
			key: 0,
			label: __( 'Exclude current post', 'italystrap' ),
			checked: exclude_current_post,
			onChange: toggleAttribute("exclude_current_post"),
		},
		{
			key: 1,
			label: __( 'Show Thumbnail', 'italystrap' ),
			checked: show_thumbnail,
			onChange: toggleAttribute("show_thumbnail"),
		},
	];

	return (
		<Fragment>
			<InspectorControls key="inspector" >
				<PanelBody
					title = {__('Posts Settings', 'italystrap')}
				>
					{
						controls.map( ( args ) => {
							return (
								<ToggleControl {...args} />
							);
						} )
					}
				</PanelBody>
			</InspectorControls>
			<ServerSideRender
				block={name}
				attributes={ attributes }
			/>
		</Fragment>
	);
}

export default PostsEdit;