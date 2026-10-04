<?php

declare(strict_types=1);

/**
 *  Plugin Name:       Advanced Control Manager for WordPress by ItalyStrap
 *  Plugin URI:        https://italystrap.com/
 *  Description:       Essential tool with an array of utility for WordPress. Always make a backup before upgrading.
 *  Version:           3.0.1
 *  Requires at least: 6.0
 *  Requires PHP:      8.2
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

namespace ItalyStrap;

if (\did_action('italystrap_plugin_loaded') > 0) {
    return;
}
require_once __DIR__ . '/bootstrap.php';
