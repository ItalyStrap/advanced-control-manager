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
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( is_multisite() ) {

	global $wpdb;

	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );

	/**
	 * In case of transient
	 * delete_transient( 'italystrap_settings' );
	 */

	delete_option( 'italystrap_settings' );

	if ( $blogs ) {
		foreach ( $blogs as $blog ) {

			switch_to_blog( $blog['blog_id'] );

			/**
			 * In case of transient
			 * delete_transient( 'italystrap_settings' );
			 */

			delete_option( 'italystrap_settings' );

			restore_current_blog();

		}
	}
} else {

	/**
	 * In case of transient
	 * delete_transient( 'italystrap_settings' );
	 */

	delete_option( 'italystrap_settings' );


}
