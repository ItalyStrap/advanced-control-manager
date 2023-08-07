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
        $plugin_dir_path = plugin_dir_path($_file);

        $default_constants = [
            'ITALYSTRAP_PLUGIN'         => true,
            'ITALYSTRAP_FILE'           => $_file,
            'ITALYSTRAP_PLUGIN_PATH'    => $plugin_dir_path,
            'ITALYSTRAP_PLUGIN_URL'     => plugin_dir_url($_file),
            'ITALYSTRAP_BASENAME'       => plugin_basename($_file),
            'ITALYSTRAP_CONFIG_PATH'    => $plugin_dir_path . 'config/',
            'ITALYSTRAP_OPTIONS_NAME'   => 'italystrap_settings',
            'GET_BLOGINFO_NAME'         => get_option('blogname'),
            'GET_BLOGINFO_DESCRIPTION'  => get_option('blogdescription'),
            'HOME_URL'                  => get_home_url(null, '/'),
            'DS'                        => DIRECTORY_SEPARATOR
        ];

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
            if (!defined($name)) {
                define($name, $value);
            }
        }
    }
}
