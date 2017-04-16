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

/**
 * Config Class
 */
class Config extends Config_Base {

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
}
