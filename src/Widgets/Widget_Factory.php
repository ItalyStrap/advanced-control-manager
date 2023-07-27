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

namespace ItalyStrap\Widgets;

if (! defined('ITALYSTRAP_PLUGIN') or ! ITALYSTRAP_PLUGIN) {
    die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Widget_Factory
 */
class Widget_Factory implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked 'widgets_init' - 10
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            // 'hook_name'              => 'method_name',
            'widgets_init'  => ['function_to_add'   => 'register', 'priority'          => 10],
            'monster-widget-config' => 'monster_widget',
        ];
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
    private $widget_list = [];

    /**
     * Injector object
     *
     * @var null
     */
    private $injector = null;

    /**
     * Fire the construct
     */
    public function __construct(array $options = [], $injector = null)
    {
        $this->options = $options;
        $this->injector = $injector;

        $this->widget_list = [
            'vcardwidget'               => \ItalyStrap\Widgets\Vcard_Widget::class,
            // Deprecated
            // 'post_widget'                => 'Widget_Posts2', // Deprecated
            'media_carousel_widget'     => \ItalyStrap\Widgets\Carousel::class,
            'widget_posts'              => \ItalyStrap\Widgets\Posts::class,
            'widget_vcard'              => \ItalyStrap\Widgets\VCard::class,
            // New
            'widget_image'              => \ItalyStrap\Widgets\Image::class,
            // New
            'widget_facebook_page'      => \ItalyStrap\Widgets\Facebook_Page::class,
            // New
            'widget_breadcrumbs'        => \ItalyStrap\Widgets\Breadcrumbs::class,
            // Beta
            'widget_grouped_posts'      => \ItalyStrap\Widgets\Grouped_Posts::class,
            // Beta
            'widget_editor'             => \ItalyStrap\Widgets\Editor::class,
            // _Beta
            // 'widget_nav_menu'            => 'ItalyStrap\\Widgets\\Nav_Menu', // _Beta
            'widget_monster'            => \ItalyStrap\Widgets\Monster::class,
        ];
    }

    /**
     * Add action to widget_init
     * Initialize widget
     */
    public function register()
    {

        foreach ((array) $this->widget_list as $option_name => $class_name) {
            if (empty($this->options[ $option_name ])) {
                continue;
            }

            \register_widget($this->injector->make($class_name));
        }
    }

    /**
     * monster-widget
     *
     * @param  array $widgets Widget configuration.
     * @return array          New Widget configuration.
     */
    public function monster_widget($widgets)
    {

        $new_widgets = [[\ItalyStrap\Widgets\Carousel::class, ['title'     => __('Bootstrap Carousel', 'italystrap'), 'ids'       => '2300,2298,2244']], [\ItalyStrap\Widgets\Posts::class, ['title'     => __('ItalyStrap Posts', 'italystrap'), 'post_types'    => 'post']], [\ItalyStrap\Widgets\VCard::class, ['title'     => __('ItalyStrap VCard', 'italystrap')]], [\ItalyStrap\Widgets\Image::class, ['title'     => __('ItalyStrap Image', 'italystrap'), 'id'        => '2300']], [\ItalyStrap\Widgets\Facebook_Page::class, ['title'     => __('Facebook_Page', 'italystrap')]], [\ItalyStrap\Widgets\Breadcrumbs::class, ['title'     => __('Breadcrumbs', 'italystrap')]], ['ItalyStrap\\Widgets\\Taxonomies_Posts', ['title'     => __('Taxonomies_Posts', 'italystrap')]]];

        return wp_parse_args($widgets, $new_widgets);
    }
}
