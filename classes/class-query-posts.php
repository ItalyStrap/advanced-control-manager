<?php namespace ItalyStrap\Core;
use \WP_Query;
/**
 *
 * Display a Bootstrap Carousel based on selected images and their titles and
 * descriptions. You need to include the Bootstrap CSS and Javascript files on
 * your own; otherwise the class will not work.
 *
 * @todo https://codex.wordpress.org/it:Shortcode_Gallery Aggiungere parametri mancanti
 *
 * @package Query_Posts
 * @version 1.0
 * @since   1.0
 */
if ( ! class_exists( 'Query_Posts' ) ) {

	/**
	* 
	*/
	class Query_Posts {

		/**
		 * WordPress query object.
		 * @var object
		 */
		private $args;
		
		/**
		 * Constructor.
		 * @param WP_Query $query The standard query of WordPress.
		 */
		function __construct( $args ) {

			$this->args = $this->get_attributes( $args );

			// var_dump( $args );

		}

		/**
		 * Get shortcode attributes.
		 *
		 * @param  array $args The carousel attribute.
		 * @return array Mixed array of shortcode attributes.
		 */
		public function get_attributes( $args ) {

			/**
			 * Define data by given attributes.
			 */
			$args = shortcode_atts_multidimensional_array( require( ITALYSTRAP_PLUGIN_PATH . 'options/options-posts.php' ), $args, 'query_posts' );

			$args = apply_filters( 'italystrap_query_posts_args', $args );

			return $args;

		}

		public function get_widget_args( $args ) {

			return $args;
		}

		public function output() {

			global $post;
			/**
			 * Get the current post id
			 *
			 * @var int
			 */
			$current_post_id = is_object( $post ) ? $post->ID : '';

			/**
			 * Excerpt more filter
			 * @var function
			 */
			$new_excerpt_more = function ( $more ) {
				return '...';
			};
			add_filter( 'excerpt_more', $new_excerpt_more );

			/**
			 * Excerpt length filter
			 *
			 * @var functions
			 */
			if ( $this->args['excerpt_length'] > 0 ) {
				add_filter( 'excerpt_length', function ( $length ) {
					return $this->args['excerpt_length'];
				} );
			}

			/**
			 * Variables for template
			 */

			// $class = $this->args['widget_class'];

			// $cats = ( empty( $this->args['cats'] ) ) ? array() : $this->args['cats'];
			// $tags = ( empty( $this->args['tags'] ) ) ? array() : $this->args['tags'];

 // var_dump( empty( $this->args['cats'] ) );
 // var_dump( empty( $this->args['cats'] ) || empty( $this->args['cats'][0] ) );
 // var_dump( empty( $this->args['cats'][0] ) );

 // var_dump($cats);
 // var_dump(count($cats));
 // var_dump($tags);
			// $cats = array();
			// $tags = array();
				// 'category__in'		=> ( ( '1' === $this->args['atcat'] ) ? $cats : null ),
				// 'tag__in'			=> ( ( '1' === $this->args['attag'] ) ? $tags : null ),


			/**
			 * Arguments for WP_Query
			 *
			 * @var array
			 */
			$args = array(
				'posts_per_page'	=> $this->args['posts_number'],
				'order'				=> $this->args['order'],
				'orderby'			=> $this->args['orderby'],
				// 'category__in'		=> $cats,
				// 'tag__in'			=> $tags,
				'post_type'			=> ( empty( $this->args['post_types'] ) ? 'post' : explode( ',', $this->args['post_types'] ) ),
				'no_found_rows'		=> true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
				);

			if ( ! empty( $this->args['cats'] ) ) {
				$args['category__in'] = $this->args['cats'];
				$args['update_post_term_cache'] = true;
			} 

			if ( ! empty( $this->args['tags'] ) ) {
				$args['tag__in'] = $this->args['tags'];
				$args['update_post_term_cache'] = true;
			}
			

			if ( 'meta_value' === $this->args['orderby'] ) {
				$args['meta_key'] = $this->args['meta_key'];
			}

			/**
			 * Sticky posts.
			 */
			$sticky_query = null;
			if ( 'only' === $this->args['sticky_post'] ) {
				$sticky_query = array( 'post__in' => get_option( 'sticky_posts' ) );
			} elseif ( 'hide' === $this->args['sticky_post'] ) {
				$sticky_query = array( 'post__not_in' => get_option( 'sticky_posts' ) );
			}

			if ( ! empty( $sticky_query ) ) {
				$args[ key( $sticky_query ) ] = reset( $sticky_query );
			}

			$args = apply_filters( 'italystrap_widget_query_args', $args );
var_dump($args);
			$widget_post_query = new WP_Query( $args );

			ob_start();

			if ( 'custom' === $this->args['template'] ) {

				// $custom_template_path = apply_filters( 'italystrap_custom_template_path',  '/templates/' . $this->args['template_custom'] . '.php', $this->args, $this->id_base );

				if ( locate_template( $custom_template_path ) ) {

					// include get_stylesheet_directory() . $custom_template_path;

				} else {

					// include 'templates/standard.php';

				}
			} elseif ( 'standard' === $this->args['template'] ) {

				// include 'templates/standard.php';
				include ITALYSTRAP_PLUGIN_PATH . '/templates/standard.php';

			} else {

				// include 'templates/legacy.php';

			}

			wp_reset_postdata();

			$output = ob_get_contents();
			ob_end_clean();

			return $output;

		}
	}

}
