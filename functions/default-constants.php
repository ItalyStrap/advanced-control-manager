<?php
/**
 * Set default constants
 *
 * @package ItalyStrap
 * @since   2.1.0
 */

namespace ItalyStrap\Core;

/**
 * Set default constant for the plugin.
 */
public function set_plugin_default_constant() {

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
		define( 'ITALYSTRAP_PLUGIN_VERSION', '2.0.0' );
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

}
