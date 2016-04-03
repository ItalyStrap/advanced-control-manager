<?php namespace ItalyStrap\Admin;
/**
 * Sanitization API: Sanitization Class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Sanitization class
 */
class Sanitization {

	/**
	 * Filter the value of key
	 *
	 * @access private
	 * @param  string $rules          Insert the filter name you want to use.
	 *                                Use | to separate more filter.
	 *
	 * @param  string $instance_value The value you want to filter.
	 * @return string                 Return the value filtered
	 */
	public function sanitize( $rules, $instance_value ) {
		$rules = explode( '|', $rules );

		if ( empty( $rules ) || count( $rules ) < 1 ) {
			return $instance_value;
		}

		foreach ( $rules as $rule ) {
			$instance_value = $this->do_filter( $rule, $instance_value );
		}

		return $instance_value;
	}

	/**
	 * Filter the value of key
	 *
	 * List of functions for sanitizing data:
	 * strip_tags
	 * wp_strip_all_tags
	 * esc_attr
	 * esc_url
	 * esc_textarea
	 * sanitize_email
	 * sanitize_file_name
	 * sanitize_html_class
	 * sanitize_key
	 * sanitize_meta
	 * sanitize_mime_type
	 * sanitize_sql_orderby
	 * sanitize_text_field
	 * sanitize_title
	 * sanitize_title_for_query
	 * sanitize_title_with_dashes
	 * sanitize_user
	 * sanitize_option // sanitize_option ha bisogno di 2 valori eseguire test
	 *
	 * @access private
	 * @param  string $rule         The filter name you want to use.
	 * @param  string $instance_value The value you want to filter.
	 * @return string                 Return the value filtered
	 */
	private function do_filter( $rule, $instance_value = '' ) {

		if ( method_exists( $this, $rule ) ) {
			return $this->$rule( $instance_value );
		} elseif ( function_exists( $rule ) ) {
			return call_user_func( $rule, $instance_value );
		} else {
			return sanitize_text_field( $instance_value );
		}

		// switch ( $rule ) {

		// 	case 'sanitize_array':
		// 		$array = array_map( 'esc_attr', $instance_value );
		// 		$array = array_map( 'absint', $array );
		// 		$count = count( $array );
		// 		if ( 1 === $count && 0 === $array[0] ) {
		// 			return array();
		// 		}
		// 		return $array;
		// 	break;
		// }

	}

	/**
	 * Sanitize array (this is an alpha method, do not use it)
	 *
	 * @param  array $instance_value The value to sanitize
	 * @return array                 The sanitized array
	 */
	public function sanitize_array( array $instance_value ) {
	
		$array = array_map( 'esc_attr', $instance_value );
		$array = array_map( 'absint', $array );
		$count = count( $array );
		if ( 1 === $count && 0 === $array[0] ) {
			return array();
		}
		return $array;
	
	}
}
