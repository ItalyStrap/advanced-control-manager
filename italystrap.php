<?php
/**
 *	Plugin Name:	ItalyStrap
 *	Plugin URI:		http://www.italystrap.it
 *	Description:	Make your website more powerfull. | <a href="admin.php?page=italystrap-documentation">Documentation</a>
 *	Version:		1.3.3
 *	Author:			Enea Overclokk
 *	Author URI:		http://www.overclokk.net
 *	Text Domain:	ItalyStrap
 *	License:		GPLv2 or later
 *	License URI:	http://www.gnu.org/licenses/gpl-2.0.html
 *	Domain Path:	/lang
 *
 * @package ItalyStrap
 * @since 1.0.0
 */

/**
 * This will make shure the plugin files can't be accessed within the web browser directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define some costant for internal use
 */
if ( ! defined( 'ITALYSTRAP_PLUGIN' ) ) {
	define( 'ITALYSTRAP_PLUGIN', true );
}

/**
 * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended\italystrap.php
 */
if ( ! defined( 'ITALYSTRAP_FILE' ) ) {
	define( 'ITALYSTRAP_FILE', __FILE__ );
}

/**
 * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended/
 */
if ( ! defined( 'ITALYSTRAP_PLUGIN_PATH' ) ) {
	define( 'ITALYSTRAP_PLUGIN_PATH', plugin_dir_path( ITALYSTRAP_FILE ) );
}

/**
 * Example: 'http://192.168.1.10/italystrap/wp-content/plugins/italystrap-extended/'
 */
if ( ! defined( 'ITALYSTRAP_PLUGIN_URL' ) ) {
	define( 'ITALYSTRAP_PLUGIN_URL', plugin_dir_url( ITALYSTRAP_FILE ) );
}

/**
 * Example = italystrap-extended/italystrap.php
 */
if ( ! defined( 'ITALYSTRAP_BASENAME' ) ) {
	define( 'ITALYSTRAP_BASENAME', plugin_basename( ITALYSTRAP_FILE ) );
}

/**
 * Require PHP autoload
 */
require( ITALYSTRAP_PLUGIN_PATH . 'vendor/autoload.php' );

/**
 * Load CMB2
 */
require( ITALYSTRAP_PLUGIN_PATH . 'vendor/webdevstudios/cmb2/init.php' );

/**
 * Load general function before init
 */
require( ITALYSTRAP_PLUGIN_PATH . 'functions/general-functions.php' );

/**
 * Init the plugin
 */
require( ITALYSTRAP_PLUGIN_PATH . 'class-init.php' );

/**
 * Require Debug file, this file is only for internal development
 */
if ( defined( 'ITALYSTRAP_DEV' ) ) {
	require( ITALYSTRAP_PLUGIN_PATH . 'debug/debug.php' );
}
