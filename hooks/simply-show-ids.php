<?php

/**
 * Add ID column to post_type admin table
 *
 * @package ItalyStrap
 * @since   2.0.0
 */

namespace ItalyStrap\Admin;

if (! defined('ITALYSTRAP_PLUGIN') or ! ITALYSTRAP_PLUGIN) {
    die();
}

/**
 * This code is forked from the Simply Show IDs WordPress plugin
 * Original author Matt Martz http://sivel.net http://sivel.net/wordpress/simply-show-ids/
 */

/**
 * Prepend the new column to the columns array
 *
 * @param  array $cols Table column.
 * @return array       New table column
 */
function ssid_column($cols)
{

    $cols['ssid'] = 'ID';
    return $cols;
}

/**
 * Echo the ID for the new column
 *
 * @param  string $column_name The name of column.
 * @param  int    $id          The ID of post_type.
 */
function ssid_value($column_name, $id)
{

    if ('ssid' === $column_name) {
        echo $id; // XSS ok.
    }
}

/**
 * [ssid_return_value description]
 *
 * @param  [type] $value       [description].
 * @param  [type] $column_name [description].
 * @param  [type] $id          [description].
 * @return [type]              [description]
 */
function ssid_return_value($value, $column_name, $id)
{

    if ('ssid' === $column_name) {
        $value = $id;
    }

    return $value;
}

/**
 * Output CSS for width of new column
 */
function ssid_css()
{
    ?>
<style type="text/css">
    #ssid { width: 50px; } /* Simply Show IDs */
</style>
    <?php
}

/**
 * Actions/Filters for various tables and the css output
 */
function ssid_add()
{
    add_action('admin_head', __NAMESPACE__ . '\ssid_css');

    add_filter('manage_posts_columns', __NAMESPACE__ . '\ssid_column');
    add_action('manage_posts_custom_column', __NAMESPACE__ . '\ssid_value', 10, 2);

    add_filter('manage_pages_columns', __NAMESPACE__ . '\ssid_column');
    add_action('manage_pages_custom_column', __NAMESPACE__ . '\ssid_value', 10, 2);

    add_filter('manage_media_columns', __NAMESPACE__ . '\ssid_column');
    add_action('manage_media_custom_column', __NAMESPACE__ . '\ssid_value', 10, 2);

    add_filter('manage_link-manager_columns', __NAMESPACE__ . '\ssid_column');
    add_action('manage_link_custom_column', __NAMESPACE__ . '\ssid_value', 10, 2);

    add_action('manage_edit-link-categories_columns', __NAMESPACE__ . '\ssid_column');
    add_filter('manage_link_categories_custom_column', __NAMESPACE__ . '\ssid_return_value', 10, 3);

    $get_taxonomies = get_taxonomies();

    foreach ($get_taxonomies as $taxonomy) {
        add_action("manage_edit-${taxonomy}_columns", __NAMESPACE__ . '\ssid_column');
        add_filter("manage_${taxonomy}_custom_column", __NAMESPACE__ . '\ssid_return_value', 10, 3);
    }

    add_action('manage_users_columns', __NAMESPACE__ . '\ssid_column');
    add_filter('manage_users_custom_column', __NAMESPACE__ . '\ssid_return_value', 10, 3);

    add_action('manage_edit-comments_columns', __NAMESPACE__ . '\ssid_column');
    add_action('manage_comments_custom_column', __NAMESPACE__ . '\ssid_value', 10, 2);
}
