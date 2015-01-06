<?php
/**
 *	Plugin Name:	ItalyStrap
 *	Plugin URI:		http://www.italystrap.it
 *	Description:	Make your website faster. | <a href="admin.php?page=italystrap-documentation">Documentation</a> 
 *	Version:		1.0.0
 *	Author:			Enea Overclokk
 *	Author URI:		http://www.overclokk.net
 *	Text Domain:	italystrap-extended
 *	License:		GPLv2 or later
 *	License URI:	http://www.gnu.org/licenses/gpl-2.0.html
 *	Domain Path:	/lang
 *
 * @package ItalyStrap
 * @version 1.0.0
 */

/**
 * This will make shure the plugin files can't be accessed within the web browser directly.
 */
if ( !defined( 'WPINC' ) )
	die;

/**
 * Define some costant for internal use
 */
if ( !defined( 'ITALYSTRAP_PLUGIN' ) )
	define('ITALYSTRAP_PLUGIN', true);

if ( !defined( 'ITALYSTRAP_FILE' ) )
	define('ITALYSTRAP_FILE', __FILE__ );

if ( !defined( 'ITALYSTRAP_PLUGIN_PATH' ) )
	define('ITALYSTRAP_PLUGIN_PATH', plugin_dir_path( ITALYSTRAP_FILE ));

if ( !defined( 'ITALYSTRAP_BASENAME' ) )
	define('ITALYSTRAP_BASENAME', plugin_basename( ITALYSTRAP_FILE ));

/**
 * Load internationalization for plugin
 */
function ItalyStrap_plugin_text_domain() {

	load_plugin_textdomain( 'ItalyStrap', false, dirname( ITALYSTRAP_BASENAME ) . '/lang' );
}
add_action('init', 'ItalyStrap_plugin_text_domain');

/**
 * Require PHP autoload
 */
require(ITALYSTRAP_PLUGIN_PATH . 'vendor/autoload.php');

/**
 * Load ItalyStrapAdmin() only if is admin
 */
if ( is_admin() ) {

	new ItalyStrapAdmin();
}
// else{
// 	new ItalyStrapPluginInit();
// }

// function prova(){
// 	if ( defined('ITALYSTRAP_THEME') ){
// 		do somethings
// 	}
// }
// add_action( 'after_setup_theme', 'prova' );


// http://codex.wordpress.org/Function_Reference/wp_get_theme
// $my_theme = wp_get_theme( 'ItalyStrap' );
// if ( $my_theme->exists() ) add some code