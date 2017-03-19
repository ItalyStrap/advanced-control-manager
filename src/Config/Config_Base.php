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
	 * @var null
	 */
	private $var = null;

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( $argument = null ) {
		// Code...
	}

	/**
	 * Retrieves all of the runtime configuration parameters
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function all() {}

	/**
	 * Get the specified configuration value.
	 *
	 * @param  string  $parameter_key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public function get( $parameter_key, $default = null ) {}

	/**
	 * Determine if the given configuration value exists.
	 *
	 * @param  string  $parameter_key
	 * @return bool
	 */
	public function has( $parameter_key ) {}

	/**
	 * Push a configuration in via the key
	 *
	 * @since 1.0.0
	 *
	 * @param string $parameter_key Key to be assigned, which also becomes the property
	 * @param mixed $value Value to be assigned to the parameter key
	 * @return null
	 */
	public function push( $parameter_key, $value ) {}
}