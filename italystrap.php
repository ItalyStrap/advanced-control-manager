<?php
/**
 *	Plugin Name:	ItalyStrap
 *	Plugin URI:		http://www.italystrap.it
 *	Description:	Make your web site more powerful with ItalyStrap. (Requires PHP 5.3 >= and Developers skills). The version 2 is a new and complete rebuild of this plugin. Always make a backup before upgrading.
 *	Version:		2.4.0
 *	Author:			Enea Overclokk
 *	Author URI:		http://www.overclokk.net
 *	Text Domain:	italystrap
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
 * Define some costant for internal use
 */
if ( ! defined( 'ITALYSTRAP_PLUGIN_VERSION' ) ) {
	define( 'ITALYSTRAP_PLUGIN_VERSION', '2.4.0' );
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
 * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended/
 */
if ( ! defined( 'ITALYSTRAP_CONFIG_PATH' ) ) {
	define( 'ITALYSTRAP_CONFIG_PATH', ITALYSTRAP_PLUGIN_PATH . 'config/' );
}

/**
 * Define Bog Name constant
 */
if ( ! defined( 'GET_BLOGINFO_NAME' ) ) {
	define( 'GET_BLOGINFO_NAME', get_option( 'blogname' ) );
}

/**
 * Define Blog Description Constant
 */
if ( ! defined( 'GET_BLOGINFO_DESCRIPTION' ) ) {
	define( 'GET_BLOGINFO_DESCRIPTION', get_option( 'blogdescription' ) );
}

/**
 * Define HOME_URL
 */
if ( ! defined( 'HOME_URL' ) ) {
	define( 'HOME_URL', get_home_url( null, '/' ) );
}

require( ITALYSTRAP_PLUGIN_PATH . 'vendor/overclokk/minimum-requirements/minimum-requirements.php' );

/**
 * Instantiate the class
 *
 * @param string $php_ver The minimum PHP version.
 * @param string $wp_ver  The minimum WP version.
 * @param string $name    The name of the theme/plugin to check.
 * @param array  $plugins Required plugins format plugin_path/plugin_name.
 *
 * @var Minimum_Requirements
 */
$requirements = new Minimum_Requirements( '5.3', '4' );

/**
 * Check compatibility on install
 * If is not compatible on install print an admin_notice
 */
register_activation_hook( __FILE__, array( $requirements, 'check_compatibility_on_install' ) );

/**
 * If it is already installed and activated check if example new version is compatible, if is not don't load plugin code and prin admin_notice
 * This part need more test
 */
if ( ! $requirements->is_compatible_version() ) {

	add_action( 'admin_notices', array( $requirements, 'load_plugin_admin_notices' ) );
	return;

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

$args = require( ITALYSTRAP_PLUGIN_PATH . 'admin/config/plugin.php' );

$injector->defineParam( 'args', $args );

/**
 * Get the plugin options
 *
 * @var array
 */
$options = (array) get_option( $args['options_name'] );

$options = wp_parse_args( $options, \ItalyStrap\Core\get_default_from_config( require( ITALYSTRAP_PLUGIN_PATH . 'admin/config/options.php' ) ) );

/**
 * Define options parmeter
 */
$injector->defineParam( 'options', $options );

/**
 * Get the theme mods
 *
 * @var array
 */
$theme_mods = (array) get_theme_mods();

/**
 * Define theme_mods parmeter
 */
$injector->defineParam( 'theme_mods', $theme_mods );

/**
 * The new events manager in ALPHA vesrion.
 *
 * @var Event_Manager
 */
$events_manager = $injector->make( '\ItalyStrap\Event\Manager' );

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
	add_action( 'plugins_loaded', function () use ( $injector ) {
		require( ITALYSTRAP_PLUGIN_PATH . 'debug/debug.php' );
	}, 9999 );
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
 * @example
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
 *
 * @example
 * Another example:
 * add_filter( 'italystrap_require_plugin_files_path', function ( $files_path ) {
 * 
 *     $files_path[] = PLUGIN_PATH . 'bootstrap.php';
 * 
 *     return $files_path;
 * } );
 *
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
		if ( ! file_exists( $plugin_file_path ) ) {
			continue;
		}
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
 * To do
 *
 * @todo Agganciare init all'azione plugin_loaded (forse plugin_loaded è troppo presto, valutare se usare init direttamente) che in questo modo sarà possibile eventualmente fare un remove_actions se necessario (normalmente con plugin premium)
 */
