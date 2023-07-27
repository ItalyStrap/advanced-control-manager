<?php

/**
 * Various metaboxes for this plugin
 *
 * In this class I add some metaboxes to use in various place of the plugin
 * It uses CMB2 for that functionality
 *
 * @since 2.0.0
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
class CMB2_Loader
{
    protected $cmb = null;

    /**
     * The plugin options
     *
     * @var array
     */
    protected $options = [];

    protected $config = [];

    protected $default = ['name'      => '', 'desc'      => '', 'id'        => '', 'type'      => 'text', 'default'   => '', 'sanitize'  => 'sanitize_text_field', 'show_on'   => true];

    protected $config_default = ['id'            => '', 'title'         => '', 'object_types'  => [], 'context'       => 'normal', 'priority'      => 'low'];

    /**
     * Init the constructor
     *
     * @param array $options The plugin options
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Load CMB2
     *
     * @param  array $config Configuration array for CMB2
     */
    public function load()
    {

        /**
         * This prevents that the CMB2 autoload the fields itself.
         */
        $fields = $this->config['fields'];
        unset($this->config['fields']);

        $this->cmb = new_cmb2_box(array_merge($this->config_default, $this->config));

        $this->generate_fields($this->cmb, $fields);
    }

    /**
     * Generate fields
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function generate_fields(CMB2 $cmb, array $fields)
    {

        foreach ($fields as $field) {
            $field = $this->add_field($field);

            if (isset($field['group'])) {
                foreach ($field['group'] as $group_field) {
                    $this->add_group_field($field, $group_field);
                }
            }
        }
    }

    /**
     * Add fields
     *
     * @param  array $fields An array with fields configuration.
     */
    public function add_field(array $field)
    {

        $field = array_merge($this->default, $field);

        if (! $field['show_on']) {
            return false;
        }

        $this->cmb->add_field($field);
    }

    /**
     * Add group fields
     *
     * @param  string $id     The parent field id of the group field to add the field.
     * @param  array  $fields An array with fields configuration.
     */
    public function add_group_field($id, array $field)
    {

        $field = array_merge($this->default, $field);

        if (! $field['show_on']) {
            return false;
        }

        $this->cmb->add_group_field($id, $field);
    }
}
