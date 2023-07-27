<?php

/**
 * Abstract class for the WordPress Settings API
 *
 * This class make simple the plugins admin panel creations,
 * you only have to write the array with the plugin configuration and that's it.
 *
 * @link http://codex.wordpress.org/Adding_Updateistration_Menus
 * @link http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-4-on-theme-options--wp-24902
 *
 * @since 2.2.0
 *
 * @deprecated 4.0.0 Deprecated Abstract Class
 *
 * @package ItalyStrap\Settings
 */

namespace ItalyStrap\Settings;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Update\Validation;
use ItalyStrap\Update\Sanitization;

/**
 * Class for admin area
 */
abstract class Settings_Base implements Settings_Interface
{
    /**
     * Definition of variables containing the configuration
     * to be applied to the various function calls wordpress
     *
     * @var string
     */
    protected $capability = 'manage_options';

    /**
     * Get the current admin page name
     *
     * @var string
     */
    protected $pagenow;

    /**
     * Settings for plugin admin page
     *
     * @var array
     */
    protected $settings = [];

    /**
     * The plugin name
     *
     * @var string
     */
    protected $plugin_slug;

    /**
     * The plugin options
     *
     * @var array
     */
    protected $options = [];

    /**
     * The type of fields to create
     *
     * @var object
     */
    protected $fields_type;

    /**
     *  The array with all sub pages if exist
     *
     * @var array
     */
    protected $submenus = [];

    /**
     * The fields preregistered in the config file.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Enqueue Style and Script
     *
     * @param  string $hook The admin page name (admin.php - tools.php ecc).
     * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
     */
    abstract public function enqueue_admin_style_script($hook);

    /**
     * Add plugin primary page in admin panel
     */
    public function add_menu_page()
    {

        if (! $this->args['menu_page']) {
            return;
        }

        add_menu_page(
            $this->args['menu_page']['page_title'],
            $this->args['menu_page']['menu_title'],
            $this->capability, // $this->args['menu_page']['capability'],
            $this->args['menu_page']['menu_slug'],
            [$this, 'get_settings_view'],
            $this->args['menu_page']['icon_url'],
            $this->args['menu_page']['position']
        );
    }


    /**
     * Add sub menù pages for plugin's admin page
     */
    public function add_sub_menu_page()
    {

        if (! $this->args['submenu_pages']) {
            return;
        }

        foreach ((array) $this->args['submenu_pages'] as $submenu) {
            if (isset($submenu['show_on']) && ! $this->show_on($submenu['show_on'])) {
                continue;
            }

            add_submenu_page(
                $submenu['parent_slug'],
                $submenu['page_title'],
                $submenu['menu_title'],
                $this->capability, // $submenu['capability'],
                $submenu['menu_slug'],
                // $submenu['function_cb']
                [$this, 'get_settings_view']
            );
        }
    }

    /**
     * Show on page
     *
     * @param  string/array $condition The config array.
     * @return bool         Return true if conditions are resolved.
     */
    public function show_on($condition)
    {

        if (is_bool($condition)) {
            return $condition;
        }

        if (is_callable($condition)) {
            return (bool) call_user_func($condition);
        }

        return false;
    }

    /**
     * The add_submenu_page callback
     */
    public function get_settings_view()
    {

        if (! current_user_can($this->capability)) {
            wp_die(esc_attr__('You do not have sufficient permissions to access this page.'));
        }

        $file_path = file_exists($this->args['admin_view_path'] . $this->pagenow . '.php')
            ? $this->args['admin_view_path'] . $this->pagenow . '.php'
            : __DIR__ . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'italystrap-settings.php';

        require($file_path);
    }

    /**
     * Add link in plugin activation panel
     *
     * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
     * @param  array $links Array of link in wordpress dashboard.
     * @return array        Array with my links
     */
    public function plugin_action_links(array $links)
    {

        if (! isset($this->args['plugin_action_links'])) {
            return $links;
        }

        if (! is_array($this->args['plugin_action_links'])) {
            return $links;
        }

        foreach ($this->args['plugin_action_links'] as $link) {
            array_unshift($links, $link);
        }

        return $links;
    }

