<?php
/**
 * Settings_Converter API
 *
 * This class converts settings from options to theme_mode and viceversa
 *
 * @link www.italystrap.it
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Migrations;

/**
 * Settings_Converter
 */
class Settings_Converter {

	/**
	 * [$var description]
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * [__construct description]
	 *
	 * @param [type] $data [description].
	 */
	public function __construct( array $pattern = array(), array $data = array() ) {
		$this->data = $data;
		$this->pattern = $pattern;
	}

	/**
	 * Puts an option to theme_mod.
	 *
	 * @param   array $pattern The pattern conversion.
	 * @param   array $data The array with options to convert.
	 *
	 * @example $pattern = array( 'old_option_key'	=> 'new_theme_mod_key', );
	 */
	public function data_to_theme_mod( array $pattern, array $data ) {

		foreach ( $pattern as $old_key => $new_key ) {
			if ( get_theme_mod( $new_key ) ) {
				continue;
			}

			if ( ! isset( $data[ $old_key ] ) ) {
				continue;
			}

			if ( preg_match( '#png|jpg|gif#is', $data[ $old_key ] ) ) {
				set_theme_mod( $new_key, $this->get_image_id_from_url( $data[ $old_key ] ) );
				continue;
			}

			set_theme_mod( $new_key, $data[ $old_key ] );
		}
	}

	/**
	 * Puts an option to option.
	 *
	 * @param   array $pattern The pattern conversion.
	 * @param   array $data The array with options to convert.
	 *
	 * @example $pattern = array( 'old_option_key'	=> 'new_theme_mod_key', );
	 */
	public function data_to_option( array $pattern, array $data ) {

		foreach ( $pattern as $old_key => $new_key ) {
			if ( get_option( $new_key ) ) {
				continue;
			}

			if ( ! isset( $data[ $old_key ] ) ) {
				continue;
			}

			if ( preg_match( '#png|jpg|gif#is', $data[ $old_key ] ) ) {
				update_option( $new_key, $this->get_image_id_from_url( $data[ $old_key ] ) );
				continue;
			}

			update_option( $new_key, $data[ $old_key ] );
		}
	}

	/**
	 * Puts an option to options.
	 *
	 * @param   array  $pattern     The pattern conversion.
	 * @param   array  $data        The array with options to convert.
	 * @param   array  $options     The options in an array.
	 * @param   string $option_name The name of the option to get.
	 *
	 * @example $pattern = array( 'old_option_key'	=> 'new_theme_mod_key', );
	 */
	public function data_to_options( array $pattern, array $data, $options, $option_name ) {

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		foreach ( $pattern as $old_key => $new_key ) {

			$options[ $new_key ] = null;

			if ( ! empty( $options[ $new_key ] ) ) {
				continue;
			}

			if ( ! isset( $data[ $old_key ] ) ) {
				continue;
			}

			if ( preg_match( '#png|jpg|gif#is', $data[ $old_key ] ) ) {
				$options[ $new_key ] = $this->get_image_id_from_url( $data[ $old_key ] );
				update_option( $option_name, $options );
				continue;
			}

			$options[ $new_key ] = $data[ $old_key ];
			update_option( $option_name, $options );
		}
	}

	/**
	 * Retrieves the attachment ID from the file URL
	 *
	 * @link https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
	 *
	 * @param  string $image_url The src of the image.
	 *
	 * @return int               Return the ID of the image
	 */
	private function get_image_id_from_url( $image_url ) {

		return \ItalyStrap\Core\get_image_id_from_url( $image_url );
	}
}
