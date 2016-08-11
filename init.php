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
 * Instantiate Init Class
 *
 * @var Init
 */
// $customizer_manager = $injector->make( 'ItalyStrap\Admin\Customizer_Manager' );
// add_action( 'customize_register', array( $customizer_manager, 'register_init' ), 11 );

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
if ( isset( $options['render_html_in_widget_title'] ) ) {
	add_filter( 'widget_title', 'ItalyStrap\Core\render_html_in_title_output' );
}

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
 * Get metaboxes value
 */
add_action( 'wp', array( $post_meta, 'add_post_type_custom_css' ) );
add_filter( 'body_class', array( $post_meta, 'body_class' ) );
add_filter( 'post_class', array( $post_meta, 'post_class' ) );


if ( isset( $options['web_font_loading'] ) ) {
	/**
	 * Load the Web_Font_Loading classes
	 *
	 * @var Web_Font_loading
	 */
	$web_font_loading = $injector->make( 'ItalyStrap\Core\Web_Font_loading' );
	add_action( 'wp_footer', array( $web_font_loading, 'lazy_load_fonts'), 9999 );
}

if ( isset( $options['widget_attributes'] ) ) {
	/**
	 * Init the Widget_Attributes
	 *
	 * @var Widget_Attributes
	 */
	$injector->define( 'ItalyStrap\Widget\Widget_Attributes', ['fields_type' => 'ItalyStrap\Admin\Fields'] );
	$widget_attributes = $injector->make( 'ItalyStrap\Widget\Widget_Attributes' );
	// add_action( 'widgets_init', array( 'Widget_Attributes', 'setup' ) );
	add_filter( 'dynamic_sidebar_params', array( $widget_attributes, 'insert_attributes' ) );
}

/**
 * Option for killing the emojis
 */
if ( isset( $options['kill-emojis'] ) ) {
	add_action( 'init', 'ItalyStrap\Core\kill_emojis' );
}

/**
 * General
 * Allow shortcode in widget text.
 */
if ( isset( $options['do_shortcode_widget_text'] ) ) {
	add_filter( 'widget_text', 'do_shortcode' );
}

/**
 * Set CSS from admin option Script
 */
ItalyStrapGlobalsCss::set( isset( $options['custom_css'] ) ? $options['custom_css'] : '' );

/**
 * Print inline css in header
 */
add_action( 'wp_head', array( $init, 'print_inline_css_in_header' ), 999999 );

/**
 * Print inline script in footer
 * Load after all and before shotdown hook
 */
add_action( 'wp_print_footer_scripts', array( $init, 'print_inline_script_in_footer' ), 999 );

/**
 * This are some functionality in beta version.
 * If you want to use thoose functionality you have to define ITALYSTRAP_BETA
 * constant in your wp-config.php first.
 * Also remember that you do it at own risk.
 * If you are not shure don't do it ;-)
 */
if ( defined( 'ITALYSTRAP_BETA' ) ) {

	/**
	 * [$generate_analytics description]
	 *
	 * @var Generate_Analytics
	 */
	$generate_analytics = $injector->make( 'ItalyStrap\Core\Generate_Analytics' );
	add_action( 'wp_footer', array( $generate_analytics, 'render_analytics' ), 99999 );

	if ( isset( $options['category_posts_shortcode'] ) ) {
		$shortcode_docs = new Shortcode_Docs;
		add_shortcode( 'docs', array( $shortcode_docs, 'docs' ) );
	}

	if ( isset( $options['category_posts_widget'] ) ) {
		$shortcode_docs = new Shortcode_Docs;
		add_shortcode( 'docs', array( $shortcode_docs, 'docs' ) );
	}

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


	/**
	 * Widget Logic Functionality for admin
	 *
	 * @var Widget_Logic_Admin
	 */
	// $widget_logic_admin = $injector->make( 'ItalyStrap\Widget\Widget_Logic' );

	/**
	 * Widget changes submitted by ajax method.
	 */
	// add_filter( 'widget_update_callback', array( $widget_logic_admin, 'widget_update_callback' ), 10, 4 );
	/**
	 * Before any HTML output save widget changes and add controls to each widget on the widget admin page.
	 */
	// add_action( 'sidebar_admin_setup', array( $widget_logic_admin, 'expand_control' ) );
	/**
	 * Add Widget Logic specific options on the widget admin page.
	 */
	// add_action( 'sidebar_admin_page', array( $widget_logic_admin, 'options_control' ) );

}