    /**
     * Add information to the plugin description in plugin.php page
     *
     * @param array  $plugin_meta An array of the plugin's metadata,
     *                            including the version, author,
     *                            author URI, and plugin URI.
     * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
     * @param array  $plugin_data An array of plugin data.
     * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
     *                            'Inactive', 'Recently Activated', 'Upgrade',
     *                            'Must-Use', 'Drop-ins', 'Search'.
     * @return array              Return the new array
     */
    public function plugin_row_meta(array $plugin_meta, $plugin_file, array $plugin_data, $status)
    {

        if (! isset($this->args['basename'])) {
            return $plugin_meta;
        }

        if ($this->args['basename'] !== $plugin_file) {
            return $plugin_meta;
        }

        if (! isset($this->args['plugin_row_meta'])) {
            return $plugin_meta;
        }

        if (! is_array($this->args['plugin_row_meta'])) {
            return $plugin_meta;
        }

        $plugin_meta = array_merge((array) $plugin_meta, (array) $this->args['plugin_row_meta']);

        return $plugin_meta;
    }

    /**
     * Prints out all settings sections added to a particular settings page
     *
     * Part of the Settings API. Use this in a settings page callback function
     * to output all the sections and fields that were added to that $page with
     * add_settings_section() and add_settings_field()
     *
     * @global $wp_settings_sections Storage array of all settings sections added to admin pages
     * @global $wp_settings_fields Storage array of settings fields and info about their pages/sections
     * @since 2.7.0
     *
     * @param string $page The slug name of the page whose settings sections you want to output.
     */
    public function do_settings_sections($page)
    {

        global $wp_settings_sections, $wp_settings_fields;

        if (! isset($wp_settings_sections[ $page ])) {
            return;
        }

        $count = 1;

        foreach ((array) $wp_settings_sections[ $page ] as $section) {
            echo '<div id="tabs-' . $count . '" class="wrap">'; // XSS ok.
            if ($section['title']) {
                echo "<h2>{$section['title']}</h2>\n"; // XSS ok.
            }

            if ($section['callback']) {
                call_user_func($section['callback'], $section);
            }

            if (! isset($wp_settings_fields) || ! isset($wp_settings_fields[ $page ]) || ! isset($wp_settings_fields[ $page ][ $section['id'] ])) {
                continue;
            }
            echo '<table class="form-table">';
            do_settings_fields($page, $section['id']);
            echo '</table>';
            echo '</div>';
            $count++;
        }
    }

    /**
     * Create the nav tabs for section in admin plugin area
     */
    public function create_nav_tab()
    {

        $count = 1;

        $out = '<ul>';

        foreach ($this->settings as $key => $setting) {
            if (isset($setting['show_on']) && false === $setting['show_on']) {
                continue;
            }

            $out .= '<li><a href="#tabs-' . $count . '">' . $setting['tab_title'] . '</a></li>';
            $count++;
        }

        $out .= '</ul>';
        echo $out; // XSS ok.
    }

    /**
     * Init settings for admin area
     */
    public function settings_init()
    {

        // If the theme options doesn't exist, create them.
        $this->add_option();

        foreach ($this->settings as $key => $setting) {
            if (isset($setting['show_on']) && false === $setting['show_on']) {
                continue;
            }

            add_settings_section(
                $setting['id'],
                $setting['title'],
                [$this, $setting['callback']],
                $setting['page']
            );

            foreach ($setting['settings_fields'] as $key2 => $field) {
                if (isset($field['show_on']) && false === $field['show_on']) {
                    continue;
                }

                add_settings_field(
                    $field['id'],
                    $field['title'],
                    [$this, $field['callback']],
                    $field['page'],
                    $field['section'],
                    $field['args']
                );
            }
        }

        $this->register_setting();
    }

    /**
     * Register settings.
     * This allow you to override this method.
     */
    public function register_setting()
    {

        register_setting(
            $this->args['options_group'],
            $this->args['options_name'],
            [$this, 'update']
        );
    }

