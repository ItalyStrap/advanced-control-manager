<?php
/**
 *	Plugin Name:	ItalyStrap
 *	Plugin URI:		http://www.italystrap.it
 *	Description:	Make your website more powerfull. | <a href="admin.php?page=italystrap-documentation">Documentation</a>
 *	Version:		2.0.0-beta.1
 *	Author:			Enea Overclokk
 *	Author URI:		http://www.overclokk.net
 *	Text Domain:	ItalyStrap
 *	License:		GPLv2 or later
 *	License URI:	http://www.gnu.org/licenses/gpl-2.0.html
 *	Domain Path:	/lang
 *	Git URI: https://github.com/overclokk/italystrap-extended
 *	GitHub Plugin URI: https://github.com/overclokk/italystrap-extended
 *	GitHub Branch: master
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
 * Initialize the IOC
 *
 * @var Auryn\Injector
 */
$injector = new \Auryn\Injector;

$args = require( ITALYSTRAP_PLUGIN_PATH . 'admin/settings/settings-general-plugin.php' );

$injector->defineParam( 'args', $args );

/**
 * Get the plugin options
 *
 * @var array
 */
$options = (array) get_option( $args['options_name'] );

/**
 * Define options parmeter
 */
$injector->defineParam( 'options', $options );

if ( defined( 'ITALYSTRAP_BETA' ) ) {
	/**
	 * Instantiate Customizer_Manager Class
	 * Questa deve essere eseguita sia in admin che in front-end
	 *
	 * @var Customizer_Manager
	 */
	$customizer_manager = $injector->make( 'ItalyStrap\Admin\Customizer_Manager' );
	add_action( 'customize_register', array( $customizer_manager, 'register' ), 11 );
}

/**
 * Init the plugin
 */
require( ITALYSTRAP_PLUGIN_PATH . 'global-init.php' );

/**
 * Init the plugin
 */
require( ITALYSTRAP_PLUGIN_PATH . 'init.php' );

/**
 * Init the plugin
 */
require( ITALYSTRAP_PLUGIN_PATH . 'admin-init.php' );

/**
 * Require Debug file, this file is only for internal development
 */
if ( defined( 'ITALYSTRAP_DEV' ) ) {
	require( ITALYSTRAP_PLUGIN_PATH . 'debug/debug.php' );
}

/**
 * Fires once ItalyStrap plugin has loaded.
 *
 * @since 2.0.0
 */
do_action( 'italystrap_plugin_loaded', $injector );

/**
 * This filter is used to load your php file right after ItalyStrap plugin is loaded.
 * The purpose is to have al code in the same scope without using global
 * with variables provided from this plugin.
 *
 * Usage example:
 *
 * 1 - First of all you have to have the file/files with some code
 *     that extending this plugins functionality in your plugin path.
 * 2 - Than you have to activate your plugin.
 * 3 - And then see the below example.
 *
 * add_filter( 'italystrap_require_plugin_files_path', 'add_your_files_path' );
 *
 * function add_your_files_path( array $arg ) {
 *     return array_merge(
 *                  $arg,
 *                  array( plugin_dir_path( __FILE__ ) . 'my-dir/my-file.php' )
 *     );
 * }
 * Important:
 * Remeber that the file you want to load just after ItalyStrap plugin
 * has not to be required/included from your plugin because
 * you will get an error 'You can't redeclare...'.
 *
 * @since 2.0.0
 *
 * @var array
 */
$plugin_files_path = apply_filters( 'italystrap_require_plugin_files_path', array() );

if ( ! empty( $plugin_files_path ) ) {
	foreach ( (array) $plugin_files_path as $key => $plugin_file_path ) {
		require( $plugin_file_path );
	}
	/**
	 * Fires once ItalyStrap Child plugin has loaded.
	 *
	 * @since 2.0.0
	 */
	do_action( 'italystrap_child_plugin_loaded', $injector );
}

/**
 * @todo Agganciare init all'azione plugin_loaded (forse plugin_loaded è troppo presto, valutare se usare init direttamente) che in questo modo sarà possibile eventualmente fare un remove_actions se necessario (normalmente con plugin premium)
 */
