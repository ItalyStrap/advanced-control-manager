<?php

declare(strict_types=1);

namespace ItalyStrap\Core;

/**
 * This will make shure the plugin files can't be accessed within the web browser directly.
 */

if (! defined('WPINC')) {
    die;
}

/**
 * @return bool
 */
if (! function_exists(__NAMESPACE__ . '\is_dev')) {
    function is_dev(): bool
    {
        return (bool) defined('ITALYSTRAP_DEV') && ITALYSTRAP_DEV;
    }
}

/**
 * @return bool
 */
if (! function_exists(__NAMESPACE__ . '\is_beta')) {
    function is_beta(): bool
    {
        return (bool) defined('ITALYSTRAP_BETA') && ITALYSTRAP_BETA;
    }
}

/**
 * @return bool
 */
if (! function_exists(__NAMESPACE__ . '\is_debug')) {
    function is_debug(): bool
    {
        return (bool) defined('WP_DEBUG') && WP_DEBUG;
    }
}

/**
 * @return bool
 */
if (! function_exists(__NAMESPACE__ . '\is_script_debug')) {
    function is_script_debug(): bool
    {
        return (bool) defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;
    }
}

/**
 * @return bool
 */
if (! function_exists(__NAMESPACE__ . '\is_p2p_register_connection_type_exists')) {
    function is_p2p_register_connection_type_exists(): bool
    {
        return (bool) function_exists('p2p_register_connection_type');
    }
}

/**
 * @todo also try this wp_get_theme( 'italystrap' )->exists()
 *
 * @return bool
 */
if (! function_exists(__NAMESPACE__ . '\is_italystrap_active')) {
    function is_italystrap_active(): bool
    {
        return 'italystrap' === wp_get_theme()->template;
    }
}
