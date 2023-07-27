<?php

/**
 * General settings for plugin bootstrap
 *
 * This is the file with general settings for ItalyStrap plugin
 *
 * @since 2.0.0
 *
 * @package Italystrap
 */

namespace ItalyStrap\Admin;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Core;

return [
    'basename'              => ITALYSTRAP_BASENAME,
    'options_name'          => ITALYSTRAP_OPTIONS_NAME,
    'options_group'         => 'italystrap_options_group',
    'admin_view_path'       => ITALYSTRAP_PLUGIN_PATH . 'admin/view/',
    'capability'            => 'manage_options',
    'plugin_action_links'   => [
        '<a href="admin.php?page=italystrap-settings">' . __('Settings', 'italystrap') . '</a>',
        '<a href="http://docs.italystrap.com/" target="_blank">' . __('Docs', 'italystrap') . '</a>',
        '<a href="https://italystrap.com/" target="_blank">ItalyStrap</a>',
    ],
    'plugin_row_meta'       => [
        '<a href="admin.php?page=italystrap-settings">' . __('Settings', 'italystrap') . '</a>',
        '<a href="http://docs.italystrap.com/" target="_blank">' . __('Doc', 'italystrap') . '</a>',
        '<a href="https://italystrap.com/" target="_blank">ItalyStrap</a>',
    ],
    'menu_page'             => [
        'page_title'        => __('ACM by ItalyStrap Dashboard', 'italystrap'),
        'menu_title'        => 'ACM by ItalyStrap',
        // 'capability'     => $this->capability,
        'menu_slug'         => 'italystrap-dashboard',
        // 'function'       => array( $this, 'get_admin_view' ),
        'icon_url'          => 'dashicons-performance',
        'position'          => null,
        // 'submenu_pages'  => array(
        //  // array(
        //  //  'parent_slug'   => 'italystrap-dashboard',//'edit.php?post_type=stores'
        //  //  'page_title'    => __( 'Documentation', 'italystrap' ),
        //  //  'menu_title'    => __( 'Documentation', 'italystrap' ),
        //  //  // 'capability' => $this->capability,
        //  //  'menu_slug'     => 'italystrap-documentation',
        //  //  // 'function_cb'    => array( $this, 'get_admin_view' ),
        //  // ),
        //  array(
        //      'parent_slug'   => 'italystrap-dashboard',
        //      'page_title'    => __( 'Settings', 'italystrap' ),
        //      'menu_title'    => __( 'Settings', 'italystrap' ),
        //      // 'capability' => $this->capability,
        //      'menu_slug'     => 'italystrap-settings',
        //      // 'function_cb'    => array( $this, 'get_admin_view' ),
        //  ),
        //  // array(
        //  //  'parent_slug'   => 'italystrap-dashboard',
        //  //  'page_title'    => __( 'Import/Export', 'italystrap' ),
        //  //  'menu_title'    => __( 'Import/Export', 'italystrap' ),
        //  //  // 'capability' => $this->capability,
        //  //  'menu_slug'     => 'italystrap-import-export',
        //  //  // 'function_cb'    => array( $this, 'get_admin_view' ),
        //  // ),
        //  array(
        //      'parent_slug'   => 'italystrap-dashboard',
        //      'page_title'    => __( 'Migrations', 'italystrap' ),
        //      'menu_title'    => __( 'Migrations', 'italystrap' ),
        //      // 'capability' => $this->capability,
        //      'menu_slug'     => 'italystrap-migrations',
        //      // 'function_cb'    => array( $this, 'get_admin_view' ),
        //      'show_on'       => ( get_option( 'template' ) === 'ItalyStrap' ),
        //  ),
        // ),
    ],
    'submenu_pages' => [
        // array(
        //  'parent_slug'   => 'italystrap-dashboard',//'edit.php?post_type=stores'
        //  'page_title'    => __( 'Documentation', 'italystrap' ),
        //  'menu_title'    => __( 'Documentation', 'italystrap' ),
        //  // 'capability' => $this->capability,
        //  'menu_slug'     => 'italystrap-documentation',
        //  // 'function_cb'    => array( $this, 'get_admin_view' ),
        // ),
        [
            'parent_slug'   => 'italystrap-dashboard',
            'page_title'    => __('Settings', 'italystrap'),
            'menu_title'    => __('Settings', 'italystrap'),
            // 'capability' => $this->capability,
            'menu_slug'     => 'italystrap-settings',
            // 'function_cb'    => array( $this, 'get_admin_view' ),
        ],
        // array(
        //  'parent_slug'   => 'italystrap-dashboard',
        //  'page_title'    => __( 'Import/Export', 'italystrap' ),
        //  'menu_title'    => __( 'Import/Export', 'italystrap' ),
        //  // 'capability' => $this->capability,
        //  'menu_slug'     => 'italystrap-import-export',
        //  // 'function_cb'    => array( $this, 'get_admin_view' ),
        // ),
        [
            'parent_slug'   => 'italystrap-dashboard',
            'page_title'    => __('Migrations', 'italystrap'),
            'menu_title'    => __('Migrations', 'italystrap'),
            // 'capability' => $this->capability,
            'menu_slug'     => 'italystrap-migrations',
            // 'function_cb'    => array( $this, 'get_admin_view' ),
            'show_on'       => get_option('template') === 'ItalyStrap',
        ],
    ],
];
