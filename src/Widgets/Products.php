<?php

/**
 * Widget API: Widget_Product class
 *
 * @package ItalyStrap
 * @since 4.0.0
 */

namespace ItalyStrap\Widgets;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Query\Posts as Products_Base;

/**
 * Widget Class for post type
 */
class Products extends Widget
{
    /**
     * Fire Before Create Fields
     *
     * Allows to modify code before creating the fields.
     *
     * @access protected
     * @param  array $fields The fields array.
     * @return array         Return a fields array
     */
    protected function before_field_types(array $fields)
    {

        $fields['cats']['taxonomy'] = 'product_cat';

        // $fields['cats']['options'] = ( ( is_admin() ) ? get_taxonomies_list_array( 'product_cat' ) : null );

        $fields['tags']['taxonomy'] = 'product_tag';

        // $fields['tags']['options'] = ( ( is_admin() ) ? get_taxonomies_list_array( 'product_tag' ) : null );

        $fields['post_types'] = [
            'name'      => __('Post type', 'italystrap'),
            'desc'      => __('Select the post type.', 'italystrap'),
            'id'        => 'post_types',
            'type'      => 'multiple_select',
            'class'     => 'widefat post_types',
            'default'   => 'product',
            'options'   => ['product' => 'product'],
            // 'validate'   => 'numeric_comma',
            'filter'    => 'sanitize_text_field',
            'section'   => 'filter',
        ];

        return apply_filters('italystrap_before_create_fields', $fields, $this->id_base);
    }

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
            'label'             => __('ItalyStrap Product', 'italystrap'),
            // Widget Backend Description.
            'description'       => __('Displays list of WC product with an array of options', 'italystrap'),
            'fields'            => $this->get_widget_fields(require(ITALYSTRAP_PLUGIN_PATH . 'config/posts.php')),
            'widget_options'    => ['customize_selective_refresh' => true],
            'control_options'   => ['width' => 450],
        ];

        /**
         * Create Widget
         */
        $this->create_widget($args);
    }

    /**
     * Parse the query argument before put it in the WP_Query
     *
     * @param  array $args Query arguments
     * @return array       New Query arguments
     */
    public function parse_query_arguments($args)
    {

        if (! isset($args['tag__in']) && ! isset($args['category__in'])) {
            return $args;
        }

        if (isset($args['tag__in']) && ! isset($args['category__in'])) {
            $args['tax_query'] = [['taxonomy'  => 'product_tag', 'terms'     => $args['tag__in']]];
        } elseif (! isset($args['tag__in']) && isset($args['category__in'])) {
            $args['tax_query'] = [['taxonomy'  => 'product_cat', 'terms'     => $args['category__in']]];
        } else {
            $args['tax_query'] = ['relation'  => 'AND', ['taxonomy'  => 'product_tag', 'terms'     => $args['tag__in']], ['taxonomy'  => 'product_cat', 'terms'     => $args['category__in']]];
        }

        unset($args['tag__in']);
        unset($args['category__in']);

        return $args;
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

        $query_posts = Products_Base::init($this->id_base);

        $query_posts->get_widget_args($instance);

        add_filter("italystrap_{$this->id_base}_query_arg", [$this, 'parse_query_arguments']);

        return $query_posts->output();
    }
} // Class.
