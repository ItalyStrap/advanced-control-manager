<?php

/**
 * Widget API: Widget_Grouped_Posts class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Query\Grouped_Posts as Grouped;

/**
 * Widget Class for displaying Posts Grouped by Taxonomies
 */
class Grouped_Posts extends Widget
{
    /**
     * Grouped Posts Object
     *
     * @var Grouped_Posts
     */
    protected $grouped = null;

    /**
     * Init the constructor
     */
    function __construct(Grouped $grouped)
    {

        $this->grouped = $grouped;

        /**
         * I don't like this and I have to find a better solution for loading script and style for widgets.
         */
        add_action('admin_enqueue_scripts', [$this, 'upload_scripts']);

        /**
         * List of posts type
         *
         * @todo Aggiungere any all'array
         * @var array
         */
        $get_post_types = get_post_types(['public' => true]);

        /**
         * Configure widget array.
         *
         * @var array
         */
        $args = [
            // Widget Backend label.
            'label'             => __('ItalyStrap Grouped Posts', 'italystrap'),
            // Widget Backend Description.
            'description'       => __('Displays list of categories with their posts with an array of options. This widget is still in ALPHA version.', 'italystrap'),
            'fields'            => $this->get_widget_fields(require(ITALYSTRAP_PLUGIN_PATH . 'config/grouped-posts.php')),
            'control_options'   => ['width' => 450],
            'widget_options'    => ['customize_selective_refresh' => true],
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
     * @return string           Return the output
     */
    public function widget_render($args, $instance)
    {

        $this->grouped->get_attributes($instance);
        return $this->grouped->output();
    }
} // Class.
