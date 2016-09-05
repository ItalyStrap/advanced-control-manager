<?php
/**
 * Widget API: Widget_Taxonomies_Posts class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widget;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

/**
 * Widget Class for displaying Taxonomies_Posts
 */
class Widget_Taxonomies_Posts extends Widget {

	/**
	 * Init the constructor
	 */
	function __construct() {

		/**
		 * I don't like this and I have to find a better solution for loading script and style for widgets.
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'upload_scripts' ) );

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
			'label'				=> __( 'ItalyStrap Taxonomies Posts', 'italystrap' ),
			// Widget Backend Description.
			'description'		=> __( 'Displays list of categories with an array of options', 'italystrap' ),
			'fields'			=> $this->get_widget_fields( require( ITALYSTRAP_PLUGIN_PATH . 'options/options-taxonomies-posts.php' ) ),
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

		global $injector;

		// $category_posts = new Category_Posts;
		$category_posts = $injector->make( 'ItalyStrap\Core\Category_Posts' );
		return $category_posts->render();

		// $query_posts = Query_Posts::init();

		// $query_posts->get_widget_args( $instance );

		// return $query_posts->output();
	}
} // Class.
