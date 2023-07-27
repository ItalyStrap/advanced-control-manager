<?php

/**
 * Class for admin panel
 *
 * This class add some functions for use in admin panel
 *
 * @link http://codex.wordpress.org/Adding_Administration_Menus
 * @link http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-4-on-theme-options--wp-24902
 *
 * @todo Maybe add_settings_error()
 *
 * @since 1.0.0
 *
 * @package ItalyStrap\Settings
 */

namespace ItalyStrap\Settings;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Fields\FieldsInterface;
use ItalyStrap\Update\Validation;
use ItalyStrap\Update\Sanitization;
use ItalyStrap\I18N\Translator;
use ItalyStrap\Event\Subscriber_Interface;

/**
 * Class for admin area
 */
class Settings implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked update_option - 10
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            // 'hook_name'                          => 'method_name',
            'update_option' => ['function_to_add'   => 'save', 'accepted_args'     => 3],
            'plugins_loaded'    => 'init',
        ];
    }



    /**
     * Definition of variables containing the configuration
     * to be applied to the various function calls wordpress
     *
     * @var string
     */
    protected $capability;

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
     * Initialize Class
     *
     * @param array            $options        Get the plugin options.
     * @param array            $settings       The configuration array plugin fields.
     * @param array            $args           The configuration array for plugin.
     * @param array            $theme_mods     The theme options.
     * @param FieldsInterface $fields_type    The Fields object.
     */
    public function __construct(array $options, array $settings, array $args, array $theme_mods, FieldsInterface $fields_type)
    {

        if (isset($_GET['page'])) { // Input var okay.
            $this->pagenow = wp_unslash($_GET['page']); // Input var okay.
        }

        $this->settings = $settings;

        $this->options = $options;

        $this->args = $args;

        $this->fields_type = $fields_type;

        $this->fields = $this->get_settings_fields();

        $this->theme_mods = $theme_mods;

        $this->capability = $args['capability'];
    }

    /**
     * Initialize admin area with those hooks
     */
    public function init()
    {

        /**
         * Add Admin menù page
         */
        add_action('admin_menu', [$this, 'add_menu_page']);

        add_action('admin_init', [$this, 'settings_init']);

        /**
         * Load script for ItalyStrap\Admin
         */
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_style_script']);

        /**
         * Add link in plugin activation panel
         */
        add_filter('plugin_action_links_' . ITALYSTRAP_BASENAME, [$this, 'plugin_action_links']);

        add_filter('plugin_row_meta', [$this, 'plugin_row_meta'], 10, 4);

        add_action('italystrap_after_settings_form', [$this, 'get_aside']);
    }

    /**
     * Get Aside for settings page
     */
    public function get_aside()
    {

        require($this->args['admin_view_path'] . 'italystrap-aside.php');
    }

    /**
     * Add style for ItalyStrap admin page
     *
     * @param  string $hook The admin page name (admin.php - tools.php ecc).
     * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
     */
    public function enqueue_admin_style_script($hook)
    {

        if ('italystrap-settings' === $this->pagenow) {
            wp_enqueue_script(
                $this->pagenow,
                plugins_url('js/' . $this->pagenow . '.min.js', __FILE__),
                ['jquery-ui-tabs', 'jquery-form'],
                false,
                false,
                true
            );

            wp_enqueue_style(
                $this->pagenow,
                plugins_url('css/' . $this->pagenow . '.css', __FILE__)
            );
        }
    }

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

        $this->add_sub_menu_page($this->args['submenu_pages']);
    }


    /**
     * Add sub menù pages for plugin's admin page
     */
    public function add_sub_menu_page(array $submenu_pages)
    {

        if (! $submenu_pages) {
            return;
        }

        foreach ((array) $submenu_pages as $submenu) {
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

        foreach ($this->settings as $setting) {
            if (isset($setting['show_on']) && false === $setting['show_on']) {
                continue;
            }

            add_settings_section(
                $setting['id'],
                $setting['title'],
                [$this, 'render_section_cb'], //array( $this, $field['callback'] ),
                $this->args['options_group'] //$setting['page']
            );

            foreach ($setting['settings_fields'] as $field) {
                if (isset($field['show_on']) && false === $field['show_on']) {
                    continue;
                }

                if (empty($field['args']['label'])) {
                    $field['args']['label_for'] = $this->get_field_id($field['args']['id']);
                }

                add_settings_field(
                    $field['id'],
                    $field['title'],
                    [$this, 'get_field_type'], //array( $this, $field['callback'] ),
                    $this->args['options_group'], //$field['page'],
                    $setting['id'],
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
        $this->translator = new Translator('ItalyStrap');

        foreach ($this->fields as $field) {
            if (! isset($instance[ $field['id'] ])) {
                $instance[ $field['id'] ] = '';
            }

            /**
             * Register string for translation
             */
            if (isset($field['translate']) && true === $field['translate']) {
                $this->translator->register_string($field['id'], strip_tags($instance[ $field['id'] ]));
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
     * Constructs id attributes for use in Settings::class fields.
     *
     * @param string $field_name Field name.
     *
     * @return string ID attribute for `$field_name`.
     */
    public function get_field_id($field_name)
    {
        return $this->args['options_name'] . '[' . trim($field_name) . ']';
    }

    /**
     * Get the field type
     *
     * @param  array $args Array with arguments.
     */
    public function get_field_type(array $args)
    {

        $args['_id'] = $args['_name'] = $this->get_field_id($args['id']);
        echo $this->fields_type->render($args, $this->options); // XSS ok.
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
}
