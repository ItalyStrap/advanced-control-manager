<?php namespace ItalyStrap\Core;
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
	 * @access private
	 * @param  string $rule         The filter name you want to use.
	 * @param  string $instance_value The value you want to filter.
	 * @return string                 Return the value filtered
	 */
	public function do_filter( $rule, $instance_value = '' ) {
		switch ( $rule ) {

			case 'strip_tags':
				return strip_tags( $instance_value );
			break;

			case 'wp_strip_all_tags':
				return wp_strip_all_tags( $instance_value );
			break;

			case 'esc_attr':
				return esc_attr( $instance_value );
			break;

			case 'esc_url':
				return esc_url( $instance_value );
			break;

			case 'esc_textarea':
				return esc_textarea( $instance_value );
			break;

			case 'sanitize_email':
				return sanitize_email( $instance_value );
			break;

			case 'sanitize_file_name':
				return sanitize_file_name( $instance_value );
			break;

			case 'sanitize_html_class':
				return sanitize_html_class( $instance_value );
			break;

			case 'sanitize_key':
				return sanitize_key( $instance_value );
			break;

			case 'sanitize_meta':
				return sanitize_meta( $instance_value );
			break;

			case 'sanitize_mime_type':
				return sanitize_mime_type( $instance_value );
			break;

			case 'sanitize_option':
				return sanitize_option( $instance_value );
			break;

			case 'sanitize_sql_orderby':
				return sanitize_sql_orderby( $instance_value );
			break;

			case 'sanitize_text_field':
				return sanitize_text_field( $instance_value );
			break;

			case 'sanitize_title':
				return sanitize_title( $instance_value );
			break;

			case 'sanitize_title_for_query':
				return sanitize_title_for_query( $instance_value );
			break;

			case 'sanitize_title_with_dashes':
				return sanitize_title_with_dashes( $instance_value );
			break;

			case 'sanitize_user':
				return sanitize_user( $instance_value );
			break;

			case 'sanitize_array':
				$array = array_map( 'esc_attr', $instance_value );
				$array = array_map( 'absint', $array );
				$count = count( $array );
				if ( 1 === $count && 0 === $array[0] ) {
					return array();
				}
				return $array;
			break;

			default:
				if ( method_exists( $this, $rule ) ) {
					return $this->$rule( $instance_value );
				} else { return $instance_value; }
			break;
		}
	}
}
