<?php

/**
 * Shortcode_Factory API
 *
 * Instantiate the Shortcodes.
 *
 * @link www.italystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Shortcodes;

if (! defined('ITALYSTRAP_PLUGIN') or ! ITALYSTRAP_PLUGIN) {
    die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Shortcode_Factory
 */
class Shortcode_Factory implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked 'init' - 10
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return array(
            // 'hook_name'              => 'method_name',
            'after_setup_theme' => array(
                'function_to_add'   => 'register',
            ),
        );
    }

    /**
     * The plugin's options
     *
     * @var string
     */
    private $options = '';

    /**
     * List of all widget classes name.
     *
     * @var array
     */
    private $shortcodes_list = array();

    /**
     * Injector object
     *
     * @var null
     */
    private $injector = null;

    /**
     * Fire the construct
     */
    public function __construct(array $options = array(), $injector = null)
    {
        $this->options = $options;
        $this->injector = $injector;

        $this->shortcodes_list = array(
            'shortcode_row'         => 'ItalyStrap\\Shortcodes\\Row',
            'shortcode_column'      => 'ItalyStrap\\Shortcodes\\Column',
            'shortcode_posts'       => 'ItalyStrap\\Shortcodes\\Posts',
            'shortcode_post_title'  => 'ItalyStrap\\Shortcodes\\Post_Title',
            'shortcode_button'      => 'ItalyStrap\\Shortcodes\\Button',
        );
    }

    /**
     * Add action to widget_init
     * Initialize widget
     */
    public function register()
    {

        foreach ((array) $this->shortcodes_list as $option_name => $class_name) {
            if (empty($this->options[ $option_name ])) {
                continue;
            }

            $shortcode_name = str_replace('shortcode_', '', $option_name);
            $$shortcode_name =  $this->injector->make($class_name);
            add_shortcode($shortcode_name, array( $$shortcode_name, 'render' ));
            if (function_exists('\shortcode_ui_register_for_shortcode')) {
                shortcode_ui_register_for_shortcode($shortcode_name, $$shortcode_name->shortcode_ui);
            }
        }
    }
}
