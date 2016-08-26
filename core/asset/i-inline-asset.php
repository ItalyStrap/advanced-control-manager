<?php
/**
 * Interface for Inline_Asset
 *
 * This is the interface for Inline_Asset Class
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core;

interface I_Inline_Asset {

	/**
	 * This append new data to the static variable.
	 *
	 * @param string $data The data static variable.
	 */
	public static function set( $data );

	/**
	 * This return the data variable.
	 */
	public static function get();
}