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

namespace ItalyStrap\Core;
use \WP_Query;

/**
 * Query Class for widget and shortcode
 */
abstract class Query implements I_Query {

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
}
