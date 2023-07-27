<?php

/**
 * Widget Areas API: Widget Areas class
 *
 * @forked from woosidebars
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets\Areas;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Core;
use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Update\Update;

/**
 * Widget Areas Class
 */
class Areas extends Areas_Base implements Subscriber_Interface
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
            'widgets_init'          => 'register_sidebars',
            'init'                  => ['function_to_add'       => 'register_post_type', 'priority'              => 20],
            'save_post_sidebar'     => ['function_to_add'       => 'add_sidebar', 'accepted_args'         => 3],
            'edit_post'             => ['function_to_add'       => 'add_sidebar', 'accepted_args'         => 2],
            'delete_post'           => 'delete_sidebar',
            'wp_import_post_meta'   => ['function_to_add'       => 'import_postmeta', 'accepted_args'         => 3],
            'widgets_admin_page'    => 'print_add_button',
            'italystrap_cmb2_configurations_array'  => ['function_to_add'   => 'register_metaboxes', 'priority'          => 10],
        ];
    }

    /**
     * Register Metaboxes
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function register_metaboxes(array $cmb2_configs)
    {

        $cmb2_configs[] = $this->cmb2_config;

        return $cmb2_configs;
    }

    /**
     * Add widget area to the front-end
     *
     * @param  int    $id The numeric ID of the single sidebar registered.
     */
    public function add_widget_area($id)
    {

        $sidebar_id = esc_attr($this->get_the_id($id));

        /**
         * This filters this sidebar configuration before it render to the browser.
         *
         * Some data passed with this array are already used for registering the widget area, and if you change they nothing happen.
         *
         * You can use the $sidebar['id'] to check the sidebar name and change the $sidebar['style'] if you need to add some custom background color or image url/id.
         *
         * The $sidebar['widget_area_class'] and $sidebar['container_width'] are also filtered
         * with italystrap_{$sidebar['id']}_widget_area_attr and italystrap_{$sidebar['id']}_container_attr @see view/widget-area.php
         *
         * @var array
         */
        $sidebar = apply_filters('italystrap_before_render_widget_area', $this->sidebars[ $id ]);

        if (! is_active_sidebar($sidebar_id)) {
            return;
        }

        /**
         * Example:
         *  add_filter( 'italystrap_widget_area_visibility', function ( $bool, $sidebar_id ) {
         *      // Check for sidebar ID
         *      if ( 'hero-bottom' !== $sidebar_id ) {
         *          return $bool;
         *      }
         *      // Visibility rule
         *      if ( is_page() ) {
         *          return false;
         *      }
         *      // Always return the $bool variable
         *      return $bool;
         *  }, 10, 2 );
         */
        if (! apply_filters('italystrap_widget_area_visibility', true, $sidebar_id)) {
            return;
        }

        foreach ($this->default as $key => $value) {
            if (! isset($sidebar[ $key ])) {
                $sidebar[ $key ] = $this->default[ $key ]['default'];
            }
        }

        $container_width = $sidebar['container_width'];
        $widget_area_class = $sidebar['widget_area_class'];

        $this->css->style($sidebar);

        $widget_area_attr = ['class' => 'widget_area ' . $sidebar_id . ' ' . esc_attr($widget_area_class), 'id'    => $sidebar_id];

        require(__DIR__ . '/view/widget-area.php');
    }

    /**
     * Register the sidebars
     */
    public function register_sidebars()
    {

        $areas_obj = $this;

        foreach ((array) $this->sidebars as $sidebar_key => $sidebar) {
            if (! isset($sidebar['value']['id'])) {
                continue;
            }

            if (strpos($sidebar['id'], '__trashed')) {
                continue;
            }

            if ('' === $sidebar['action']) {
                continue;
            }

            register_sidebar($sidebar['value']);

            add_action($sidebar['action'], function () use ($sidebar_key, $areas_obj) {
                $areas_obj->add_widget_area($sidebar_key);
            }, absint($sidebar['priotity']));
        }
    }

    /**
     * Import post_meta
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function import_postmeta($postmeta, $post_id, $post)
    {

        // global $wp_import;
        // error_log( print_r( $wp_import, true ) );
        // error_log( print_r( new \WP_Import(), true ) );

        $new_postmeta = [];

        foreach ($postmeta as $array) {
            $new_postmeta[ $array['key'] ] = $array['value'];
        }

        $post = (object) $post;

        $this->add_sidebar($post_id, $post, null, $new_postmeta);

        return $postmeta;
    }

    /**
     * Add new sidebar to database
     *
     * @param int            $post_ID  The post ID.
     * @param WP_Post|stdObj $post     WP_Post object also used by $this->import_postmeta();.
     * @param bool   $update           Is post update or not.
     * @param array  $postmeta         Postmeta data used only by $this->import_postmeta().
     */
    public function add_sidebar($post_ID, $post, $update = false, $postmeta = [])
    {

        if ('sidebar' !== $post->post_type) {
            return $post_ID;
        }

        if ('' === $post->post_name) {
            return $post_ID;
        }

        if (! current_user_can('edit_post', $post_ID)) {
            return $post_ID;
        }

        if (false === get_option('italystrap_widget_area')) {
            add_option('italystrap_widget_area', []);
        }

        static $run_once = false;
        /**
         * Run only once and when is not an update
         */
        // if ( $run_once ) {
        //  return $post_ID;
        // }
        // error_log( print_r( $run_once, true ) );

        // delete_option( 'italystrap_widget_area' );

        $fields = $this->default;

        $fields['background_image']['id'] = $this->_prefix . '_background_image_id';

        $instance = [];

        /**
         * Is is an import use $postmeta otherwise use $_POST
         *
         * @var array
         */
        $postmeta = empty($postmeta) ? $_POST : $postmeta;
        // $postmeta = $update ? $postmeta : $_POST;
        // $postmeta = $_POST;

        $instance = $this->update->update($postmeta, $fields);

        $this->sidebars[ $post_ID ] = ['id'                => $post->post_name, 'action'            => $instance[ $this->_prefix . '_action' ], 'priotity'          => (int) $instance[ $this->_prefix . '_priority' ], 'style'             => ['background-color'      => $instance[ $this->_prefix . '_background_color' ], 'background-image'      => (int) $instance[ $this->_prefix . '_background_image_id'], 'background-image-id'   => (int) $instance[ $this->_prefix . '_background_image_id']], 'widget_area_class' => $instance[ $this->_prefix . '_widget_area_class' ], 'container_width'   => $instance[ $this->_prefix . '_container_width' ], 'value'             => ['name'              => $post->post_title, 'id'                => $post->post_name, 'description'       => $post->post_excerpt, 'before_widget'     => sprintf(
            '<%s %s>',
            $instance[ $this->_prefix . '_widget_before_after' ],
            \ItalyStrap\Core\get_attr($post->post_name, ['class' => 'widget %2$s', 'id' => '%1$s'])
        ), 'after_widget'      => sprintf(
            '</%s>',
            $instance[ $this->_prefix . '_widget_before_after' ]
        ), 'before_title'      => sprintf(
            '<%s class="widget-title %s">',
            $instance[ $this->_prefix . '_widget_title_before_after' ],
            $instance[ $this->_prefix . '_widget_title_class' ]
        ), 'after_title'       => sprintf(
            '</%s>',
            $instance[ $this->_prefix . '_widget_title_before_after' ]
        )]];

        // error_log( print_r( $this->sidebars, true ) );
        // $this->reorder_sidebar( $post_ID, $instance, '', $post );

        update_option('italystrap_widget_area', $this->sidebars);

        $run_once = true;

        return $post_ID;
    }

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function delete_sidebar($post_id)
    {

        if (! isset($this->sidebars[ $post_id ])) {
            return $post_id;
        }

        unset($this->sidebars[ $post_id ]);

        update_option('italystrap_widget_area', $this->sidebars);
    }

    /**
     * Reorder Widget Areas
     *
     * @param array|object $postarr  Optional. Post data. Arrays are expected to be escaped,
     *                               objects are not. Default array.
     */
    public function reorder_sidebar($post_id = null, $instance = '', $postarr = '', $post = null)
    {

        // Reorder by priority
        // And reorder by action order

        // error_log( print_r( get_post_meta( $post_id, $this->_prefix . '_action', true ), true ) );

        // var_dump( get_post_meta( $post_id, $this->_prefix . '_action', true ) );

        // global $wp_filter;

        // error_log( print_r( $wp_filter[ $instance[ $this->_prefix . '_action' ] ], true ) );
        // $this->sidebars[ $post_id ]['priotity']

        $temp = [];

        // foreach ( $this->sidebars as $key => $value ) {
        //  $temp[ $this->sidebars[ $key ]['action'] ] = $this->sidebars[ $key ]['priotity'];
        // }


        foreach (apply_filters('italystrap_theme_positions', []) as $key => $value) {
            foreach ($this->sidebars as $id => $config) {
                if ($config['action'] === $key) {
                    $temp[ $id ] = $config;
                }
            }
        }

        // error_log( print_r( $temp, true ) );
        // error_log( print_r( $post, true ) );
        // error_log( print_r( apply_filters( 'italystrap_theme_positions', array() ), true ) );
    }
}
