<?php

/**
 * Widget API: Editor class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

/**
 * Widget Class for TinyMCE editor
 */
class Editor extends Widget
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
         * Configure widget array.
         *
         * @var array
         */
        $args = [
            // Widget Backend label.
            'label'             => __('ItalyStrap Editor', 'italystrap'),
            // Widget Backend Description.
            'description'       => __('The visual editor widget.', 'italystrap'),
            'fields'            => $this->get_widget_fields(require(ITALYSTRAP_PLUGIN_PATH . 'config/editor.php')),
            'control_options'   => ['width' => 650],
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
        /**
         * This is not escaped like WP standard Text Widget does.
         * The input is sanitized if User can't 'unfiltered_html'
         */
        return sprintf(
            '<div class="widget_editor">%s</div>',
            $instance['text']
        );
    }
} // Class.
