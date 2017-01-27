<?php
/**
 * Widget_Factory API
 *
 * Instantiate the wdgets.
 *
 * @link www.italystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Widget;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Widget_Factory
 */
class Widget_Factory implements Subscriber_Interface {

	/**
	 * Returns an array of hooks that this subscriber wants to register with
	 * the WordPress plugin API.
	 *
	 * @hooked 'widgets_init' - 10
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {

		return array(
			// 'hook_name'				=> 'method_name',
			'widgets_init'	=> array(
				'function_to_add'	=> 'widgets_init',
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
	 * Fire the construct
	 */
	public function __construct( array $options = array() ) {
		$this->options = $options;
	}

	/**
	 * Add action to widget_init
	 * Initialize widget
	 */
	public function widgets_init() {

		$widget_list = array(
			'vcardwidget'				=> 'Vcard_Widget', // Deprecated
			'post_widget'				=> 'Widget_Posts2', // Deprecated
			'media_carousel_widget'		=> 'Carousel',
			'widget_posts'				=> 'Posts',
			'widget_vcard'				=> 'VCard', // New
			'widget_image'				=> 'Image', // New
			'widget_facebook_page'		=> 'Facebook_Page', // New
			'widget_breadcrumbs'		=> 'Breadcrumbs', // Beta
			'widget_taxonomies_posts'	=> 'Taxonomies_Posts', // Beta
		);

		foreach ( (array) $widget_list as $key => $value ) {
			if ( ! empty( $this->options[ $key ] ) ) {
				\register_widget( 'ItalyStrap\\Widget\\' . $value );
			}
		}
	}
}
