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

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Block_Factory
 */
class Block_Factory implements Subscriber_Interface {

	/**
	 * Returns an array of hooks that this subscriber wants to register with
	 * the WordPress plugin API.
	 *
	 * @hooked 'init' - 10
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {

		return array(
			// 'hook_name'				=> 'method_name',
			'after_setup_theme'	=> array(
				'function_to_add'	=> 'register',
			),
			'enqueue_block_editor_assets'	=> 'enqueue',
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
	private $blocks_list = array();

	/**
	 * Injector object
	 *
	 * @var null
	 */
	private $injector = null;

	/**
	 * Fire the construct
	 */
	public function __construct( array $options = array(), $injector = null ) {
		$this->options = $options;
		$this->injector = $injector;

		$this->blocks_list = array(
			'block_posts'			=> 'ItalyStrap\\Blocks\\Posts',
		);
	}

	/**
	 * Enqueue script
	 */
	public function enqueue() {
		wp_enqueue_script(
			'italystrap-posts',
			plugins_url( 'index.build.js', __FILE__ ),
			array( 'wp-blocks', 'wp-element', 'wp-api' )
		);
	}

	/**
	 * Add action to widget_init
	 * Initialize widget
	 */
	public function register() {

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		foreach ( (array) $this->blocks_list as $option_name => $class_name ) {
			// if ( empty( $this->options[ $option_name ] ) ) {
			// 	continue;
			// }

			$block_name = str_replace( 'block_', '', $option_name );
			$$block_name =  $this->injector->make( $class_name );

			register_block_type(
				"italystrap/{$block_name}",
				array(
					'render_callback' => array( $$block_name, 'render' ),
				)
			);
		}
	}
}
