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
		 * @param WP_Query $query The standard quesry of WordPress.
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

		public function output() {

			global $post;
			$current_post_id = is_object( $post ) ? $post->ID : '';

			// foreach ( $this->args as $key => $value)
			// 	$$key = $value;

			// var_dump( $this->args );

			$class = $this->args['widget_class'];
			$post_types = empty( $this->args['post_types'] ) ? 'post' : explode( ',', $this->args['post_types'] );
			$cats = empty( $this->args['cats'] ) ? '' : explode( ',', $this->args['cats'] );
			$tags = empty( $this->args['tags'] ) ? '' : explode( ',', $this->args['tags'] );
			$atcat = $this->args['atcat'] ? true : false;
			$thumb_size = $this->args['thumb_size'];
			$attag = $this->args['attag'] ? true : false;
			$excerpt_length = $this->args['excerpt_length'];
			$excerpt_readmore = $this->args['excerpt_readmore'];
			$sticky = $this->args['sticky_post'];
			$order = $this->args['order'];
			$orderby = $this->args['orderby'];
			$meta_key = $this->args['meta_key'];
			$custom_fields = $this->args['custom_fields'];

			/**
			 * Sticky posts.
			 */
			if ( 'only' === $sticky ) {

				$sticky_query = array( 'post__in' => get_option( 'sticky_posts' ) );

			} elseif ( 'hide' === $sticky ) {

				$sticky_query = array( 'post__not_in' => get_option( 'sticky_posts' ) );

			} else {

				$sticky_query = null;

			}

			/**
			 * If $atcat true and in category
			 */
			if ( $atcat && is_category() ) {

				$cats = get_query_var( 'cat' );

			}

			/**
			 * If $atcat true and is single post
			 */
			if ( $atcat && is_single() ) {
				$cats = '';
				foreach ( get_the_category() as $catt ) {
					$cats .= $catt->term_id.' ';
				}
				$cats = str_replace( ' ', ',', trim( $cats ) );
			}

			/**
			 * If $attag true and in tag
			 */
			if ( $attag && is_tag() ) {
				$tags = get_query_var( 'tag_id' );
			}

			/**
			 * If $attag true and is single post
			 */
			if ( $attag && is_single() ) {
				$tags = '';
				$thetags = get_the_tags();
				if ( $thetags ) {
					foreach ( $thetags as $tagg ) {
						$tags .= $tagg->term_id . ' ';
					}
				}
				$tags = str_replace( ' ', ',', trim( $tags ) );
			}

			/**
			 * Excerpt more filter
			 * @var function
			 */
			$new_excerpt_more = create_function( '$more', 'return "...";' );
			add_filter( 'excerpt_more', $new_excerpt_more );

			// Excerpt length filter
			$new_excerpt_length = create_function( '$length', 'return ' . $excerpt_length . ';' );
			if ( $this->args['excerpt_length'] > 0 ) add_filter( 'excerpt_length', $new_excerpt_length );

			// if ( $class ) {
			// 	$before_widget = str_replace( 'class="', 'class="'. $class . ' ', $before_widget );
			// }

			/**
			 * Arguments for WP_Query
			 * @var array
			 */
			$args = array(
				'posts_per_page'	=> ( isset( $this->args['posts_number'] ) ? $this->args['posts_number'] : 10 ),
				'order'				=> $order,
				'orderby'			=> $orderby,
				// 'category__in'		=> $cats,
				// 'tag__in'			=> $tags,
				'post_type'			=> $post_types,
				'no_found_rows'		=> true,
				);

			if ( 'meta_value' === $orderby ) {
				$args['meta_key'] = $meta_key;
			}

			if ( ! empty( $sticky_query ) ) {
				$args[ key( $sticky_query ) ] = reset( $sticky_query );
			}

			$args = apply_filters( 'italystrap_wp_query_args', $args );

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
				include ITALYSTRAP_PLUGIN_PATH . '/widget/templates/standard.php';

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
