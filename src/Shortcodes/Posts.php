<?php
/**
 * Row API
 *
 * Row Base class
 *
 * @link www.italystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Shortcodes;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

use \ItalyStrap\Query\Posts as Posts_Base;

/**
 * Posts
 */
class Posts extends Shortcode {

	/**
	 * Instance of ItalyStrap\Query\Posts
	 *
	 * @var ItalyStrap\Query\Posts
	 */
	private $query_posts = null;

	static $instance = 0;

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	public function __construct( Posts_Base $post ) {

		$this->query_posts = $post;

		self::$instance++;

		$shortcode_ui_default = array(

			/** This label will appear in user interface */
			'label'			=> '',

			/** This is the actual attr used in the code used for shortcode */
			'attr'			=> '',

			/** Define input type. Supported types are text, checkbox, textarea, radio, select, email, url, number, and date. */
			'type'			=> 'text',

			/** Add a helpful description for users */
			'description'	=> '',
		);

		$this->config = require( ITALYSTRAP_PLUGIN_PATH . 'config/posts.php' );

		$this->shortcode_ui = array(

			/** Label for your shortcode user interface. This part is required. */
			'label'			=> __( 'Posts', 'italystrap' ),

			/** Icon or an image attachment for shortcode. Optional. src or dashicons-$icon.  */
			'listItemImage'	=> 'dashicons-flag',

			/** You can select which post types will show shortcode UI */
			// 'post_type'		=> array( 'post', 'page' ),

			/** Shortcode Attributes */
			'attrs'			=> array(),
		);

		$new_attr = array();

		foreach ( $this->config as $key => $value ) {

			if ( isset( $value['show_on'] ) && ! $value['show_on'] ) {
				continue;
			}

			// $default = isset( $value['default'] ) && '0' !== $value['default'] ? $value['default'] : '';
			$default = isset( $value['default'] ) ? $value['default'] : '';

			$new_attr = array(
				'label'			=> $value['name'],
				'description'	=> $value['desc'],
				'attr'			=> $key,
				'type'			=> $value['type'],
				'value'			=> $default,
			);

			if ( isset( $value['options'] ) ) {
				$new_attr['options'] = $value['options'];
			}

			$this->shortcode_ui['attrs'][] = $new_attr;

			$this->default[ $key ] = $default;
		}
	}

	/**
	 * Dispay the widget content
	 *
	 * @param  array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param  array $instance The settings for the particular instance of the widget.
	 * @return string           Return the output
	 */
	public function shortcode_render( array $attr = array(), $content = "" ) {

		$this->query_posts->get_widget_args( $attr );

		return $this->query_posts->output();
	}

	/**
	 * Render the output
	 *
	 * @param  array  $attr    The attribute for the shortcode.
	 * @param  array  $content The content for the shortcode.
	 *
	 * @return string          The output of the shortcode.
	 */
	public function render( $attr = array(), $content = "" ) {

		$attr = shortcode_atts( $this->default, $attr, 'posts' );

		return $this->shortcode_render( $attr, $content );

		/**
		 * Filters the default posts shortcode output.
		 *
		 * If the filtered output isn't empty, it will be used instead of generating
		 * the default posts template.
		 *
		 * @since
		 *
		 * @see ItalyStrap\Shortcode\Shortcode()
		 *
		 * @param string $html     The posts output. Default empty.
		 * @param array  $attr     Attributes of the posts shortcode.
		 * @param int    $instance Unique numeric ID of this posts shortcode instance.
		 */
		// $html = apply_filters( 'italystrap_shortcode_posts', '', $this->attr, self::$instance );
		// if ( '' !== $html ) {
		// 	return $html;
		// }

		$this->query_posts->get_widget_args( $this->attr );

		return $this->query_posts->output();
	}
}
