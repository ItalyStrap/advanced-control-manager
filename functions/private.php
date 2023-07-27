<?php

/**
 * Private functions
 *
 * All the functionality in this page are meant only for internal purpose, do not use they.
 *
 * @link italystrap.com
 * @since 4.0.0
 *
 * @package Italystrap\Core
 */

namespace ItalyStrap\Core;

/**
 * This will make shure the plugin files can't be accessed within the web browser directly.
 */
if (! defined('WPINC')) {
    die;
}

/**
 * This shortcode is only for intarnal use
 *
 * @param  array $atts Shortcode attributes.
 *
 * @return string      The shortcode output
 */
function _display_config($atts)
{

    if (! isset($atts['file'])) {
        return;
    }

    $file_path = ITALYSTRAP_PLUGIN_PATH . 'config/' . $atts['file'] . '.php';

    if (! file_exists($file_path)) {
        return __('No file founded', 'italystrap');
    }

    $get_settings = require($file_path);

    $output = '<ul class="list-unstyled">';
    $output .= sprintf(
        '<h3>%s</h3>',
        __('Options available:', 'italystrap')
    );

    foreach ((array) $get_settings as $key => $setting) {
        $output .= sprintf(
            '<li><h4>%s</h4><p>Attribute: <code>%s</code><br>Default: <code>%s</code><br>%s</p></li>',
            esc_attr($setting['label']),
            esc_attr($setting['id']),
            empty($setting['default']) ? __('empty', 'italystrap') : esc_attr($setting['default']),
            wp_kses_post($setting['desc'])
        );
    }

    $output .= '</ul>';

    return $output;
}

add_shortcode('display_config', __NAMESPACE__ . '\_display_config');

/**
 * This shortcode is only for intarnal use
 *
 * @param  array $atts Shortcode attributes.
 *
 * @return string      The shortcode output
 */
function _display_options($atts)
{

    if (! isset($atts['file'])) {
        return '<!-- The file name is not set -->';
    }

    $file_path = ITALYSTRAP_PLUGIN_PATH . 'admin/config/' . $atts['file'] . '.php';

    if (! file_exists($file_path)) {
        return __('No file founded', 'italystrap');
    }

    $get_settings = require($file_path);

    $output = '';
    foreach ($get_settings as $section => $settings) {
        $output .= '<h2>' . ucfirst(esc_html($section)) . '</h2>';
        $output .= '<ul class="">';
        foreach ($settings['settings_fields'] as $setting) {
            // d( $setting['show_on'] );
            $output .= sprintf(
                '<li><h4>%s</h4><p>%s</p></li>',
                esc_attr($setting['args']['name']),
                wp_kses_post($setting['args']['desc'])
            );
        }
        $output .= '</ul>';
    }

    return $output;
}

add_shortcode('display_options', __NAMESPACE__ . '\_display_options');

/**
 * This shortcode is only for intarnal use
 *
 * @param  array $atts Shortcode attributes.
 *
 * @return string      The shortcode output
 */
function _display_example($atts)
{

    if (! isset($atts['file'])) {
        return;
    }

    if (! file_exists(ITALYSTRAP_PLUGIN_PATH . $atts['file'] . '.md')) {
        return __('No file founded', 'italystrap');
    }

    static $parsedown = null;

    if (! $parsedown) {
        $parsedown = new \Parsedown();
    }

    $get_example = \ItalyStrap\Core\get_file_content(ITALYSTRAP_PLUGIN_PATH . $atts['file'] . '.md');

    return $parsedown->text($get_example);
}

add_shortcode('display_example', __NAMESPACE__ . '\_display_example');
