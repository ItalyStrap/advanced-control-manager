<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @package   ItalyStrap
 * @author    Enea Overclokk
 * @license   GPL-2.0+
 * @link      http://www.italystrap.it
 */

/**
 * If uninstall not called from WordPress, then exit
 */
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * To read:
 * http://wordpress.stackexchange.com/questions/25910/uninstall-activate-deactivate-a-plugin-typical-features-how-to
 * https://premium.wpmudev.org/blog/activate-deactivate-uninstall-hooks/
 */

if (is_multisite()) {
    global $wpdb;

    $blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);

    /**
     * In case of transient
     * delete_transient( 'italystrap_settings' );
     */

    delete_option('italystrap_settings');
    delete_option('italystrap_widget_area');

    if ($blogs) {
        foreach ($blogs as $blog) {
            switch_to_blog($blog['blog_id']);

            /**
             * In case of transient
             * delete_transient( 'italystrap_settings' );
             * Delete all custom post type created by plugin
             */

            delete_option('italystrap_settings');
            delete_option('italystrap_widget_area');
            // delete_post_meta_by_key( '_italystrap_layout_settings' );

            restore_current_blog();
        }
    }
} else {

    /**
     * In case of transient
     * delete_transient( 'italystrap_settings' );
     * Delete all custom post type created by plugin
     */

    delete_option('italystrap_settings');
    delete_option('italystrap_widget_area');
    // delete_post_meta_by_key( '_italystrap_layout_settings' );
}
