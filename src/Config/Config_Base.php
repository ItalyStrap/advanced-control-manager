<?php
/**
 * Config Class that handle the classes configuration
 *
 * [Long Description.]
 *
 * @link [URL]
 * @since 2.4.2
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Config;

use \ArrayObject;

/**
 * Config Class
 */
class Config_Base extends ArrayObject implements Config_Interface {

	/**
	 * [$var description]
	 *
	 * @var array
	 */
	protected $config = array();
	protected $default = array();

	/**
	 * [__construct description]
	 *
	 * @param [type] $config [description].
	 */
	function __construct( array $config = array(), array $default = array() ) {
		$this->config = $config;
		$this->default = $default;
	}

	/**
	 * Retrieves all of the runtime configuration parameters
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function all() {
		return $this->config;
	}

	/**
	 * Get the specified configuration value.
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		return $this->config[ $key ];
	}

	/**
	 * Determine if the given configuration value exists.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function has( $key ) {

		if ( ! array_key_exists( $key, $this->config ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Push a configuration in via the key
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Key to be assigned, which also becomes the property
	 * @param mixed $value Value to be assigned to the parameter key
	 * @return null
	 */
	public function push( $key, $value ) {

	}
}
