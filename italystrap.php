<?php
/**
 *	Plugin Name:	ItalyStrap
 *	Plugin URI:		http://www.italystrap.it
 *	Description:	Make your website more powerfull. | <a href="admin.php?page=italystrap-documentation">Documentation</a> 
 *	Version:		1.0.2
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
if ( !defined( 'WPINC' ) )
	die;

/**
 * Define some costant for internal use
 */
if ( !defined( 'ITALYSTRAP_PLUGIN' ) )
	define('ITALYSTRAP_PLUGIN', true);

/**
 * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended\italystrap.php
 */
if ( !defined( 'ITALYSTRAP_FILE' ) )
	define('ITALYSTRAP_FILE', __FILE__ );

/**
 * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended/
 */
if ( !defined( 'ITALYSTRAP_PLUGIN_PATH' ) )
	define('ITALYSTRAP_PLUGIN_PATH', plugin_dir_path( ITALYSTRAP_FILE ));
/**
 * Example = italystrap-extended/italystrap.php
 */
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

// require(ITALYSTRAP_PLUGIN_PATH . 'classes/prova.php');

/**
 * Load ItalyStrapAdmin() only if is admin
 */
if ( is_admin() ) {

	new ItalyStrapAdmin;
	new ItalyStrapAdminMediaSettings;
	new ItalyStrapAdminGallerySettings;
}
// else{
// 	new ItalyStrapPluginInit();
// }


/**
 * Load ItalyStrapCarouselLoader only if [gallery] shortcode exist
 */
function italystrap_load_carousel_shortcode() { //http://wordpress.stackexchange.com/questions/103549/wp-deregister-register-and-enqueue-dequeue

	$post = get_post();
	$gallery = false;

	if( isset($post->post_content) && has_shortcode( $post->post_content, 'gallery') )
	        $gallery = true; // http://dannyvankooten.com/3935/only-load-contact-form-7-scripts-when-needed/

	if( !$gallery )
	    new ItalyStrapCarouselLoader();

}
// adjust priority to make sure this runs
add_action( 'init', 'italystrap_load_carousel_shortcode', 100 );

/**
 * Instatiate Mobile_Detect class for responive use
 * @todo Passare l'istanza dentro la classe http://stackoverflow.com/a/10634148
 * @var obj
 */
$detect = new Mobile_Detect;


// function prova(){
// 	if ( defined('ITALYSTRAP_THEME') ){
// 		do somethings
// 	}
// }
// add_action( 'after_setup_theme', 'prova' );


// http://codex.wordpress.org/Function_Reference/wp_get_theme
// $my_theme = wp_get_theme( 'ItalyStrap' );
// if ( $my_theme->exists() ) add some code
// 
// 

// add_action( 'wp_footer', 'display_priority', 999 );
// function display_priority(){
// 	var_dump(ItalyStrapGlobals::get());
// }

/**
 * @link http://wordpress.stackexchange.com/questions/162862/wordpress-hooks-run-sequence
 * @link http://codex.wordpress.org/Plugin_API/Action_Reference
 * wp_footer (1)
 * wp_print_footer_scripts (1)
 * shutdown (1) 
 */
// add_action( 'shutdown', function(){
//     foreach( $GLOBALS['wp_actions'] as $action => $count )
//         printf( '%s (%d) <br/>' . PHP_EOL, $action, $count );

// });