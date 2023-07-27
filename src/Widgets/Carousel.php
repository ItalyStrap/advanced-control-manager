<?php

/**
 * Widget API: Widget Media Carousel
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Carousel\Bootstrap;

// use \ItalyStrapAdminMediaSettings;

/**
 * Da leggere https://carlalexander.ca/polymorphism-wordpress-interfaces/
 */

/**
 * Class
 */
class Carousel extends Widget
{
    /**
     * Init the constructor
     */
    function __construct()
    {

        /**
         * I don't like this and I have to find a better solution for loading script and style for widgets.
         */
        add_action('admin_enqueue_scripts', [$this, 'upload_scripts']);

        /**
         * Instance of list of image sizes
         * @var ItalyStrapAdminMediaSettings
         */
        // $image_size_media = new ItalyStrapAdminMediaSettings;
        $image_size_media = new \ItalyStrap\Image\Size();
        $image_size_media_array = $image_size_media->get_image_sizes();

        $fields = array_merge($this->title_field(), require(ITALYSTRAP_PLUGIN_PATH . 'config/media-carousel.php'));

        /**
         * Configure widget array.
         * @var array
         */
        $args = [
            // Widget Backend label.
            'label'             => __('ItalyStrap Media Carousel', 'italystrap'),
            // Widget Backend Description.
            'description'       => __('Add a image carousel for all your media files from any posts type (posts, pages, attachments and custom post type)', 'italystrap'),
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

        $mediacarousel = new Bootstrap($instance);

        return $mediacarousel->__get('output');
    }

    /**
     * Validate if is the format num,num,
     * @param  int $value The vallue of the field.
     * @return bool       Return tru if is format num,num, else return false
     */
    function numeric_comma($value)
    {

        $instance_value = null;
        return (bool) preg_match('/(?:\d+\,)+?/', $instance_value);
    }
} // class
