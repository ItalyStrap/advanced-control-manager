<?php
/**
 * Class description
 */

namespace ItalyStrap\Widget;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

use \WP_Widget;
use ItalyStrap\Widgets\Widget as Old_Widget;
use ItalyStrap\Fields\Fields;
use ItalyStrap\Update\Sanitization;
use ItalyStrap\Update\Validation;

use InvalidArgumentException;

class Widget extends Old_Widget {


	protected function create_widget( array $args ) {
		_deprecated_function( __CLASS__, '2.5', 'ItalyStrap\\\Widgets\\\Widget' );
		parent::create_widget( $args );
	}

	/**
	 * Dispay the widget content
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param  array $instance The settings for the particular instance of the widget.
	 */
	public function widget_render( $args, $instance ){}
}
