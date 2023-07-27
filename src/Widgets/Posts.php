<?php

/**
 * Widget API: Widget_Posts class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Query\Posts as PostsBase;

/**
 * Widget Class for post type
 */
class Posts extends Widget
{
    /**
     * Instance of ItalyStrap\Query\Posts
     */
    private ?PostsBase $query_posts = null;

    /**
     * Init the constructor
     */
    function __construct(PostsBase $post)
    {

        $this->query_posts = $post;

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
            'label'             => __('ItalyStrap Posts', 'italystrap'),
            // Widget Backend Description.
            'description'       => __('Displays list of posts with an array of options', 'italystrap'),
            'fields'            => $this->get_widget_fields(require(ITALYSTRAP_PLUGIN_PATH . 'config/posts.php')),
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

        // $query_posts = Posts_Base::init();

        $this->query_posts->get_widget_args($instance);

        return $this->query_posts->output();
    }
} // Class.
