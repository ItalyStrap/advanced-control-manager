<?php
/**
 * Shortcode_Factory API
 *
 * Instantiate the Shortcodes.
 *
 * @link www.italystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Shortcode;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Shortcode_Factory
 */
class Shortcode_Factory implements Subscriber_Interface {

	/**
	 * Returns an array of hooks that this subscriber wants to register with
	 * the WordPress plugin API.
	 *
	 * @hooked 'init' - 10
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {

		return array(
			// 'hook_name'				=> 'method_name',
			'init'	=> array(
				'function_to_add'	=> 'register',
				'priority'			=> 10,
			),
		);
	}

	/**
	 * The plugin's options
	 *
	 * @var string
	 */
	private $options = '';

	/**
	 * List of all widget classes name.
	 *
	 * @var array
	 */
	private $shortcodes_list = array();

	/**
	 * Fire the construct
	 */
	public function __construct( array $options = array() ) {
		$this->options = $options;

		$this->shortcodes_list = array(
			'shortcode_row'			=> 'Row',
			'shortcode_column'		=> 'Column',
		);
	}

	/**
	 * Add action to widget_init
	 * Initialize widget
	 */
	public function register() {

		foreach ( (array) $this->shortcodes_list as $shortcode_option => $shortcode_class ) {
			$shortcode_name = lcfirst( $shortcode_class );
			if ( ! empty( $this->options[ $shortcode_option ] ) ) {
				$shortcode_class = 'ItalyStrap\\Shortcode\\' . $shortcode_class;
				$$shortcode_name =  new $shortcode_class;
				add_shortcode( $shortcode_name, array( $$shortcode_name, 'render' ) );
				if ( function_exists( '\shortcode_ui_register_for_shortcode' ) ) {
					\shortcode_ui_register_for_shortcode( $shortcode_name, $$shortcode_name->shortcode_ui );
				}
			}
		}
	}
}
