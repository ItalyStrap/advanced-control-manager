<?php

/**
 *  Plugin Name:       Advanced Control Manager for WordPress by ItalyStrap
 *  Plugin URI:        https://italystrap.com/
 *  Description:       Essential tool with an array of utility for WordPress. Always make a backup before upgrading.
 *  Version:           2.16.0
 *  Requires at least: 6.0
 *  Requires PHP:      7.4
 *  Author:            Enea Overclokk
 *  Author URI:        https://www.overclokk.net
 *  Text Domain:       italystrap
 *  License:           GPLv2 or later
 *  License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 *  Domain Path:       /lang
 *
 * @package ItalyStrap
 * @since 1.0.0
 */

require __DIR__ . '/vendor/overclokk/minimum-requirements/minimum-requirements.php';

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
$requirements = new Minimum_Requirements('7.4', '6.0', 'ACM - Advanced Control Manager');

/**
 * Check compatibility on install
 * If is not compatible on install print an admin_notice
 */
register_activation_hook(__FILE__, [$requirements, 'check_compatibility_on_install']);

/**
 * If it is already installed and activated check
 * if example new version is compatible, if is not don't load plugin code and print admin_notice
 * This part need more test
 */
if (! $requirements->is_compatible_version()) {
    add_action('admin_notices', [$requirements, 'load_plugin_admin_notices']);
    return;
}

/**
 * Init plugin default constant
 */
require(__DIR__ . '/functions/default-constants.php');
italystrap_set_default_constant(__FILE__, 'ITALYSTRAP');

$autoload_plugin_files = [
    '/vendor/autoload.php',
    '/vendor/cmb2/cmb2/init.php',
    '/functions/autoload.php',
    '/bootstrap.php',
    '/functions/edd.php',
];

if (did_action('italystrap_plugin_loaded') > 0) {
    $autoload_plugin_files = [];
}

foreach ($autoload_plugin_files as $file) {
    require(__DIR__ . $file);
}

/**
 * Fires once ItalyStrap plugin has loaded.
 *
 * @since 2.0.0
 */
do_action('italystrap_plugin_loaded', null);

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
 * @deprecated 4.0.0 Do not use this hook
 *
 * @var array
 */
$plugin_files_path = apply_filters('italystrap_require_plugin_files_path', []);

if (! empty($plugin_files_path)) {
    foreach ((array) $plugin_files_path as $key => $plugin_file_path) {
        if (! file_exists($plugin_file_path)) {
            continue;
        }
        require($plugin_file_path);
    }
    /**
     * Fires once ItalyStrap Child plugin has loaded.
     *
     * @since 2.0.0
     */
    do_action('italystrap_child_plugin_loaded', null);
}

/**
 * To do
 *
 * @todo Agganciare init all'azione plugin_loaded
 *      (forse plugin_loaded è troppo presto, valutare se usare init direttamente)
 *      che in questo modo sarà possibile eventualmente fare un remove_actions se necessario
 *      (normalmente con plugin premium)
 */
