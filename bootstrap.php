<?php
/**
 * Init API: Init Class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Core;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

use ItalyStrap\Widgets\Widget_Factory;
use ItalyStrap\Shortcodes\Shortcode_Factory;
use ItalyStrap\Blocks\Block_Factory;

/**
 * Initialize the DIC
 *
 * @var Auryn\Injector
 */
$injector = new \Auryn\Injector;

$args = require( ITALYSTRAP_PLUGIN_PATH . 'admin/config/plugin.php' );
$injector->defineParam( 'args', $args );

/**
 * Get the plugin and theme options
 */
$theme_mods = (array) get_theme_mods();
$options = (array) get_option( $args['options_name'] );
$options = wp_parse_args( $options, get_default_from_config( require( ITALYSTRAP_PLUGIN_PATH . 'admin/config/options.php' ) ) );

$prefix_coonfig = array(
	'prefix'	=> 'italystrap',
	'_prefix'	=> '_italystrap',
	'prefix_'	=> 'italystrap_',
);

/**
 * Define theme_mods  and options parmeter
 * @deprecated 2.5.0 Use the Config object instead.
 */
$injector->defineParam( 'theme_mods', $theme_mods );
$injector->defineParam( 'options', $options );

/**=======================
 * Autoload Shared Classes
 *======================*/
$autoload_sharing = array(
	'ItalyStrap\Config\Config',
	'ItalyStrap\Excerpt\Excerpt',
);

/**=============================
 * Autoload Classes definitions
 *============================*/
$fields_type = array( 'fields_type' => 'ItalyStrap\Fields\Fields' );
$autoload_definitions = array(
	'ItalyStrap\Widgets\Attributes\Attributes'	=> $fields_type,
	'ItalyStrap\Settings\Settings'				=> $fields_type,
	'ItalyStrap\Import_Export\Import_Export'	=> $fields_type,
	'ItalyStrapAdminGallerySettings'			=> $fields_type,
	'ItalyStrap\Config\Config'					=> array( ':config' => array_merge( $options, $theme_mods, $prefix_coonfig ) ),
);

/**======================
 * Autoload Aliases Class
 *=====================*/
$autoload_aliases = array(
	'ItalyStrap\Config\Config_Interface'	=> 'ItalyStrap\Config\Config',
	// 'ItalyStrap\Fields\Fields_Interface'	=> 'ItalyStrap\Fields\Fields',
);

/**=============================
 * Autoload Concrete Classes
 * with option check
 * @see _init & _init_admin
 =============================*/
$autoload_subscribers = array(
	'widget_areas'			=> 'ItalyStrap\Widgets\Areas\Areas',
	'widget_attributes'		=> 'ItalyStrap\Widgets\Attributes\Attributes',
);

/**=============================
 * Autoload Concrete Classes
 * with option check
 * @see _init & _init_admin
 =============================*/
// $autoload_concretes = array(
// 	// 'ItalyStrap\Customizer\Customizer_Register',
// );

if ( defined( 'ITALYSTRAP_BETA' ) ) {
	$autoload_subscribers[] = 'ItalyStrap\Customizer\Customizer_Register';
}

foreach ( $autoload_sharing as $class ) {
	$injector->share( $class );
}
foreach ( $autoload_definitions as $class_name => $class_args ) {
	$injector->define( $class_name, $class_args );
}
foreach ( $autoload_aliases as $interface => $implementation ) {
	$injector->alias( $interface, $implementation );
}

/**
 * The new events manager in ALPHA version.
 *
 * @var Event_Manager
 */
$event_manager = $injector->make( 'ItalyStrap\Event\Manager' );
$events_manager = $event_manager; // Deprecated $events_manager.

/**
 * Register widgets, shortcodes and bloks
 */
$event_manager->add_subscriber( new Widget_Factory( $options, $injector ) );
$event_manager->add_subscriber( new Shortcode_Factory( $options, $injector ) );
// $event_manager->add_subscriber( new Block_Factory( $options, $injector ) );

/**
 * Adjust priority to make sure this runs
 */
add_action( 'init', function () {
	/**
	 * Load po file
	 */
	load_plugin_textdomain( 'italystrap', false, dirname( ITALYSTRAP_BASENAME ) . '/lang' );
}, 100 );

// if ( defined( 'ITALYSTRAP_BETA' ) ) {

	/**
	 * WooCommerce enqueue style
	 *
	 * @link https://docs.woothemes.com/document/css-structure/
	 */
	// function dequeue_unused_styles( $enqueue_styles ) {

	// 	if ( is_cart() || is_checkout() || is_account_page() ) {

	// 		return $enqueue_styles;
	// 	} else {

	// 		return false;
	// 	}
	// }
	// add_filter( 'woocommerce_enqueue_styles', __NAMESPACE__ . '\dequeue_unused_styles' );

	// Or just remove them all in one line.
	// add_filter( 'woocommerce_enqueue_styles', '__return_false' );
// }
// 

$autoload_plugin_files_init = array(
	'/_init.php',
	'/_init-admin.php',
);

foreach ( $autoload_plugin_files_init as $file ) {
	require( __DIR__ . $file );
}

$app = array(
	'sharing'				=> $autoload_sharing,
	'aliases'				=> $autoload_aliases,
	'definitions'			=> $autoload_definitions,
	'define_param'			=> array(),
	'delegations'			=> array(),
	'preparations'			=> array(),
	// 'concretes'				=> $autoload_concretes,
	// 'options_concretes'		=> $autoload_options_concretes,
	'subscribers'			=> $autoload_subscribers,
);

/**================================
 * Now load the plugin application
 *
 * @var ItalyStrap\Plugin\Loader
 =================================*/
$italystrap_plugin = new \ItalyStrap\Plugin\Loader( $injector, $event_manager, $app, $options );
add_action( 'after_setup_theme', array( $italystrap_plugin, 'load' ), 10 );
