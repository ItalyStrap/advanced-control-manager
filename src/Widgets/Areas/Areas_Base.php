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
use ItalyStrap\Asset\Inline_Style;
use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Update\Update;

/**
 * Widget Areas Class
 */
class Areas_Base
{
    /**
     * [$var description]
     *
     * @var null
     */
    private $options = null;

    /**
     * Array with widget areas
     */
    private array $widget_areas = [];

    /**
     * Update object for saving data to DB
     *
     * @var Update
     */
    protected $update = null;

    /**
     * Instance of CSS Generator
     *
     * @var CSS_Generator
     */
    protected $css = null;

    protected $cmb2_config = [];

    /**
     * [__construct description]
     *
     * @param [type] $argument [description].
     */
    function __construct(array $options = [], Update $update, CSS_Generator $css)
    {
        // $this->sidebars = $options;
        $this->sidebars = apply_filters('italystrap_registered_widget_areas_config', get_option('italystrap_widget_area'));
        // delete_option( 'italystrap_widget_area' );
        // error_log( print_r( get_option( 'italystrap_widget_area' ), true ) );

        $this->update = $update;
        $this->css = $css;

        $this->prefix = 'italystrap';

        $this->_prefix = '_' . $this->prefix;

        $this->cmb2_config = (array) require(__DIR__ . DIRECTORY_SEPARATOR . 'config/metaboxes.php');

        $this->default = $this->cmb2_config['fields'];
    }

    /**
     * Get joined posts and postmeta
     *
     * https://kuttler.eu/en/code/custom-wordpress-sql-query-for-multiple-meta-values/
     * https://stackoverflow.com/questions/26319613/improving-a-query-using-a-lot-of-inner-joins-to-wp-postmeta-a-key-value-table
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_joined_post_and_postmeta_from_db()
    {

        $post_type = 'sidebar';

        global $wpdb;
        $sql_query =
        "SELECT p.ID AS post_id, p.post_title, p.post_excerpt, p.post_parent, p.menu_order, p.post_name, m1.meta_key, m1.meta_value
	FROM {$wpdb->posts} AS p
	INNER JOIN {$wpdb->postmeta} AS m1 ON ( p.ID = m1.post_id )
	INNER JOIN {$wpdb->postmeta} AS m2 ON ( p.ID = m2.post_id )
	WHERE p.post_type = %s AND p.post_status = 'publish'
	GROUP BY p.ID
	ORDER BY p.menu_order ASC;";

        $sql_query = $wpdb->prepare($sql_query, $post_type);
        $results = $wpdb->get_results($sql_query);

        if (! $results || ! is_array($results)) {
            return false;
        }

        return $results;
    }

    /**
     * Generate HTML attributes
     * Helper method for the get_attr();
     * Build list of attributes into a string and apply contextual filter on string.
     *
     * The contextual filter is of the form `italystrap_attr_{context}_output`.
     *
     * @since 2.5.0
     *
     * @see In general-function on the plugin.
     *
     * @param  string $context    The context, to build filter name.
     * @param  array  $attributes Optional. Extra attributes to merge with defaults.
     * @param  bool   $echo       True for echoing or false for returning the value.
     *                            Default false.
     * @param  null   $args       Optional. Extra arguments in case is needed.
     *
     * @return string String of HTML attributes and values.
     */
    public function attr($context, array $attr = [], $echo = false, $args = null)
    {

        Core\get_attr($context, $attr, $echo, $args);
    }

    /**
     * Get Sidebar ID
     *
     * @param  int    $id The numeric ID of the single sidebar registered.
     *
     * @return string     The ID of the sidebar to load on front-end
     */
    protected function get_the_id($id)
    {
        return $this->sidebars[ $id ]['value']['id'];
    }

    /**
     * register_post_type function.
     *
     * @access public
     * @return void
     */
    public function register_post_type()
    {
        // Allow only users who can adjust the theme to view the WooSidebars admin.
        if (! current_user_can('edit_theme_options')) {
            return;
        }

        $page = 'themes.php';

        $singular = __('Widget Area', 'italystrap');
        $plural = __('Widget Areas', 'italystrap');
        $rewrite = ['slug' => 'sidebars'];
        $supports = ['title', 'excerpt'];

        if ('' === $rewrite) {
            $rewrite = 'sidebar';
        }

        $labels = ['name'                  => _x($plural, 'post type general name', 'italystrap'), 'singular_name'         => _x($singular, 'post type singular name', 'italystrap'), 'add_new'               => _x('Add New', $singular), 'add_new_item'          => sprintf(
            __('Add New %s', 'italystrap'),
            $singular
        ), 'edit_item'             => sprintf(
            __('Edit %s', 'italystrap'),
            $singular
        ), 'new_item'              => sprintf(
            __('New %s', 'italystrap'),
            $singular
        ), 'all_items'             => $plural, 'view_item'             => sprintf(
            __('View %s', 'italystrap'),
            $singular
        ), 'search_items'          => sprintf(
            __('Search %a', 'italystrap'),
            $plural
        ), 'not_found'             => sprintf(
            __('No %s Found', 'italystrap'),
            $plural
        ), 'not_found_in_trash'    => sprintf(
            __('No %s Found In Trash', 'italystrap'),
            $plural
        ), 'parent_item_colon'     => '', 'menu_name'             => $plural];
        $args = [
            'labels'                => $labels,
            'public'                => false,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_nav_menus'     => false,
            'show_in_admin_bar'     => true,
            'show_in_menu'          => $page,
            'show_in_rest'          => false,
            'query_var'             => true,
            'rewrite'               => $rewrite,
            // 'capability_type'        => 'post',
            'has_archive'           => 'sidebars',
            'hierarchical'          => false,
            'menu_position'         => null,
            'supports'              => $supports,
        ];
        register_post_type('sidebar', $args);
    } // End register_post_type()

    /**
     * Print add button in widgets.php.
     *
     * @hooked 'widgets_admin_page' - 10
     */
    public function print_add_button()
    {

        printf(
            '<div><a %s>%s</a></div>',
            Core\get_attr(
                'widget_add_sidebar',
                ['href'  => 'post-new.php?post_type=sidebar', 'class' => 'button button-primary sidebar-chooser-add', 'style' => 'margin:1em 0;'],
                false
            ),
            __('Add new widgets area', 'italystrap')
        );
    }
}
