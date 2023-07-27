<?php

/**
 * Widget API: Widget Image
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Image\Image as Image_Base;

// use \ItalyStrapAdminMediaSettings;

/**
 * Da leggere https://carlalexander.ca/polymorphism-wordpress-interfaces/
 */

/**
 * Class
 */
class Image extends Widget
{
    /**
     * Init the constructor
     */
    function __construct()
    {

        $this->config = require(ITALYSTRAP_PLUGIN_PATH . 'config/image.php');

        /**
         * I don't like this and I have to find a better solution for loading script and style for widgets.
         */
        add_action('admin_enqueue_scripts', [$this, 'upload_scripts']);

        $fields = array_merge($this->title_field(), $this->config);

        /**
         * Configure widget array.
         *
         * @var array
         */
        $args = [
            // Widget Backend label.
            'label'             => __('ItalyStrap Image', 'italystrap'),
            // Widget Backend Description.
            'description'       => __('Add a image with title, url and description', 'italystrap'),
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

        $image = new Image_Base();

        $image->get_args('widget_image', $instance, $this->config);

        return $image->output();
    }
} // Class.
