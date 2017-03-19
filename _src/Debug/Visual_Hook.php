<?php
/**
 * Visual_Hook API
 *
 * Get a visual hook in front-end for all ItalyStrap hooks.
 *
 * @link www.italystrap.coom
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Debug;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Visual_Hook
 */
class Visual_Hook implements Subscriber_Interface {

	/**
	 * Returns an array of hooks that this subscriber wants to register with
	 * the WordPress plugin API.
	 *
	 * @hooked 'render' - 99
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {

		return array(
			// 'hook_name'				=> 'method_name',
			'after_setup_theme'					=> array(
				'function_to_add'					=> 'render',
				'priority'							=> 99,
			),
			'italystrap_custom_inline_style'	=> 'style',
		);
	}

	/**
	 * An array with all theme hooks
	 *
	 * @var array
	 */
	private $theme_hooks = array();

	/**
	 * 
	 */
	public function __construct( Hooked $hooked, array $options = array() ) {
		$this->hooked = $hooked;
		$this->options = $options;
		$this->theme_hooks = array(
			'italystrap_before',

			'italystrap_before_header',
			'italystrap_content_header',
			'italystrap_after_header',

			'italystrap_before_main',
			'italystrap_before_content',
			'italystrap_before_loop',
			'italystrap_loop',
			'italystrap_after_loop',
			'italystrap_after_content',
			'italystrap_after_main',

			'italystrap_before_while',
			'italystrap_before_entry',
			'italystrap_entry',
			'italystrap_after_entry',
			'italystrap_after_while',
			'italystrap_content_none',

			'italystrap_before_entry_content',
			'italystrap_entry_content',
			'italystrap_after_entry_content',

			'italystrap_before_sidebar_widget_area',
			'italystrap_sidebar',
			'italystrap_after_sidebar_widget_area',

			'italystrap_before_sidebar_secondary_widget_area',
			'italystrap_sidebar_secondary',
			'italystrap_after_sidebar_secondary_widget_area',

			'italystrap_before_footer',
			'italystrap_footer',
			'italystrap_after_footer',

			'italystrap_after',
		);
	}

	/**
	 * Get the style
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function style( $style = '' ) {

		$style .= '*::before, *::after{
			clear:both;
		}
		.filter-container{
			padding:10px 0 5px;
			width:100%;
			background-color:#aaa;
			border: 1px solid black;
			margin:10px 0;
			float:left;
		}
		.filter-name{
			color:white;
			font-weight:bold;
			text-align:center
		}';
	
		return $style;	
	}

	/**
	 * Get the HTML snippet
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function get_visual_hook() {

		printf(
			'<div class="filter-container"><p class="filter-name">%s</p>%s</div>',
			current_filter(),
			empty( $this->options['show_hooked_callable'] ) ? '' : $this->hooked->get_hooked_list( current_filter(), false )
		);
	}

	/**
	 * Display the hook in pages
	 *
	 * @uses current_filter() https://codex.wordpress.org/Function_Reference/current_filter
	 * @return string      Print an HTML tag with border and hooks name
	 */
	public function render() {
		if ( ! current_user_can( 'install_themes' ) ) {
			return;
		}
		foreach ( $this->theme_hooks as $value ) {
			add_action( $value, array( $this, 'get_visual_hook' ), 10 );
		}
	}
}
