<?php namespace ItalyStrap\Core;
/**
 * Widget API: Widget_Posts class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Widget Class for post type
 */
class Widget_Posts extends Widget {

	/**
	 * Init the constructor
	 */
	function __construct() {

		/**
		 * List of posts type
		 *
		 * @todo Aggiungere any all'array
		 * @var array
		 */
		$get_post_types = get_post_types( array( 'public' => true ) );

		$get_post_types = ( class_exists( 'WooCommerce' ) ) ? array_merge( $get_post_types, array( 'product' => 'product' ) ) : $get_post_types ;

		/**
		 * Widget form fields
		 * @var array
		 */
		$fields = $this->get_widget_fields( require( ITALYSTRAP_PLUGIN_PATH . 'options/options-posts.php' ) );

		/**
		 * Configure widget array.
		 *
		 * @var array
		 */
		$args = array(
			// Widget Backend label.
			'label'				=> __( 'ItalyStrap Posts', 'ItalyStrap' ),
			// Widget Backend Description.
			'description'		=> __( 'Displays list of posts with an array of options', 'ItalyStrap' ),
			'fields'			=> $fields,
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
	 */
	public function widget_render( $args, $instance ) {

		$out = '';

		$query_posts = new Query_Posts( $instance );

		$out = $query_posts->output();

		return apply_filters( 'widget_text', $out );
	}
} // Class.
