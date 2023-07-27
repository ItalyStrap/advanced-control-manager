<?php

/**
 * Factory class for Blocks
 *
 * Factory class for registering all Blocks
 *
 * @link https://github.com/WordPress/gutenberg
 * @link https://github.com/nylen/gutenberg-examples
 * @link http://gutenberg-devdoc.surge.sh/
 * @since [x.x.x (if available)]
 *
 * @package [Plugin/Theme/Etc]
 */

namespace ItalyStrap\Blocks;

if (! defined('ITALYSTRAP_PLUGIN') or ! ITALYSTRAP_PLUGIN) {
    die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Block_Factory
 */
class Block_Factory implements Subscriber_Interface
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

        return [
            // 'hook_name'              => 'method_name',
            'after_setup_theme' => ['function_to_add'   => 'register'],
            'enqueue_block_editor_assets'   => 'enqueue',
        ];
    }

    /**
     * The plugin's options
     *
     * @var string
     */
    private $options = '';

    /**
     * List of all widget classes name.
     */
    private array $blocks_list = [];

    /**
     * Injector object
     *
     * @var null
     */
    private $injector = null;

    /**
     * Fire the construct
     */
    public function __construct(array $options = [], $injector = null)
    {
        $this->options = $options;
        $this->injector = $injector;

        $this->blocks_list = ['block_posts'           => \ItalyStrap\Blocks\Posts::class];
    }

    /**
     * Enqueue script
     */
    public function enqueue()
    {
        wp_enqueue_script(
            'italystrap-posts',
            plugins_url('index.build.js', __FILE__),
            ['wp-blocks', 'wp-element', 'wp-api'],
            random_int(0, mt_getrandmax()),
            true
        );
    }

    /**
     * Add action to widget_init
     * Initialize widget
     */
    public function register()
    {

        if (! function_exists('register_block_type')) {
            return;
        }

        foreach ((array) $this->blocks_list as $option_name => $class_name) {
            // if ( empty( $this->options[ $option_name ] ) ) {
            //  continue;
            // }

            $block_name = str_replace('block_', '', $option_name);

            /**
             * Block object
             */
            ${$block_name} =  $this->injector->make(
                $class_name,
                [
                    ':block_type'   => "italystrap/{$block_name}",
                    ':args'         => [
                //                      'render_callback' => '', // Defined in abstract Block::class
                        'attributes'    => [
                //                          'orderBy'         => [
                //                              'type'      => 'string',
                //                              'default'   => 'date',
                //                          ],
                            'exclude_current_post'         => [
                                'type'      => 'boolean',
                                'default'   => 'false',
                            ],
                            'show_thumbnail'         => [
                                'type'      => 'boolean',
                                'default'   => 'false',
                            ],
                        ],
                    ],
                ]
            );

            /**
             * We don't need to pass the string name and args for the block because
             * we just passed the obj to the first argument.
             */
            register_block_type(${$block_name});

//          register_block_type_from_metadata(  );
        }
    }
}
