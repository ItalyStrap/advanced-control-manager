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

			var_dump( $args );

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
			$args = shortcode_atts( require( ITALYSTRAP_PLUGIN_PATH . 'options/options-posts.php' ), $args, 'gallery' );

			$args = apply_filters( 'italystrap_query_posts_args', $args );

			return $args;

		}

		public function output() {

			foreach ( $this->args as $key => $value)
				$$key = $value;

			// var_dump( $this->args );

			$class = $instance['class'];
			$number = empty( $instance['number'] ) ? -1 : $instance['number'];
			$types = empty( $instance['types'] ) ? 'any' : explode( ',', $instance['types'] );
			$cats = empty( $instance['cats'] ) ? '' : explode( ',', $instance['cats'] );
			$tags = empty( $instance['tags'] ) ? '' : explode( ',', $instance['tags'] );
			$atcat = $instance['atcat'] ? true : false;
			$thumb_size = $instance['thumb_size'];
			$attag = $instance['attag'] ? true : false;
			$excerpt_length = $instance['excerpt_length'];
			$excerpt_readmore = $instance['excerpt_readmore'];
			$sticky = $instance['sticky'];
			$order = $instance['order'];
			$orderby = $instance['orderby'];
			$meta_key = $instance['meta_key'];
			$custom_fields = $instance['custom_fields'];

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
			if ( $instance['excerpt_length'] > 0 ) add_filter( 'excerpt_length', $new_excerpt_length );

			if ( $class ) {
				$before_widget = str_replace( 'class="', 'class="'. $class . ' ', $before_widget );
			}

			echo $before_widget;

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			if ( $title && $instance['title_link'] )
				echo $before_title . apply_filters( 'italystrap_widget_title_link', '<a href="' . esc_html( $instance['title_link'] ) . '">' . esc_attr( $title ) . '</a>', $instance['title_link'], $title ) . $after_title;
			elseif ( $title && ! $instance['title_link'] )
				echo $before_title . esc_attr( $title ) . $after_title;

			/**
			 * Arguments for WP_Query
			 * @var array
			 */
			$args = array(
				'posts_per_page'	=> $number,
				'order'				=> $order,
				'orderby'			=> $orderby,
				'category__in'		=> $cats,
				'tag__in'			=> $tags,
				'post_type'			=> $types,
				'no_found_rows'		=> true,
				);

			if ( 'meta_value' === $orderby ) {
				$args['meta_key'] = $meta_key;
			}

			if ( ! empty( $sticky_query ) ) {
				$args[ key( $sticky_query ) ] = reset( $sticky_query );
			}

			$args = apply_filters( 'italystrap_wp_query_args', $args, $instance, $this->id_base );

			$widget_post_query = new WP_Query( $args );

			if ( 'custom' === $instance['template'] ) {

				$custom_template_path = apply_filters( 'italystrap_custom_template_path',  '/templates/' . $instance['template_custom'] . '.php', $instance, $this->id_base );

				if ( locate_template( $custom_template_path ) ) {

					include get_stylesheet_directory() . $custom_template_path;

				} else {

					include 'templates/standard.php';

				}
			} elseif ( 'standard' === $instance['template'] ) {

				include 'templates/standard.php';

			} else {

				include 'templates/legacy.php';

			}

		}
	}

}
