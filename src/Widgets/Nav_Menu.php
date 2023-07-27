<?php

/**
 * Widget API: Widget_Nav_Menu class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Navbar\BootstrapNavMenu;

/**
 * Widget Class for post type
 */
class Nav_Menu extends Widget
{
    /**
     * Init the constructor
     */
    function __construct(BootstrapNavMenu $nav_menu)
    {

        $this->nav_menu = $nav_menu;

        $this->config = require(ITALYSTRAP_PLUGIN_PATH . 'config/nav-menu.php');

        /**
         * I don't like this and I have to find a better solution for loading script and style for widgets.
         */
        // add_action( 'admin_enqueue_scripts', array( $this, 'upload_scripts' ) );

        $fields = array_merge($this->title_field(), $this->config);

        /**
         * Configure widget array.
         *
         * @var array
         */
        $args = [
            // Widget Backend label.
            'label'             => __('ItalyStrap Nav Menu', 'italystrap'),
            // Widget Backend Description.
            'description'       => __('Add an advanced custom menu to your sidebar.', 'italystrap'),
            'fields'            => $fields,
            'widget_options'    => ['customize_selective_refresh' => true],
            'control_options'   => ['width' => 450],
        ];

        /**
         * Create Widget
         */
        $this->create_widget($args);
    }

    /**
     * Dispay the widget content
     *
     * @param  array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param  array $instance The settings for the particular instance of the widget.
     */
    public function widget_render($args, $instance)
    {
// echo "<pre>";
// print_r($instance['nav_menus']);
// echo "</pre>";
        // Get menu
        $nav_menu = ! empty($instance['nav_menu']) ? wp_get_nav_menu_object($instance['nav_menu']) : false;

        if (! $nav_menu) {
            return;
        }

        $nav_menu_args = [
            'echo'              => false,
            'fallback_cb'       => '',
            'menu'              => $nav_menu,
            'container'         => false,
            // WP Default div.
            'container_class'   => false,
            'container_id'      => false,
            'menu_class'        => 'list-inline info-menu flex-menu',
            'before'            => '',
            'after'             => '',
            'link_before'       => '<span class="item-title sr-only" itemprop="name">',
            'link_after'        => '</span>',
            'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'depth'             => 1,
            'walker'            => $this->nav_menu,
        ];

        /**
         * Filters the arguments for the Custom Menu widget.
         *
         * @since 4.2.0
         * @since 4.4.0 Added the `$instance` parameter.
         *
         * @param array    $nav_menu_args {
         *     An array of arguments passed to wp_nav_menu() to retrieve a custom menu.
         *
         *     @type callable|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
         *     @type mixed         $menu        Menu ID, slug, or name.
         * }
         * @param WP_Term  $nav_menu      Nav menu object for the current menu.
         * @param array    $args          Display arguments for the current widget.
         * @param array    $instance      Array of settings for the current widget.
         */
        return wp_nav_menu(apply_filters('widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance));
    }
} // Class.
