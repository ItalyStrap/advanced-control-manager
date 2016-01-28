<?php namespace ItalyStrap\Core;

use \ItalyStrapAdminMediaSettings;

/**
 * Da leggere https://carlalexander.ca/polymorphism-wordpress-interfaces/
 */

if ( ! class_exists( 'Widget_Posts2' ) ) {

	/**
	 * Class
	 */
	class Widget_Posts2 extends Widget implements Interface_Widget {

		/**
		 * Init the constructor
		 */
		function __construct() {

			/**
			 * Instance of list of image sizes
			 * @var ItalyStrapAdminMediaSettings
			 */
			$image_size_media = new ItalyStrapAdminMediaSettings;
			$image_size_media_array = $image_size_media->get_image_sizes( array( 'full' => __( 'Real size', 'ItalyStrap' ) ) );

			$get_category_list_array = $this->get_taxonomies_list_array( 'category' );

			$get_post_tag_list_array = $this->get_taxonomies_list_array( 'post_tag' );

			/**
			 * List of posts type
			 * @todo Aggiungere any all'array
			 * @var array
			 */
			$get_post_types = get_post_types( array( 'public' => true ) );

			$get_post_types = ( class_exists( 'WooCommerce' ) ) ? array_merge( $get_post_types, array( 'product' => 'product' ) ) : $get_post_types ;

			$fields = array_merge( $this->title_field(), require( ITALYSTRAP_PLUGIN_PATH . 'options/options-posts.php' ) );

			/**
			 * Configure widget array.
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

		public function get_taxonomies_list_array( $tax ) {

			/**
			 * Array of taxonomies
			 * @todo Make object cache, see https://10up.github.io/Engineering-Best-Practices/php/#performance
			 * @todo Add a default value
			 * @var array
			 */
			$tax_arrays = get_terms( $tax );

			$get_taxonomies_list_array = array();

			foreach ( $tax_arrays as $tax_array ) {

				$get_taxonomies_list_array[ $tax_array->term_id ] = $tax_array->name;

			}

			return $get_taxonomies_list_array;
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
	} // class
}
