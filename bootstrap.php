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
$options = wp_parse_args( $options, \ItalyStrap\Core\get_default_from_config( require( ITALYSTRAP_PLUGIN_PATH . 'admin/config/options.php' ) ) );

/**
 * Define theme_mods  and options parmeter
 * @deprecated 2.5.0 Use the Config object instead.
 */
$injector->defineParam( 'theme_mods', $theme_mods );
$injector->defineParam( 'options', $options );

/**============================================
 * Autoload Classes that they have to be shared
 *===========================================*/
$autoload_sharing = array(
	'ItalyStrap\Config\Config'
);

/**=============================
 * Autoload Classes definitions
 =============================*/
$fields_type = array( 'fields_type' => 'ItalyStrap\Fields\Fields' );
$autoload_definitions = array(
	'ItalyStrap\Widgets\Attributes\Attributes'	=> $fields_type,
	'ItalyStrap\Settings\Settings'				=> $fields_type,
	'ItalyStrap\Import_Export\Import_Export'	=> $fields_type,
	'ItalyStrapAdminGallerySettings'			=> $fields_type,
	'ItalyStrap\Config\Config'					=> array( ':config' => array_merge( $options, $theme_mods ) ),
);

/**======================
 * Autoload Aliases Class
 ======================*/
$aliases = array(
	'ItalyStrap\Config\Config_Interface'	=> 'ItalyStrap\Config\Config',
);


foreach ( $autoload_sharing as $class ) {
	$injector->share( $class );
}
foreach ( $autoload_definitions as $class_name => $class_args ) {
	$injector->define( $class_name, $class_args );
}
foreach ( $aliases as $interface => $implementation ) {
	$injector->alias( $interface, $implementation );
}

/**=============================
 * Autoload Concrete Classes
 * @see _init & _init_admin
 =============================*/
$autoload_concrete = array(
	'widget_areas'			=> 'ItalyStrap\Widgets\Areas\Areas',
);

/**
 * Instantiate Config
 */
// $injector->make( 'ItalyStrap\Config\Config' );

/**
 * The new events manager in ALPHA version.
 *
 * @var Event_Manager
 */
$event_manager = $injector->make( 'ItalyStrap\Event\Manager' );
$events_manager = $event_manager; // Deprecated $events_manager.

/**
 * Register widgets and shortcodes
 */
$event_manager->add_subscriber( new Widget_Factory( $options, $injector ) );
$event_manager->add_subscriber( new Shortcode_Factory( $options, $injector ) );

if ( defined( 'ITALYSTRAP_BETA' ) ) {

	/**
	 * Instantiate Customizer_Manager Class
	 * Questa deve essere eseguita sia in admin che in front-end
	 *
	 * @var Customizer_Manager
	 */
	$customizer_manager = $injector->make( 'ItalyStrap\Customizer\Customizer_Register' );
	$event_manager->add_subscriber( $customizer_manager );

	/**
	 * WooCommerce enqueue style
	 *
	 * @link https://docs.woothemes.com/document/css-structure/
	 */
	function dequeue_unused_styles( $enqueue_styles ) {

		if ( is_cart() || is_checkout() || is_account_page() ) {

			return $enqueue_styles;
		} else {

			return false;
		}
	}
	// add_filter( 'woocommerce_enqueue_styles', __NAMESPACE__ . '\dequeue_unused_styles' );

	// Or just remove them all in one line.
	// add_filter( 'woocommerce_enqueue_styles', '__return_false' );
}
