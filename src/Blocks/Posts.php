<?php
/**
 * Posts Block API
 *
 * Block for Posts Class
 *
 * @link [URL]
 * @since [x.x.x (if available)]
 *
 * @package [Plugin/Theme/Etc]
 */

namespace ItalyStrap\Blocks;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

use \ItalyStrap\Query\Posts as Posts_Base;

/**
 * Posts Block API
 */
class Posts extends Block {

	/**
	 * [$var description]
	 *
	 * @var null
	 */
	private $var = null;

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( Posts_Base $post ) {
		$this->query_posts = $post;
	}

	/**
	 * Render the output
	 *
	 * @param  array  $attr    The attribute for the shortcode.
	 * @param  array  $content The content for the shortcode.
	 *
	 * @return string          The output of the shortcode.
	 */
	public function render( array $attributes ) {

		return 'Block posts';

// ddd( $attributes );
		$this->query_posts->get_widget_args( $attributes );

		return $this->query_posts->output();
	}

}
