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

	/**
	 * [__construct description]
	 *
	 * @param [type] $config [description].
	 */
	function __construct( $config = array() ) {
		$this->config = $config;
	}
}
