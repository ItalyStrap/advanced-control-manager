<?php

/**
 * Class for admin panel
 *
 * This class add some functions for use in admin panel
 *
 * @link http://codex.wordpress.org/Adding_Administration_Menus
 * @link http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-4-on-theme-options--wp-24902
 *
 * @since 2.3.0
 *
 * @package ItalyStrap\Settings
 */

namespace ItalyStrap\Settings;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

/**
 * Class for admin area
 */
class Uninstall extends Settings_Base
{
    /**
     * Initialize Class
     *
     * @param array            $options     Get the plugin options.
     * @param FieldsInterface $fields_type The Fields object.
     */
    public function __construct(array $options = [], array $settings, array $args, FieldsInterface $fields_type)
    {

        parent::__construct($options, $settings, $args, $fields_type);
    }
}
