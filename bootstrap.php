<?php
/**
 * Init API: Init Class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Core;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Initialize the IOC
 *
 * @var Auryn\Injector
 */
$injector = new \Auryn\Injector;

$args = require( ITALYSTRAP_PLUGIN_PATH . 'admin/config/plugin.php' );

$injector->defineParam( 'args', $args );

/**
 * Get the plugin options
 *
 * @var array
 */
$options = (array) get_option( $args['options_name'] );

$options = wp_parse_args( $options, \ItalyStrap\Core\get_default_from_config( require( ITALYSTRAP_PLUGIN_PATH . 'admin/config/options.php' ) ) );

/**
 * Define options parmeter
 */
$injector->defineParam( 'options', $options );

/**
 * Get the theme mods
 *
 * @var array
 */
$theme_mods = (array) get_theme_mods();

/**
 * Define theme_mods parmeter
 */
$injector->defineParam( 'theme_mods', $theme_mods );

$config = $injector->make( '\ItalyStrap\Config\Config' );

/**
 * The new events manager in ALPHA version.
 *
 * @var Event_Manager
 */
$event_manager = $injector->make( '\ItalyStrap\Event\Manager' );
$events_manager = $event_manager; // Deprecated $events_manager.


if ( defined( 'ITALYSTRAP_BETA' ) ) {
	/**
	 * Instantiate Customizer_Manager Class
	 * Questa deve essere eseguita sia in admin che in front-end
	 *
	 * @var Customizer_Manager
	 */
	$customizer_manager = $injector->make( 'ItalyStrap\Customizer\Customizer_Register' );
	add_action( 'customize_register', array( $customizer_manager, 'register' ), 11 );
}

/**
 * Register widget
 */
$event_manager->add_subscriber( $injector->make( '\ItalyStrap\Widgets\Widget_Factory' ) );

$shortcode_factory = $injector->make( '\ItalyStrap\Shortcodes\Shortcode_Factory' );
$event_manager->add_subscriber( $shortcode_factory );
// $shortcode_factory->register();
// add_action( 'wp_loaded', array( $shortcode_factory, 'register' ) );
// add_action( 'plugins_loaded', array( $shortcode_factory, 'register' ) );

if ( ! empty( $options['widget_areas'] ) ) {
	/**
	 * Instantiate Widget Areas Class
	 * Questa deve essere eseguita sia in admin che in front-end
	 *
	 * @var Areas
	 */
	$widget_areas = $injector->make( 'ItalyStrap\Widgets\Areas\Areas' );
	// $widget_areas->register_sidebars();
	add_action( 'widgets_init', array( $widget_areas, 'register_sidebars' ) );
	add_action( 'init', array( $widget_areas, 'register_post_type' ), 20 );
	add_action( 'save_post', array( $widget_areas, 'add_sidebar' ), 10, 3 );
	add_action( 'edit_post', array( $widget_areas, 'add_sidebar' ), 10, 2 );
	add_action( 'delete_post', array( $widget_areas, 'delete_sidebar' ) );
	add_action( 'widgets_admin_page', array( $widget_areas, 'print_add_button' ) );

	// delete_option( 'italystrap_widget_area' );
	// d( get_option( 'italystrap_widget_area' ) );
}

// if ( ! empty( $options['widget_visibility'] ) ) {
// 	add_action( 'init', array( 'ItalyStrap\Widgets\Visibility\Visibility', 'init' ) );
// 	add_action( 'admin_init', array( 'ItalyStrap\Widgets\Visibility\Visibility_Admin', 'init' ) );
// }

// if ( ! empty( $options['shortcode_widget'] ) ) {
	/**
	 * Instantiate Widget to Shortcode Class
	 * Questa deve essere eseguita sia in admin che in front-end
	 *
	 * @var ItalyStrap\Shortcodes\Widget
	 */
	// $shortcode_widget = $injector->make( 'ItalyStrap\Shortcodes\Widget' );

	// add_shortcode( 'widget', array( $shortcode_widget, 'shortcode' ) );

	// add_action( 'widgets_init', array( $shortcode_widget, 'arbitrary_sidebar' ), 20 );
	// add_action( 'in_widget_form', array( $shortcode_widget, 'in_widget_form' ), 10, 3 );
	// add_filter( 'mce_external_plugins', array( $shortcode_widget, 'mce_external_plugins' ) );
	// add_filter( 'mce_buttons', array( $shortcode_widget, 'mce_buttons' ) );
	// add_action( 'admin_enqueue_scripts', array( $shortcode_widget, 'editor_parameters' ) );
	// add_action( 'wp_enqueue_scripts', array( $shortcode_widget, 'editor_parameters' ) );
// }

/**
 * This are some functionality in beta version.
 * If you want to use thoose functionality you have to define ITALYSTRAP_BETA
 * constant in your wp-config.php first.
 * Also remember that you do it at own risk.
 * If you are not shure don't do it ;-)
 */
if ( defined( 'ITALYSTRAP_BETA' ) ) {

	/*************
	 * GLOBAL INIT
	 ************/

	/**
	 * WooCommerce enqueue style
	 *
	 * @link https://docs.woothemes.com/document/css-structure/
	 */
	function dequeue_unused_styles( $enqueue_styles ) {

		if ( is_cart() || is_checkout() || is_account_page() ) {

			return $enqueue_styles;
		} else {

			return false;
		}
	}
	// add_filter( 'woocommerce_enqueue_styles', __NAMESPACE__ . '\dequeue_unused_styles' );

	// Or just remove them all in one line.
	// add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	// 
	// $search_box_item = new Search_Box_Item();


	/**
	 * @link http://www.wpbeginner.com/plugins/add-excerpts-to-your-pages-in-wordpress/
	 * @link https://codex.wordpress.org/Function_Reference/add_post_type_support
	 */
	// add_action( 'init', __NAMESPACE__ .'\my_add_excerpts_to_pages' );
	// function my_add_excerpts_to_pages() {
	// 	add_post_type_support( 'page', 'excerpt' );
	// }


	/*************
	 * ADMIN INIT
	 ************/

	/**
	 * FRONTEND INIT
	 */

	if ( isset( $options['category_posts_shortcode'] ) ) {
		$shortcode_docs = new Shortcode_Docs;
		add_shortcode( 'docs', array( $shortcode_docs, 'docs' ) );
	}

	if ( isset( $options['category_posts_widget'] ) ) {
		$shortcode_docs = new Shortcode_Docs;
		add_shortcode( 'docs', array( $shortcode_docs, 'docs' ) );
	}

	// $facebook_page = new \ItalyStrap\Core\Facebook\Page();
	// add_action( 'wp_footer', array( $facebook_page, 'script_2' ), 99 );
	// add_action( 'italystrap_sidebar', array( $facebook_page, 'output' ) );

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	// function add_to_top() {

	// 	global $injector;

	// 	// $category_posts = new Category_Posts;
	// 	$category_posts = $injector->make( 'ItalyStrap\Core\Category_Posts' );
	// 	echo $category_posts->render();

	// 	return null;
	
	// }
	// add_action( 'italystrap_before_content', __NAMESPACE__ . '\add_to_top' );
}
