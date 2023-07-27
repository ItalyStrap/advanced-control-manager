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

use ItalyStrap\Asset\Custom_Css;
use ItalyStrap\Asset\Inline_Asset_Factory;
use ItalyStrap\Asset\Inline_Style;
use ItalyStrap\Cache\Menu;
use ItalyStrap\Debug\Visual_Hook;
use ItalyStrap\Excerpt\Excerpt;
use ItalyStrap\Google\Analytics;
use ItalyStrap\Google\Tag_Manager;
use ItalyStrap\Lazyload\Fonts;
use ItalyStrap\Lazyload\ImageSubscriber;
use ItalyStrap\Lazyload\Video;
use ItalyStrap\Shortcodes\Gallery;
use ItalyStrap\Social\Share;
use ItalyStrap\Widgets\Tag_Cloud\Tag_Cloud;
use ItalyStrap\Widgets\Visibility\Visibility;

if (is_admin()) {
    return;
}

$autoload_subscribers = array_merge($autoload_subscribers, array(
        // 'option_name'            => 'Class\Name',
        'lazyload_video'            => Video::class,
        'lazyload'                  => ImageSubscriber::class,
        'web_font_loading'          => Fonts::class, // 404
        'activate_custom_css'       => Custom_Css::class,
        'activate_analytics'        => Analytics::class,
        'google_tag_manager_id'     => Tag_Manager::class,
        'activate_social_share'     => Share::class,
        'show_theme_hooks'          => Visual_Hook::class,
        'media_carousel_shortcode'  => Gallery::class,
        'activate_excerpt_more_mods' => Excerpt::class,
        'custom_tag_cloud'          => Tag_Cloud::class,
        Inline_Asset_Factory::class,
    ));

/**
 * Se ci sono più classi da instanziare con la stessa option valutare se
 * gestire anche un array oltre che stringa, per esempio:
 *
 * [
 *     'activate_analytics'     => [
 *         'ItalyStrap\Google\Analytics',
 *         'ItalyStrap\Google\Tag_Manager',
 *     ],
 * ]
 *
 * è possibile anche definire i parametri della classe stessa in un array:
 *
 * ItalyStrap\Dir\Some_Class    => [
 *      :arg    => $some_value
 * ],
 */

// foreach ( $autoload_subscribers as $option_name => $concrete ) {
//  if ( empty( $options[ $option_name ] ) ) {
//      continue;
//  }
//  $event_manager->add_subscriber( $injector->make( $concrete ) );
// }

if (! empty($options['widget_visibility'])) {
    \add_action('init', [Visibility::class, 'init']);
}

/**
 * This filter render HTML in widget title parsing {{}}
 */
if (! empty($options['render_html_in_widget_title'])) {
    \add_filter('widget_title', 'ItalyStrap\Core\render_html_in_title_output');
}

// if (  ! empty( $options['activate_custom_script'] )  ) {
//  add_action( 'wp_footer', function () {
//      echo apply_filters( 'italystrap_footer_scripts', $options['custom_script'] );
//  });
// }

/**
 * Option for killing the emojis
 */
if (! empty($options['kill-emojis'])) {
    \add_action('init', 'ItalyStrap\Core\kill_emojis');
}

/**
 * General
 * Allow shortcode in widget text.
 */
if (! empty($options['do_shortcode_widget_text'])) {
    \add_filter('widget_text', 'do_shortcode');
}

if (! empty($options['remove_widget_title'])) {
    /**
     * @see \ItalyStrap\Core\remove_widget_title()
     */
    \add_filter('widget_title', '\ItalyStrap\Core\remove_widget_title', 999);
}

/**
 * @link https://github.com/inpsyde/menu-cache
 */
if (! empty($options['menu_cache']) && \version_compare(PHP_VERSION, '5.4.0', '>=')) {
    $cache = $injector->make(Menu::class);

    \add_filter('pre_wp_nav_menu', [$cache, 'get_menu'], 10, 2);

    // Unfortunately, there is no appropriate action, so we have to (mis)use a filter here. Almost as last as possible.
    \add_filter('wp_nav_menu', [$cache, 'cache_menu'], PHP_INT_MAX - 1, 2);
}

/**
 * Set CSS from admin option Script
 */
Inline_Style::set(\strip_tags($options['custom_css']));

/**
 * Instantiate MobileDetect Class
 *
 * @var \Detection\MobileDetect
 */
// $mobile_detect = $injector->make( 'Detection\MobileDetect' );
/**
 * This filter is applyed in class-carousel in get_img_size_attr() method
 * @todo Create an injection for Detection\MobileDetect in carousel class
 */
\add_filter('mobile_detect', '\ItalyStrap\Core\new_mobile_detect');
