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

$autoload_concrete = array_merge( $autoload_concrete, array(
		// 'option_name'			=> 'Class\Name',
		'lazyload_video'			=> 'ItalyStrap\Lazyload\Video',
		'lazyload'					=> 'ItalyStrap\Lazyload\Image',
		'web_font_loading'			=> 'ItalyStrap\Lazyload\Fonts',
		'activate_custom_css'		=> 'ItalyStrap\Asset\Custom_Css',
		'activate_analytics'		=> 'ItalyStrap\Google\Analytics',
		'activate_social_share'		=> 'ItalyStrap\Social\Share',
		'show_theme_hooks'			=> 'ItalyStrap\Debug\Visual_Hook',
		'media_carousel_shortcode'	=> 'ItalyStrap\Shortcodes\Gallery',
	)
);

foreach ( $autoload_concrete as $option_name => $concrete ) {
	if ( empty( $options[ $option_name ] ) ) {
		continue;
	}
	$event_manager->add_subscriber( $injector->make( $concrete ) );
}

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

if ( ! empty( $options['widget_attributes'] ) ) {
	/**
	 * Init the Widget_Attributes
	 *
	 * @var Widget_Attributes
	 */
	$widget_attributes = $injector->make( 'ItalyStrap\Widgets\Attributes\Attributes' );
	$event_manager->add_subscriber( $widget_attributes );
}

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

if ( ! empty( $options['activate_excerpt_more_mods'] ) ) {

	$excerpt = $injector->make( 'ItalyStrap\Excerpt\Excerpt' );

	add_filter( 'excerpt_length', array( $excerpt, 'excerpt_length') );
	add_filter( 'wp_trim_words', array( $excerpt, 'excerpt_end_with_punctuation' ), 98, 4 );

	/**
	 * Inside the <p> of the excerpt.
	 */
	if ( 'append' === $options['read_more_position'] ) {
		add_filter( 'get_the_excerpt', array( $excerpt, 'custom_excerpt_more') );
		add_filter( 'excerpt_more', array( $excerpt, 'read_more_link') );
	} 
	/**
	 * Outside the <p> of the excerpt on anew line.
	 */
	elseif ( 'after' === $options['read_more_position'] ) {
		add_filter( 'the_excerpt', array( $excerpt, 'custom_excerpt_more') );
		add_filter( 'excerpt_more', '__return_empty_string' );
		add_filter( 'wp_trim_words', array( $excerpt, 'read_more_link' ), 99 );
	}
	
}

/**
 * Set CSS from admin option Script
 */
Inline_Style::set( strip_tags( $options['custom_css'] ) );

/**
 * Print the inline assets
 */
$event_manager->add_subscriber( $injector->make( 'ItalyStrap\Asset\Inline_Asset_Factory' ) );

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
