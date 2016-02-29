<?php namespace ItalyStrap\Core;
/**
 * Widget API: Widget_Product class
 *
 * @package ItalyStrap
 * @since 4.0.0
 */

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Widget Class for post type
 */
class Widget_Product extends Widget {

	/**
	 * Fire Before Create Fields
	 *
	 * Allows to modify code before creating the fields.
	 *
	 * @access protected
	 * @param  array $fields The fields array.
	 * @return array         Return a fields array
	 */
	protected function before_field_types( array $fields ) {

		$fields['cats']['taxonomy'] = 'product_cat';

		// $fields['cats']['options'] = ( ( is_admin() ) ? get_taxonomies_list_array( 'product_cat' ) : null );

		$fields['tags']['taxonomy'] = 'product_tag';

		// $fields['tags']['options'] = ( ( is_admin() ) ? get_taxonomies_list_array( 'product_tag' ) : null );

		$fields['post_types'] = array(
			'name'		=> __( 'Post type', 'ItalyStrap' ),
			'desc'		=> __( 'Select the post type.', 'ItalyStrap' ),
			'id'		=> 'post_types',
			'type'		=> 'multiple_select',
			'class'		=> 'widefat post_types',
			'default'	=> 'product',
			'options'	=> array( 'product' => 'product' ),
			// 'validate'	=> 'numeric_comma',
			'filter'	=> 'sanitize_text_field',
			'section'	=> 'filter',
		);

		return apply_filters( 'italystrap_before_create_fields', $fields, $this->id_base );
	}

	/**
	 * Init the constructor
	 */
	function __construct() {

		/**
		 * Configure widget array.
		 *
		 * @var array
		 */
		$args = array(
			// Widget Backend label.
			'label'				=> __( 'ItalyStrap Product', 'ItalyStrap' ),
			// Widget Backend Description.
			'description'		=> __( 'Displays list of WC product with an array of options', 'ItalyStrap' ),
			'fields'			=> $this->get_widget_fields( require( ITALYSTRAP_PLUGIN_PATH . 'options/options-posts.php' ) ),
			'control_options'	=> array( 'width' => 450 ),
		 );

		/**
		 * Create Widget
		 */
		$this->create_widget( $args );
	}

	/**
	 * Dispay the widget content
	 *
	 * @param  array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param  array $instance The settings for the particular instance of the widget.
	 * @return string           Return the output
	 */
	public function widget_render( $args, $instance ) {

		$query_posts = Query_Posts::init();

		$query_posts->get_widget_args( $instance );

		return $query_posts->output();
	}
} // Class.
