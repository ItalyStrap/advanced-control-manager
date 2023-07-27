<?php

/**
 * i18n Configuration file
 *
 * This are the default config for i18n
 *
 * @link [URL]
 * @since 2.2.0
 *
 * @package Italystrap\Import_Export
 */

return ['no_json_file'  => ['message'   => esc_attr__('Please upload a valid .json file', 'italystrap'), 'title'     => esc_attr__('No valid json file', 'italystrap')], 'zero_size' => ['message'   => esc_attr__('The json file can\'t be empty', 'italystrap'), 'title'     => esc_attr__('Empty file', 'italystrap')], 'no_file'   => ['message'   => esc_attr__('Please upload a file to import', 'italystrap'), 'title'     => esc_attr__('No file import', 'italystrap')], 'export'    => ['title'     => esc_attr__('Export Settings', 'italystrap'), 'desc'      => __('Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'italystrap')], 'import'    => ['title'     => esc_attr__('Import Settings', 'italystrap'), 'desc'      => __('Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'italystrap')]];
