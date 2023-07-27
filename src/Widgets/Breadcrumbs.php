<?php

/**
 * Widget API: Widget Breadcrumbs
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

/**
 * Da leggere https://carlalexander.ca/polymorphism-wordpress-interfaces/
 */

/**
 * Class
 */
class Breadcrumbs extends Widget
{
    /**
     * Init the constructor
     */
    function __construct()
    {

        $fields = array_merge($this->title_field(), require(ITALYSTRAP_PLUGIN_PATH . 'config/breadcrumbs.php'));

        /**
         * Configure widget array.
         * @var array
         */
        $args = [
            // Widget Backend label.
            'label'             => __('ItalyStrap Breadcrumbs', 'italystrap'),
            // Widget Backend Description.
            'description'       => __('Add a Breadcrumbs ti widgetized area', 'italystrap'),
            'fields'            => $fields,
            'widget_options'    => ['customize_selective_refresh' => true],
            'control_options'   => ['width' => 340],
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

        // echo "<div>";

        // new \ItalyStrapBreadcrumbs();

        // echo "</div>";

        // $out = '';

        // return $out;
    }
} // class
