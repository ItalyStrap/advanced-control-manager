<?php

/**
 * Provide Import and Export of the settings of the plugin.
 *
 * This is a fork from
 * https://github.com/Mte90/WordPress-Plugin-Boilerplate-Powered of Mte90
 *
 * @since 2.2.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Import_Export;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Import Export Class
 */
class Import_Export extends Import_Export_Base implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked italystrap_after_settings_page - 10
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            // 'hook_name'                          => 'method_name',
            'italystrap_after_settings_page'    => 'get_view',
            'admin_init'                        => 'onAdminInit',
        ];
    }

    public function onAdminInit()
    {
        $this->export();
        $this->import();
    }

    /**
     * Process a settings export from config
     *
     * @since    2.2.0
     */
    public function export()
    {

        if (! $this->is_allowed('export')) {
            return;
        }

        foreach ((array) $this->args['options_names'] as $options_name) {
            $this->settings[ $options_name ] = get_option($options_name);
        }

        ignore_user_abort(true);

        nocache_headers();

        /**
         * date_default_timezone_set('UTC');
         */
        header('Content-Type: application/json; charset=utf-8');
        header(
            sprintf(
                'Content-Disposition: attachment; filename=%s-%s.json',
                $this->args['filename'],
                $this->get_date_time_string()
            )
        );
        header('Expires: 0');

        if (is_callable('wp_json_encode')) {
            echo wp_json_encode($this->settings, JSON_PRETTY_PRINT);
            exit;
        }

        echo json_encode($this->settings, JSON_THROW_ON_ERROR);
        exit;
    }

    /**
     * Process a settings import from a json file
     *
     * @since    2.2.0
     */
    public function import()
    {

        if (! $this->is_allowed('import')) {
            return;
        }

        /**
         * Get the extension of import file
         *
         * @link http://stackoverflow.com/a/19831453 Strict standards: Only variables should be passed by reference
         * @var string
         */
        $file_name = $_FILES[ $this->args['import_file'] ]['name']; // WPCS: input var okay.
        $exploded = explode('.', $file_name);
        $extension = end($exploded);

        /**
         * If it is not json than die
         */
        if ('json' !== $extension) {
            wp_die(
                $this->i18n['no_json_file']['message'],
                $this->i18n['no_json_file']['title'],
                ['back_link' => true]
            );
        }

        /**
         * If the file is empty than die
         */
        if (0 === $_FILES[ $this->args['import_file'] ]['size']) { // WPCS: input var okay.
            wp_die(
                $this->i18n['zero_size']['message'],
                $this->i18n['zero_size']['title'],
                ['back_link' => true]
            );
        }

        $import_file = $_FILES[ $this->args['import_file'] ]['tmp_name']; // WPCS: input var okay.

        /**
         * If $import_file is empty or null than die
         */
        if (empty($import_file)) {
            wp_die(
                $this->i18n['no_file']['message'],
                $this->i18n['no_file']['title'],
                ['back_link' => true]
            );
        }

        /**
         * Retrieve the settings from the file and convert the json object to an array.
         *
         * @var array
         */
        $settings = (array) json_decode(file_get_contents($import_file), null, 512, JSON_THROW_ON_ERROR);

        foreach ((array) $this->args['options_names'] as $options_name) {
            update_option($options_name, get_object_vars($settings[ $options_name ]));
        }

        $url = isset($_SERVER['HTTP_REFERER']) ? esc_url($_SERVER['HTTP_REFERER']) : network_admin_url(); // WPCS: input var okay.

        wp_safe_redirect($url);
        exit;
    }
}
