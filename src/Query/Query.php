<?php
/**
 * Abstract class for custom query
 *
 * This is the abstract class for the custom query used in widget and shortcode to display post type
 *
 * @since 2.0.0
 *
 * @version 1.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Query;

use \WP_Query;

/**
 * Query Class for widget and shortcode
 */
abstract class Query implements Query_Interface {

	/**
	 * WordPress query object.
	 *
	 * @var WP_Query
	 */
	protected $query;

	/**
	 * WordPress query object.
	 *
	 * @var object
	 */
	protected $args;

	/**
	 * WordPress global $post
	 *
	 * @var object
	 */
	protected $post;

	/**
	 * Set an array with post to be escluded from loop
	 *
	 * @var array
	 */
	protected $posts_to_exclude = array();

	/**
	 * Declare this variable static and call it only one time
	 *
	 * @var array
	 */
	protected static $sticky_posts;

	/**
	 * The context of this instance works
	 *
	 * @var string
	 */
	protected $context;

	/**
	 * Constructor.
	 *
	 * @param WP_Query $query The standard query of WordPress.
	 */
	function __construct( WP_Query $query, $context = null ) {

		$this->query = $query;

		global $post;
		$this->post = $post;

		if ( ! isset( self::$sticky_posts ) ) {
			self::$sticky_posts = get_option( 'sticky_posts' );
		}

		if ( ! isset( $context ) ) {
			$context = 'main';
		}

		if ( ! is_string( $context ) ) {
			throw new InvalidArgumentException( __( 'Context name must be a string', 'italystrap' ) );
		}

		$this->context = $context;

	}

	/**
	 * Initialize the repository.
	 *
	 * @uses PHP 5.3
	 *
	 * @return self
	 */
	public static function init( $context = null ) {

		return new self( new WP_Query(), $context );

	}

	/**
	 * Get the global post
	 *
	 * @return object Return the global objesct post
	 */
	public function get_global_post() {
		return $this->post;
	}

	/**
	 * Get arguments from widget attributes
	 *
	 * @param  array $args Arguments from widget.
	 */
	public function get_widget_args( $args ) {

		$this->args = $this->get_attributes( $args );

	}

	/**
	 * Get arguments from shortcode attributes
	 *
	 * @param  array $args Arguments from shortcode.
	 */
	public function get_shortcode_args( $args ) {

		$this->args = $this->get_attributes( $args );

	}

	/**
	 * Get the correct path for template parts
	 *
	 * @return string Return the path
	 */
	public function get_template_part() {

		$template_path = ITALYSTRAP_PLUGIN_PATH . '/templates/legacy.php';

		if ( 'custom' === $this->args['template'] ) {

			$custom_template_path = '/templates/' . $this->args['template_custom'] . '.php';

			if ( locate_template( $custom_template_path ) ) {

				$template_path = STYLESHEETPATH . $custom_template_path;

			} else {

				$template_path = ITALYSTRAP_PLUGIN_PATH . '/templates/standard.php';

			}
		} elseif ( 'standard' === $this->args['template'] ) {

			$template_path = ITALYSTRAP_PLUGIN_PATH . '/templates/standard.php';

		} else {

			$template_path = ITALYSTRAP_PLUGIN_PATH . '/templates/legacy.php';

		}

		return apply_filters( 'italystrap_query_posts_template_path', $template_path, $this->args );

	}

	/**
	 * Output the query result
	 *
	 * @return string The HTML result
	 */
	abstract public function output();

	/**
	 * Get posts id by views.
	 * This function is forked from Jetpack.
	 * It functions only if jetpack is actived.
	 *
	 * @param  array      $args   The widget arguments.
	 *
	 * @return array/null         Return an array of posts id
	 *                            or null if none are found.
	 */
	protected function get_posts_ids_by_views( $args ) {

		if ( ! function_exists( 'stats_get_csv' ) ) {
			return null;
		}

		/**
		 * Filter the number of days used to calculate Top Posts for the Top Posts widget.
		 * We do not recommend accessing more than 10 days of results at one.
		 * When more than 10 days of results are accessed at once, results should be cached via the WordPress transients API.
		 * Querying for -1 days will give results for an infinite number of days.
		 *
		 * @module widgets
		 *
		 * @since 3.9.3
		 *
		 * @param int 2 Number of days. Default is 2.
		 * @param array $args The widget arguments.
		 */
		$days = (int) apply_filters( 'italystrap_top_posts_days', 2, $args );

		/** Handling situations where the number of days makes no sense - allows for unlimited days where $days = -1 */
		if ( 0 === $days || false === $days ) {
			$days = 2;
		}

		$post_view_posts = stats_get_csv( 'postviews', array( 'days' => absint( $days ), 'limit' => 11 ) );

		if ( ! $post_view_posts ) {
			return array();
		}

		$post_view_ids = array_filter( wp_list_pluck( $post_view_posts, 'post_id' ) );

		if ( ! $post_view_ids ) {
			return null;
		}

		return (array) array_map( 'absint', $post_view_ids );
	}
}
