<?php
/**
 * Shortcode API
 *
 * Shortcode Base class
 *
 * @link www.italystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Shortcodes;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Shortcode
 */
abstract class Shortcode {

	protected $attr = array();

	protected $default = array();

	protected $content = '';

	public $shortcode_ui = array();

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	public function __construct( $argument = null ) {
		// Code...
	}
}
