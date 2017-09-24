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

use ItalyStrap\Asset\Inline_Style;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

if ( is_admin() ) {
	return;
}

$autoload_subscribers = array_merge( $autoload_subscribers, array(
		// 'option_name'			=> 'Class\Name',
		'lazyload_video'			=> 'ItalyStrap\Lazyload\Video',
		'lazyload'					=> 'ItalyStrap\Lazyload\Image',
		'web_font_loading'			=> 'ItalyStrap\Lazyload\Fonts', // 404
		'activate_custom_css'		=> 'ItalyStrap\Asset\Custom_Css',
		'activate_analytics'		=> 'ItalyStrap\Google\Analytics',
		'google_tag_manager_id'		=> 'ItalyStrap\Google\Tag_Manager',
		'activate_social_share'		=> 'ItalyStrap\Social\Share',
		'show_theme_hooks'			=> 'ItalyStrap\Debug\Visual_Hook',
		'media_carousel_shortcode'	=> 'ItalyStrap\Shortcodes\Gallery',
		'activate_excerpt_more_mods'=> 'ItalyStrap\Excerpt\Excerpt',
		'ItalyStrap\Asset\Inline_Asset_Factory',
	)
);

/**
 * Se ci sono più classi da instanziare con la stessa option valutare se
 * gestire anche un array oltre che stringa, per esempio:
 *
 * [
 *     'activate_analytics'		=> [
 *         'ItalyStrap\Google\Analytics',
 *         'ItalyStrap\Google\Tag_Manager',
 *     ],
 * ]
 *
 * è possibile anche definire i parametri della classe stessa in un array:
 *
 * ItalyStrap\Dir\Some_Class	=> [
 * 		:arg	=> $some_value
 * ],
 */

// foreach ( $autoload_subscribers as $option_name => $concrete ) {
// 	if ( empty( $options[ $option_name ] ) ) {
// 		continue;
// 	}
// 	$event_manager->add_subscriber( $injector->make( $concrete ) );
// }

if ( ! empty( $options['widget_visibility'] ) ) {
	add_action( 'init', array( 'ItalyStrap\Widgets\Visibility\Visibility', 'init' ) );
}

/**
 * This filter render HTML in widget title parsing {{}}
 */
if ( ! empty( $options['render_html_in_widget_title'] ) ) {
	add_filter( 'widget_title', 'ItalyStrap\Core\render_html_in_title_output' );
}

// if (  ! empty( $options['activate_custom_script'] )  ) {
// 	add_action( 'wp_footer', function () {
// 		echo apply_filters( 'italystrap_footer_scripts', $options['custom_script'] );
// 	});
// }

/**
 * Option for killing the emojis
 */
if ( ! empty( $options['kill-emojis'] ) ) {
	add_action( 'init', 'ItalyStrap\Core\kill_emojis' );
}

/**
 * General
 * Allow shortcode in widget text.
 */
if ( ! empty( $options['do_shortcode_widget_text'] ) ) {
	add_filter( 'widget_text', 'do_shortcode' );
}

if ( ! empty( $options['remove_widget_title'] ) ) {
	/**
	 * @see ItalyStrap\Core\remove_widget_title()
	 */
	add_filter( 'widget_title', '\ItalyStrap\Core\remove_widget_title', 999 );
}

/**
 * @link https://github.com/inpsyde/menu-cache
 */
if ( ! empty( $options['menu_cache'] ) && version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {

	$cache = $injector->make( 'ItalyStrap\Cache\Menu' );

	add_filter( 'pre_wp_nav_menu', array( $cache, 'get_menu' ), 10, 2 );

	// Unfortunately, there is no appropriate action, so we have to (mis)use a filter here. Almost as last as possible.
	add_filter( 'wp_nav_menu', array( $cache, 'cache_menu' ), PHP_INT_MAX - 1, 2 );
}

/**
 * Set CSS from admin option Script
 */
Inline_Style::set( strip_tags( $options['custom_css'] ) );

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
