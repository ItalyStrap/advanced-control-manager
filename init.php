<?php
/**
 * ItalyStrap init file
 *
 * Init the plugin front-end functionality
 *
 * @link www.italystrap.com
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

if ( is_admin() ) {
	return;
}

$injector = new \Auryn\Injector;

/**
 * Get the plugin options
 *
 * @var array
 */
$options = (array) get_option( 'italystrap_settings' );

/**
 * Define options parmeter
 */
$injector->defineParam( 'options', $options );

/**
 * Option for killing the emojis
 */
if ( isset( $options['kill-emojis'] ) ) {
	add_action( 'init', 'ItalyStrap\Core\kill_emojis' );
}

/**
 * Instantiate Init Class
 *
 * @var Init
 */
$init = $injector->make( 'ItalyStrap\Core\Init' );

/**
 * Adjust priority to make sure this runs
 */
add_action( 'init', array( $init, 'on_load' ), 100 );

if ( isset( $options['media_carousel_shortcode'] ) ) {
	$init->add_carousel_to_gallery_shortcode();
}

/**
 * Attivate LazyLoad
 */
if ( isset( $options['lazyload'] ) ) {
	/**
	 * Instantiate Post_Meta Class
	 *
	 * @var Post_Meta
	 */
	$lazy_load_image = $injector->make( 'ItalyStrap\Core\Lazy_Load_Image' );
	$lazy_load_image::init();
	// Lazy_Load_Image::init();
}

/**
 * Register widget
 */
add_action( 'widgets_init', array( $init, 'widgets_init' ) );

/**
 * This filter render HTML in widget title parsing {{}}
 */
add_filter( 'widget_title', 'ItalyStrap\Core\render_html_in_title_output' );

/**
 * Instantiate MobileDetect Class
 *
 * @var MobileDetect
 */
// $mobile_detect = $injector->make( 'Detection\MobileDetect' );
/**
 * This filter is applyed in class-carousel in get_img_size_attr() method
 * @todo Create an injection for Detection\MobileDetect in carousel class
 */
add_filter( 'mobile_detect', 'ItalyStrap\Core\new_mobile_detect' );

/**
 * Instantiate Post_Meta Class
 *
 * @var Post_Meta
 */
$post_meta = $injector->make( 'ItalyStrap\Core\Post_Meta' );

/**
 * Get metaboxex value
 */
add_action( 'wp', array( $post_meta, 'add_post_type_custom_script' ) );
add_filter( 'body_class', array( $post_meta, 'body_class' ) );
add_filter( 'post_class', array( $post_meta, 'body_class' ) );

/**
 * [$generate_analytics description]
 *
 * @var Web_Font_loading
 */
$web_font_loading = $injector->make( 'ItalyStrap\Core\Web_Font_loading' );
add_action( 'wp_footer', array( $web_font_loading, 'lazy_load_fonts'), 9999 );

/**
 * Set JavaScript from admin option Script
 */
ItalyStrapGlobals::set( isset( $options['custom_js'] ) ? $options['custom_js'] : '' );

/**
 * Set CSS from admin option Script
 */
ItalyStrapGlobalsCss::set( isset( $options['custom_css'] ) ? $options['custom_css'] : '' );

/**
 * Print inline css in header
 */
add_action( 'wp_head', array( $init, 'print_inline_css_in_header' ), 999999 );

/**
 * [$generate_analytics description]
 *
 * @var Generate_Analytics
 */
$generate_analytics = $injector->make( 'ItalyStrap\Core\Generate_Analytics' );

add_action( 'wp_footer', array( $generate_analytics, 'render_analytics' ), 99999 );

/**
 * Print inline script in footer
 * Load after all and before shotdown hook
 */
add_action( 'wp_print_footer_scripts', array( $init, 'print_inline_script_in_footer' ), 999 );
