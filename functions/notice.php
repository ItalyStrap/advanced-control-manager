<?php

/**
 * Functions for notices in admin panel
 *
 * @link italystrap.com
 * @since 2.0.0
 *
 * @package Italystrap\Core
 */

namespace ItalyStrap\Core;

/**
 * This will make shure the plugin files can't be accessed within the web browser directly.
 */
if (! defined('WPINC')) {
    die;
}

/**
 * ItalyStrap plugin on activation
 * @see _init_admin.php#36
 * @see _init_admin.php#37
 */
function plugin_on_activation()
{

    // var_dump( get_user_meta( get_current_user_id(), 'italystrap_notice_plugin_update_2', true ) );
    // var_dump( delete_user_meta( get_current_user_id(), 'italystrap_notice_plugin_update_2' ) );

    add_action('admin_notices', __NAMESPACE__ . '\_admin_notice_success', 999);
}

/**
 * Add notice.
 * Internal use.
 */
function _admin_notice_success()
{

    global $pagenow;
    if ('plugins.php' !== $pagenow) {
        return null;
    }

    if (get_user_meta(get_current_user_id(), 'italystrap_notice_plugin_update_2')) {
        return null;
    }

    $message = 'Welcome to the new version 2.0 of ItalyStrap plugin, this is a major release and it is a breaking change, this means that the prevous version of the code has been changed, for example the function for the breadcrumbs, the lazyload and the carousel, please read the <a href="admin.php?page=italystrap-dashboard">changelog</a> for more information.';
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo $message; ?></p>
        <a class="dismiss-notice" href="?italystrap_notice_plugin_update=0">Hide Notice</a>
        <!-- <button type="button" class="notice-dismiss"> -->
        <!-- <span class="screen-reader-text">Dismiss this notice.</span> -->
    <!-- </button> -->
    </div>
    <?php
}

/**
 * Add the dismiss notice.
 */
function _notice_plugin_update()
{

    if (! isset($_GET['italystrap_notice_plugin_update'])) {
        return null;
    }

    if ('0' !== $_GET['italystrap_notice_plugin_update']) {
        return null;
    }

    add_user_meta(get_current_user_id(), 'italystrap_notice_plugin_update_2', 'true', true);

    /**
     * Maybe this is not the right way to handle this but for now it works.
     * Because otherways fire everytime.
     */
    wp_redirect('plugins.php', 301);
    exit;
}

/**
 * Remove the notice added from the old plugin ItalyStrap
 */
function _remove_italystrap_notice()
{
    remove_action('all_admin_notices', 'italystrap_all_admin_notices_discontinued_plugin', 10);
}
add_action('after_setup_theme', __NAMESPACE__ . '\_remove_italystrap_notice');


/**
 * I don't want to see other notices in my plugin admin page
 * Fuck them all :-)
 */
function hide_update_notice_to_all_but_admin_users()
{
    $current_screen = get_current_screen();

    /**
     * Risolto problema sorto nella pagina si setup di WooCommerce
     * wp-admin/index.php?page=wc-setup
     */
    if (! $current_screen) {
        return;
    }

    if ('acm-by-italystrap_page_italystrap-settings' !== $current_screen->id) {
        return;
    }

    remove_all_actions('admin_notices');
}
add_action('admin_head', __NAMESPACE__ . '\hide_update_notice_to_all_but_admin_users', 1);
