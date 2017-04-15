<?php
/**
 * Widget Areas API: Widget Areas class
 *
 * @forked from woosidebars
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets\Areas;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

use ItalyStrap\Core;
use ItalyStrap\Asset\Inline_Style;
use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Update\Update;

/**
 * Widget Areas Class
 */
class Areas_Base {

	/**
	 * [$var description]
	 *
	 * @var null
	 */
	private $options = null;

	/**
	 * Array with widget areas
	 *
	 * @var array
	 */
	private $widget_areas = array();

	/**
	 * Update object for saving data to DB
	 *
	 * @var Update
	 */
	protected $update = null;

	/**
	 * Instance of CSS Generator
	 *
	 * @var CSS_Generator
	 */
	protected $css = null;

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( array $options = array(), Update $update, CSS_Generator $css ) {
		// $this->sidebars = $options;
		$this->sidebars = get_option( 'italystrap_widget_area' );
		// delete_option( 'italystrap_widget_area' );
		// d( get_option( 'italystrap_widget_area' ) );
		$this->update = $update;
		$this->css = $css;

		$this->prefix = 'italystrap';

		$this->_prefix = '_' . $this->prefix;

		$default = require( __DIR__ . DIRECTORY_SEPARATOR . 'config.php' );

		$this->default = $default['fields'];
	}

	/**
	 * Generate HTML attributes
	 * Helper method for the get_attr();
	 * Build list of attributes into a string and apply contextual filter on string.
	 *
	 * The contextual filter is of the form `italystrap_attr_{context}_output`.
	 *
	 * @since 2.5.0
	 *
	 * @see In general-function on the plugin.
	 *
	 * @param  string $context    The context, to build filter name.
	 * @param  array  $attributes Optional. Extra attributes to merge with defaults.
	 * @param  bool   $echo       True for echoing or false for returning the value.
	 *                            Default false.
	 * @param  null   $args       Optional. Extra arguments in case is needed.
	 *
	 * @return string String of HTML attributes and values.
	 */
	public function attr( $context, array $attr = array(), $echo = false, $args = null ) {
	
		Core\get_attr( $context, $attr, $echo, $args );
	}

	/**
	 * Get Sidebar ID
	 *
	 * @param  int    $id The numeric ID of the single sidebar registered.
	 *
	 * @return string     The ID of the sidebar to load on front-end
	 */
	protected function get_the_id( $id ) {
		return $this->sidebars[ $id ]['value']['id'];
	}

	/**
	 * register_post_type function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_post_type() {
		// Allow only users who can adjust the theme to view the WooSidebars admin.
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$page = 'themes.php';

		$singular = __( 'Widget Area', 'italystrap' );
		$plural = __( 'Widget Areas', 'italystrap' );
		$rewrite = array( 'slug' => 'sidebars' );
		$supports = array(
			'title',
			'excerpt',
			// 'page-attributes',
		);

		if ( '' === $rewrite ) {
			$rewrite = 'sidebar';
		}

		$labels = array(
			'name'					=> _x( $plural, 'post type general name', 'italystrap' ),
			'singular_name'			=> _x( $singular, 'post type singular name', 'italystrap' ),
			'add_new'				=> _x( 'Add New', $singular ),
			'add_new_item'			=> sprintf(
				__( 'Add New %s', 'italystrap' ),
				$singular
			),
			'edit_item'				=> sprintf(
				__( 'Edit %s', 'italystrap' ),
				$singular
			),
			'new_item'				=> sprintf(
				__( 'New %s', 'italystrap' ),
				$singular
			),
			'all_items'				=> $plural,
			'view_item'				=> sprintf(
				__( 'View %s', 'italystrap' ),
				$singular
			),
			'search_items'			=> sprintf(
				__( 'Search %a', 'italystrap' ),
				$plural
			),
			'not_found'				=> sprintf(
				__( 'No %s Found', 'italystrap' ),
				$plural
			),
			'not_found_in_trash'	=> sprintf(
				__( 'No %s Found In Trash', 'italystrap' ),
				$plural
			),
			'parent_item_colon'		=> '',
			'menu_name'				=> $plural

		);
		$args = array(
			'labels'				=> $labels,
			'public'				=> false,
			'publicly_queryable'	=> true,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> false,
			'show_in_admin_bar'     => true,
			'show_in_menu'			=> $page,
			'show_in_rest'			=> false,
			'query_var'				=> true,
			'rewrite'				=> $rewrite,
			// 'capability_type'		=> 'post',
			'has_archive'			=> 'sidebars',
			'hierarchical'			=> false,
			'menu_position'			=> null,
			'supports'				=> $supports
		);
		register_post_type( 'sidebar', $args );
	} // End register_post_type()

	/**
	 * Print add button in widgets.php.
	 *
	 * @hooked 'widgets_admin_page' - 10
	 */
	public function print_add_button() {
	
		printf(
			'<div><a %s>%s</a></div>',
			Core\get_attr(
				'widget_add_sidebar',
				array(
					'href'	=> 'post-new.php?post_type=sidebar',
					'class'	=> 'button button-primary sidebar-chooser-add',
					'style'	=> 'margin:1em 0;',
				),
				false
			),
			__( 'Add new widgets area', 'italystrap' )
		);
	}
}
