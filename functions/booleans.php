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
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Is dev enviroment
 *
 * @return bool Return true if ITALYSTRAP_DEV constant is declared
 */
function is_dev() {
	return (bool) defined( 'ITALYSTRAP_DEV' ) && ITALYSTRAP_DEV;
}

/**
 * Is Beta version
 *
 * @return bool Return true if ITALYSTRAP_BETA version is declared
 */
function is_beta() {
	return (bool) defined( 'ITALYSTRAP_BETA' ) && ITALYSTRAP_BETA;
}

/**
 * Is Beta version
 *
 * @return bool Return true if ITALYSTRAP_BETA version is declared
 */
function is_debug() {
	return (bool) defined( 'WP_DEBUG' ) && WP_DEBUG;
}

/**
 * Is Beta version
 *
 * @return bool Return true if ITALYSTRAP_BETA version is declared
 */
function is_script_debug() {
	return (bool) defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
}

/**
 * is_p2p_register_connection_type_exists
 *
 * @return bool Return true if is_p2p_register_connection_type_exists
 */
function is_p2p_register_connection_type_exists() {
	return (bool) function_exists( 'p2p_register_connection_type' );
}

/**
 * Is ItalyStrap Framework active
 *
 * @todo also try this wp_get_theme( 'italystrap' )->exists()
 *
 * @return bool Return true if the ItalyStrap theme framweork is active
 */
function is_italystrap_active() {
	return 'italystrap' === wp_get_theme()->template;
}
