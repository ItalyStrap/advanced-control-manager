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

use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Config\ConfigInterface;

/**
 * Add some metaboxes in admin area with CMB2
 */
class Register_Metaboxes implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked 'wp_footer' - 20
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            // 'hook_name'                          => 'method_name',
            'italystrap_cmb2_configurations_array'  => 'register',
        ];
    }

    /**
     * CMB prefix
     *
     * @var string
     */
    private $prefix;

    /**
     * CMB _prefix
     *
     * @var string
     */
    private $_prefix;

    /**
     * The plugin config
     *
     * @var Config
     */
    private $config = [];

    /**
     * Init the constructor
     *
     * @param array $config The plugin config
     */
    function __construct(ConfigInterface $config)
    {

        $this->config = $config->all();

        /**
         * Start with an underscore to hide fields from custom fields list
         *
         * @var string
         */
        $this->prefix = $this->config['prefix'];

        $this->_prefix = $this->config['_prefix'];
    }

    /**
     * Register the configurations for CMB2
     *
     * @param  array  $configs The array with the configurations for CMB2.
     *
     * @return array           An array with all configurations
     */
    public function register(array $cmb2_configs)
    {

        if (! empty($this->config['activate_custom_css'])) {
            $cmb2_configs[] = (array) require(__DIR__ . '/config/custom-script-style.php');
        }

        return $cmb2_configs;
    }

    /**
     * Register metaboxes in the widget areas
     * Per ora lo faccio partire direttamente dal tema, file bootstrap.php
     * Appena la classe per gestire la widget_area Widget/Area viene caricata
     * su after_setup_theme allora si può commentare il contenuto e fare un return
     */
    public function register_widget_areas_fields()
    {

        $cmb2_configs = (array) require(ITALYSTRAP_PLUGIN_PATH . '/src/Widgets/Areas/config/metaboxes.php');

        // ddd( $cmb2_configs );

        $this->cmb = new CMB2_Adapter();
        $this->cmb->merge($cmb2_configs);
        $this->cmb->autoload();
        return;
    }

    /**
     * Register new fields for add custom css, ID and class in every post/page
     */
    public function register_style_fields_in_wp_editor($configs)
    {

        $script_settings_metabox_object_types = apply_filters('italystrap_script_settings_metabox_object_types', ['post', 'page']);

        /**
         * Sample metabox to demonstrate each field type included
         */
        // $cmb = new_cmb2_box(
        //  array(
        //      'id'            => $this->prefix . '-script-settings-metabox',
        //      'title'         => __( 'CSS and JavaScript settings', 'italystrap' ),
        //      'object_types'  => $script_settings_metabox_object_types,
        //      'context'       => 'normal',
        //      'priority'      => 'high',
        //  )
        // );

        // $cmb->add_field(
        //  array(
        //      'name'          => __( 'Custom CSS', 'italystrap' ),
        //      'desc'          => __( 'This code will be included verbatim in style tag before </head> tag of your page or post', 'italystrap' ),
        //      'id'            => $this->_prefix . '_custom_css_settings',
        //      'type'          => 'textarea_code',
        //      'attributes'    => array( 'placeholder' => 'body{background-color:#f2f2f2}' ),
        //  )
        // );

        // $cmb->add_field(
        //  array(
        //      'name'          => __( 'Body Classes', 'italystrap' ),
        //      'desc'          => __( 'These class names will be added to the body_class() function (provided your theme uses these functions), separate each one by comma.', 'italystrap' ),
        //      'id'            => $this->_prefix . '_custom_body_class_settings',
        //      'type'          => 'text',
        //      'attributes'    => array( 'placeholder' => 'class1,class2,class3,otherclass' ),
        //  )
        // );

        // $cmb->add_field(
        //  array(
        //      'name'          => __( 'Post Classes', 'italystrap' ),
        //      'desc'          => __( 'These class names will be added to the post_class() function (provided your theme uses these functions), separate each one by comma.', 'italystrap' ),
        //      'id'            => $this->_prefix . '_custom_post_class_settings',
        //      'type'          => 'text',
        //      'attributes'    => array( 'placeholder' => 'class1,class2,class3,otherclass' ),
        //  )
        // );

        /**
         * @todo Aggiungere selezione di script o stili già registrati che
         *       si vuole aggiungere al post o pagine e anche globalmente
         *       global $wp_scripts, $wp_styles;
         */
    }
}
