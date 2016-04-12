<?php
/**
 * ItalyStrap init file
 *
 * Init the plugin front-end functionality
 *
 * @link www.italystrap.com
 * @since 4.0.0
 *
 * @package ItalyStrap
 */


namespace ItalyStrap\Core;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

if ( is_admin() ) {
	return;
}

$injector = new \Auryn\Injector;

/**
 * Get the plugin options
 *
 * @var array
 */
$options = (array) get_option( 'italystrap_settings' );

/**
 * Define options parmeter
 */
$injector->defineParam( 'options', $options );
