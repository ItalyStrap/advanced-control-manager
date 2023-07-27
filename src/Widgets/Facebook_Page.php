<?php

/**
 * Widget API: Facebook_Page class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Facebook\Page;

/**
 * Widget Class for post type
 */
class Facebook_Page extends Widget
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
         * List of posts type
         *
         * @todo Aggiungere any all'array
         * @var array
         */
        // $get_post_types = get_post_types( array( 'public' => true ) );

        /**
         * Configure widget array.
         *
         * @var array
         */
        $args = [
            // Widget Backend label.
            'label'             => __('ItalyStrap Facebook Page', 'italystrap'),
            // Widget Backend Description.
            'description'       => __('Display the Facebook Page widget', 'italystrap'),
            'fields'            => $this->get_widget_fields(require(ITALYSTRAP_PLUGIN_PATH . 'config/facebook-page.php')),
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

        $facebook_page = new Page();
        add_action('wp_footer', [$facebook_page, 'script_2'], 99);
        // add_action( 'italystrap_sidebar', array( $facebook_page, 'output' ) );

        // $query_posts = Posts_Base::init();

        // $query_posts->get_widget_args( $instance );

        return $facebook_page->render($instance);
    }
} // Class.
