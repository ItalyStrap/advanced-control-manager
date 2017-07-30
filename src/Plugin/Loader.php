<?php
/**
 * Plugin Loader API
 *
 * This class is the main loading for the plugin
 *
 * @link [URL]
 * @since 2.8
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Plugin;

use \Auryn\Injector;
use ItalyStrap\Event\Manager;

/**
 * Loader Class
 */
class Loader {

	/**
	 * Flag to track if the plugin is loaded.
	 *
	 * @var bool
	 */
	private $loaded;

	/**
	 * Auryn PHP container
	 *
	 * @var Injector
	 */
	private $injector;

	/**
	 * Event Manager
	 *
	 * @var Manager
	 */
	private $event_manager;

	/**
	 * Array with all the classes registered for the initialization of the plugin.
	 *
	 * @var array
	 */
	private $app;

	/**
	 * Plugin config
	 *
	 * @var array
	 */
	private $config;

	/**
	 * Init the plugin
	 *
	 * @param Injector $injector      Auryn Injector.
	 * @param Manager  $event_manager Event Manager System.
	 * @param array    $app           Class appuration array.
	 * @param array    $config        Plugin configuration.
	 */
	function __construct( Injector $injector, Manager $event_manager, array $app = array(), array $config = array() ) {

		$this->injector = $injector;
		$this->event_manager = $event_manager;
		$this->config = $config;

		$default = array(
			'sharing'				=> array(),
			'aliases'				=> array(),
			'definitions'			=> array(),
			'define_param'			=> array(),
			'delegations'			=> array(),
			'preparations'			=> array(),
			'concretes'				=> array(),
			'options_concretes'		=> array(),
			'subscribers'			=> array(),
		);

		$this->app = array_merge( $default, $app );

		$this->loaded = false;
	}

	/**
	 * Init
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function init( array $app ) {
		foreach ( $app['sharing'] as $class ) {
			$this->injector->share( $class );
		}
		foreach ( $app['aliases'] as $interface => $implementation ) {
			$this->injector->alias( $interface, $implementation );
		}
		foreach ( $app['definitions'] as $class_name => $class_args ) {
			$this->injector->define( $class_name, $class_args );
		}
		foreach ( $app['define_param'] as $param_name => $param_args ) {
			$this->injector->defineParam( $param_name, $param_args );
		}
		foreach ( $app['delegations'] as $param => $callable ) {
			$this->injector->delegate( $param, $callable );
		}
		foreach ( $app['preparations'] as $class => $callable ) {
			$this->injector->prepare( $class, $callable );
		}
	}

	/**
	 * Load method
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function load() {

		if ( $this->loaded ) {
			return;
		}

		$app = $this->get_app();

		$this->init( $app );

		foreach ( $app['concretes'] as $concrete ) {
			$this->event_manager->add_subscriber( $this->injector->make( $concrete ) );
		}
		foreach ( $app['options_concretes'] as $option_name => $concrete ) {
			if ( empty( $this->config[ $option_name ] ) ) {
				continue;
			}
			$this->event_manager->add_subscriber( $this->injector->make( $concrete ) );
		}

		foreach ( $app['subscribers'] as $option_name => $subscriber ) {

			if ( is_int( $option_name ) ) {
				$this->event_manager->add_subscriber( $this->injector->make( $subscriber ) );
				continue;
			}
			
			if ( empty( $this->config[ $option_name ] ) ) {
				continue;
			}

			$this->event_manager->add_subscriber( $this->injector->make( $subscriber ) );
		}

		$this->loaded = true;

		/**
		 * Fires once ItalyStrap plugin has loaded.
		 *
		 * @since 2.0.0
		 */
		do_action( 'italystrap_plugin_app_loaded' );
	}

	/**
	 * Get Shared Classes
	 *
	 * @return array  An array with shared classes
	 */
	private function get_app() {
		$this->app = (array) apply_filters( 'italystrap_app', $this->app );
		return $this->app;
	}

	/**
	 * Get Shared Classes
	 *
	 * @return array  An array with shared classes
	 */
	private function get( $key ) {
		return (array) apply_filters( "italystrap_{$key}", $this->app[ $key ] );
	}
}
