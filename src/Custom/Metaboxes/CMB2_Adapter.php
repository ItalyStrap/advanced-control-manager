<?php

/**
 * Various metaboxes for this plugin
 *
 * In this class I add some metaboxes to use in various place of the plugin
 * It uses CMB2 for that functionality
 *
 * @since 2.0.0
 * @deprecated 2.8.0 Deprecated, use 'italystrap_cmb2_configurations_array'
 *             filter to load the CMB2 configuration array
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Custom\Metaboxes;

if (! defined('ITALYSTRAP_PLUGIN') or ! ITALYSTRAP_PLUGIN) {
    die();
}

use CMB2;

/**
 * Add some metaboxes in admin area with CMB2
 */
class CMB2_Adapter extends CMB2_Loader
{
    /**
     * Merge config in configs
     *
     * @param  array $config An array with a configuration for CMB2
     * @return string        [description]
     */
    public function merge(array $config)
    {
        $this->configs[] = $config;
    }

    /**
     * Autoload CMB2
     */
    public function autoload()
    {

        foreach ($this->configs as $config) {
            $this->load_cmb2($config);
        }
    }

    /**
     * Load CMB2
     *
     * @param  array $config Configuration array for CMB2
     */
    public function load_cmb2(array $config)
    {

        /**
         * This prevents that the CMB2 autoload the fields itself.
         */
        $fields = $config['fields'];
        unset($config['fields']);

        $this->cmb = new_cmb2_box(array_merge($this->config_default, $config));

        $this->generate_fields($this->cmb, $fields);
    }
}