    /**
     * Sanitize the input data
     *
     * @param  array $instance The input array.
     * @return array           Return the array sanitized
     */
    public function update($instance)
    {

        $this->validation = new Validation();
        $this->sanitization = new Sanitization();

        foreach ($this->fields as $field) {
            if (! isset($instance[ $field['id'] ])) {
                $instance[ $field['id'] ] = '';
            }

            /**
             * Validate fields if $field['validate'] is set
             */
            if (isset($field['validate'])) {
                if (false === $this->validation->validate($field['validate'], $instance[ $field['id'] ])) {
                    $instance[ $field['id'] ] = '';
                }
            }

            if (isset($field['capability']) && true === $field['capability']) {
                $instance[ $field['id'] ] = $instance[ $field['id'] ];
            } elseif (isset($field['sanitize'])) {
                $instance[ $field['id'] ] = $this->sanitization->sanitize($field['sanitize'], $instance[ $field['id'] ]);
            } else {
                $instance[ $field['id'] ] = strip_tags($instance[ $field['id'] ]);
            }
        }

        return $instance;
    }

    /**
     * Render section CB
     *
     * @param  array $args The arguments for section CB.
     */
    public function render_section_cb($args)
    {

        echo $args['callback'][0]->settings[ $args['id'] ]['desc'] ?? ''; // XSS ok.
    }

    /**
     * Get the field type
     *
     * @param  array $args Array with arguments.
     */
    public function get_field_type(array $args)
    {

        /**
         * Set field id and name
         */
        $args['_id'] = $args['_name'] = $this->args['options_name'] . '[' . $args['id'] . ']';

        echo $this->fields_type->get_field_type($args, $this->options); // XSS ok.
    }

    /**
     * Get the plugin fields
     *
     * @return array The plugin fields
     */
    public function get_settings_fields()
    {

        $fields = [];
        foreach ((array) $this->settings as $settings_value) {
            foreach ($settings_value['settings_fields'] as $fields_key => $fields_value) {
                $fields[ $fields_value['id'] ] = $fields_value['args'];
            }
        }

        return $fields;
    }

    /**
     * Get admin settings default value in an array
     *
     * @return array The new array with default options
     */
    public function get_plugin_settings_array_default()
    {

        $default_settings = [];

        foreach ((array) $this->fields as $key => $setting) {
            $default_settings[ $key ] = $setting['default'] ?? '';
        }

        return $default_settings;
    }

    /**
     * Add option
     */
    public function add_option()
    {

        if (false === get_option($this->args['options_name'])) {
            $default = $this->get_plugin_settings_array_default();
            add_option($this->args['options_name'], $default);
            $this->set_theme_mods((array) $default);
        }
    }

    /**
     * Delete option
     */
    public function delete_option()
    {

        delete_option($this->args['options_name']);
        $this->remove_theme_mods($this->get_plugin_settings_array_default());
    }

    /**
     * Set theme mods
     *
     * @param  array $value The options array with value.
     */
    public function set_theme_mods(array $value = [])
    {

        foreach ((array) $this->fields as $key => $field) {
            if (isset($field['option_type']) && 'theme_mod' === $field['option_type']) {
                set_theme_mod($key, $value[ $key ]);
            }
        }
    }

    /**
     * Remove theme mods
     *
     * @param  array $value The options array with value.
     */
    public function remove_theme_mods(array $value = [])
    {

        foreach ((array) $this->fields as $key => $field) {
            if (isset($field['option_type']) && 'theme_mod' === $field['option_type']) {
                remove_theme_mod($key);
            }
        }
    }

    /**
     * Save options in theme_mod
     *
     * @param  string $option    The name of the option.
     * @param  mixed  $old_value The old options.
     * @param  mixed  $value     The new options.
     *
     * @return string            The name of the option.
     */
    public function save($option, $old_value, $value)
    {

        if (! isset($this->args['options_name'])) {
            return $option;
        }

        if ($option !== $this->args['options_name']) {
            return $option;
        }

        $this->set_theme_mods((array) $value);

        return $option;
    }
}
