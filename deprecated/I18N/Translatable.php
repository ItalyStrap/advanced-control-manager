<?php
/**
 * Translatable Interface API
 *
 * Interface for translatable Class in themes and plugins
 *
 * @link italystrap.com
 * @since 2.9.0
 *
 * @package ItalyStrap\I18N
 */

namespace ItalyStrap\I18N;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

interface Translatable {

	/**
	 * Add registration for multilanguage string (contain hook)
	 *
	 * @since   1.0.0
	 *
	 * @param     string $string_name              The name of the string.
	 * @param     string $value                    The value.
	 */
	function register_string( $string_name, $value );

	/**
	 * Unregister multilanguage string, Polylang missing support of this feature
	 *
	 * @since   1.0.0
	 *
	 * @param     string $string_name              The name of the string.
	 */
	function deregister_string( $string_name );

	/**
	 * Get multilanguage string
	 *
	 * @since   1.0.0
	 *
	 * @param     string $string_name              The name of the string.
	 * @param     string $value                    The value.
	 */
	function get_string( $string_name, $value );
}
