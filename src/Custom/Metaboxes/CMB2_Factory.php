<?php

/**
 * CMB2 Loader
 *
 * [Long Description.]
 *
 * @link [URL]
 * @since [x.x.x (if available)]
 *
 * @package [Plugin/Theme/Etc]
 */

namespace ItalyStrap\Custom\Metaboxes;

if (! defined('ITALYSTRAP_PLUGIN') or ! ITALYSTRAP_PLUGIN) {
    die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * CMB2_Factory API
 */
class CMB2_Factory implements Subscriber_Interface
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
            'cmb2_admin_init'   => 'autoload',
        ];
    }

    protected $configs = [];

    /**
     * Autoload CMB2
     */
    public function autoload()
    {

        $this->configs = (array) apply_filters('italystrap_cmb2_configurations_array', $this->configs);

        foreach ($this->configs as $config) {
            $this->cmb = new CMB2_Loader($config);
            $this->cmb->load();
        }
    }
}
