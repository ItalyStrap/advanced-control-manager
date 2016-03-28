<?php
/**
 * Widget API: Widget_Posts class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Core;

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
			'fields'			=> $this->get_widget_fields( require( ITALYSTRAP_PLUGIN_PATH . 'options/options-posts.php' ) ),
			'control_options'	=> array( 'width' => 450 ),
			'widget_options'	=> array( 'customize_selective_refresh' => true ),
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
