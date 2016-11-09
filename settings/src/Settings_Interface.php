<?php
/**
 * Interface for Admin Class
 *
 * This is the interface for the admin class
 *
 * @link [URL]
 * @since 2.0.0
 *
 * @package ItalyStrap\Settings
 */

namespace ItalyStrap\Settings;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

interface Settings_Interface {

	/**
	 * Function description
	 *
	 * @since 2.0.0
	 */
	public function settings_init();

	/**
	 * Sanitize the input data
	 *
	 * @since 2.0.0
	 *
	 * @param  array $input The input array.
	 * @return array        Return the array sanitized
	 */
	public function update( $input );
}
