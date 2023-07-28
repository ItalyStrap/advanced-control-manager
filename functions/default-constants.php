<?php

/**
 * Set default constants
 *
 * @package ItalyStrap
 * @since   2.1.0
 */

if (! function_exists('italystrap_set_default_constant')) {

    /**
     * Set default constant for the plugin.
     *
     * @param  string $_file __FILE__ for the root directory of ACM by ItalyStrap.
     */
    function italystrap_set_default_constant($_file, $prefix = '')
    {

        /**
         * Define some costant for internal use
         */
        // if ( ! defined( 'ITALYSTRAP_PLUGIN' ) ) {
        //  define( 'ITALYSTRAP_PLUGIN', true );
        // }

        /**
         * Define some costant for internal use
         */
        if (! defined('ITALYSTRAP_PLUGIN_VERSION')) {
            define('ITALYSTRAP_PLUGIN_VERSION', '2.16.0');
        }

        /**
         * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended\italystrap.php
         */
        // if ( ! defined( 'ITALYSTRAP_FILE' ) ) {
        //  define( 'ITALYSTRAP_FILE', $_file );
        // }

        /**
         * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended/
         */
        // if ( ! defined( 'ITALYSTRAP_PLUGIN_PATH' ) ) {
        //  define( 'ITALYSTRAP_PLUGIN_PATH', plugin_dir_path( $_file ) );
        // }

        /**
         * Example: 'http://192.168.1.10/italystrap/wp-content/plugins/italystrap-extended/'
         */
        // if ( ! defined( 'ITALYSTRAP_PLUGIN_URL' ) ) {
        //  define( 'ITALYSTRAP_PLUGIN_URL', plugin_dir_url( $_file ) );
        // }

        /**
         * Example = italystrap-extended/italystrap.php
         */
        // if ( ! defined( 'ITALYSTRAP_BASENAME' ) ) {
        //  define( 'ITALYSTRAP_BASENAME', plugin_basename( $_file ) );
        // }

        /**
         * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended/
         */
        // if ( ! defined( 'ITALYSTRAP_CONFIG_PATH' ) ) {
        //  define( 'ITALYSTRAP_CONFIG_PATH', ITALYSTRAP_PLUGIN_PATH . 'config/' );
        // }

        /**
         * Define Bog Name constant
         */
        // if ( ! defined( 'GET_BLOGINFO_NAME' ) ) {
        //  define( 'GET_BLOGINFO_NAME', get_option( 'blogname' ) );
        // }

        /**
         * Define Blog Description Constant
         */
        // if ( ! defined( 'GET_BLOGINFO_DESCRIPTION' ) ) {
        //  define( 'GET_BLOGINFO_DESCRIPTION', get_option( 'blogdescription' ) );
        // }

        /**
         * Define HOME_URL
         */
        // if ( ! defined( 'HOME_URL' ) ) {
        //  define( 'HOME_URL', get_home_url( null, '/' ) );
        // }

        /**
         * Define DIRECTORY_SEPARATOR
         */
        // if ( ! defined( 'DS' ) ) {
        //  define( 'DS', DIRECTORY_SEPARATOR );
        // }

        $plugin_dir_path = plugin_dir_path($_file);

        $default_constants = ['ITALYSTRAP_PLUGIN'         => true, 'ITALYSTRAP_FILE'           => $_file, 'ITALYSTRAP_PLUGIN_PATH'    => $plugin_dir_path, 'ITALYSTRAP_PLUGIN_URL'     => plugin_dir_url($_file), 'ITALYSTRAP_BASENAME'       => plugin_basename($_file), 'ITALYSTRAP_CONFIG_PATH'    => $plugin_dir_path . 'config/', 'ITALYSTRAP_OPTIONS_NAME'   => 'italystrap_settings', 'GET_BLOGINFO_NAME'         => get_option('blogname'), 'GET_BLOGINFO_DESCRIPTION'  => get_option('blogdescription'), 'HOME_URL'                  => get_home_url(null, '/'), 'DS'                        => DIRECTORY_SEPARATOR];

        italystrap_define_constants($default_constants);
    }
}

if (! function_exists('italystrap_define_constants')) {
    /**
     * Define constant from a configuration array
     *
     * @param  array $constants An array with constant key value to generate.
     */
    function italystrap_define_constants(array $constants = [])
    {
        foreach ($constants as $name => $value) {
            if (! defined($name)) {
                define($name, $value);
            }
        }
    }
}
