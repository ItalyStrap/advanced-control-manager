<?php
/**
 * Fonts API for Google Fonts
 *
 * This class load the google fonts for style purpose.
 *
 * @link www.italystrap.com
 * @since 2.5.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core\Google;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Web Font Loading class
 */
class Fonts {

	/**
	 * Google API font URL
	 *
	 * @var string
	 */
	private $google_api_url = '';

	/**
	 * Google API Key
	 *
	 * @var string
	 */
	private $google_api_key = '';

	private	$options = array();

	private	$theme_mods = array();

	/**
	 * Init the class.
	 *
	 * @param array $options The plugin options.
	 */
	function __construct( array $options = array(), array $theme_mods = array() ) {

		$this->options = $options;

		$this->theme_mods = $theme_mods;

		$this->google_api_url = '//www.googleapis.com/webfonts/v1/webfonts';

		$this->google_api_key = isset( $options['google_api_key'] ) ? '?key=' . esc_attr( $options['google_api_key'] ) : '';

		$this->variants = array(
			'100'		=> __( '100', 'italystrap' ),
			'100italic'	=> __( '100italic', 'italystrap' ),
			'200'		=> __( '200', 'italystrap' ),
			'200italic'	=> __( '200italic', 'italystrap' ),
			'300'		=> __( '300', 'italystrap' ),
			'300italic'	=> __( '300italic', 'italystrap' ),
			'regular'	=> __( 'Regular 400', 'italystrap' ),
			'italic'	=> __( 'Italic 400', 'italystrap' ),
			'500'		=> __( '500', 'italystrap' ),
			'500italic'	=> __( '500italic', 'italystrap' ),
			'600'		=> __( '600', 'italystrap' ),
			'600italic'	=> __( '600italic', 'italystrap' ),
			'700'		=> __( '700', 'italystrap' ),
			'700italic'	=> __( '700italic', 'italystrap' ),
			'800'		=> __( '800', 'italystrap' ),
			'800italic'	=> __( '800italic', 'italystrap' ),
			'900'		=> __( '900', 'italystrap' ),
			'900italic'	=> __( '900italic', 'italystrap' ),
		);

		$this->subsets = array(
			'bengali'		=> __( 'Bengali', 'italystrap' ),
			'cyrillic'		=> __( 'Cyrillic', 'italystrap' ),
			'cyrillic-ext'	=> __( 'Cyrillic Extended', 'italystrap' ),
			'devanagari'	=> __( 'Devanagari', 'italystrap' ),
			'greek'			=> __( 'Greek', 'italystrap' ),
			'greek-ext'		=> __( 'Greek Extended', 'italystrap' ),
			'gujarati'		=> __( 'Gujarati', 'italystrap' ),
			'hebrew'		=> __( 'Hebrew', 'italystrap' ),
			'khmer'			=> __( 'Khmer', 'italystrap' ),
			'latin'			=> __( 'Latin', 'italystrap' ),
			'latin-ext'		=> __( 'Latin Extended', 'italystrap' ),
			'tamil'			=> __( 'Tamil', 'italystrap' ),
			'telugu'		=> __( 'Telugu', 'italystrap' ),
			'thai'			=> __( 'Thai', 'italystrap' ),
			'vietnamese'	=> __( 'Vietnamese', 'italystrap' ),
		);
	}

	/**
	 * Flush transient
	 * Maybe flush at the 'update_option_' . $args['options_name'] ?
	 */
	public function flush_transient() {
		delete_transient( 'italystrap_google_fonts' );
	}

	/**
	 * Get the google fonts from the API or in the cache
	 *
	 * @return String
	 */
	public function get_remote_fonts() {

		if ( empty( $this->options['google_api_key'] ) ) {
			return array();
		}

		// $this->flush_transient();

		if ( false === ( $fonts = get_transient( 'italystrap_google_fonts' ) ) ) {

			$font_content = wp_remote_get( $this->google_api_url . $this->google_api_key, array( 'sslverify' => false ) );

			$fonts = wp_remote_retrieve_body( $font_content );

			$fonts = (array) json_decode( $fonts );

			$fonts = $this->rename_key_by_font_family_name( $fonts );

			set_transient( 'italystrap_google_fonts', $fonts, MONTH_IN_SECONDS );
		}

		if ( is_object( $fonts ) ) {
			return $fonts->items;
		}

		if ( ! isset( $fonts['items'] ) ) {
			return array();
		}

		return (array) $fonts['items'];
	}

	/**
	 * Get property
	 *
	 * @param  string $prop_name The name of the property to get.
	 * @return string            The value pf the property.
	 */
	public function get_property( $prop_name ) {

		if ( ! property_exists( $this, $prop_name ) ) {
			return null;
		}

		return $this->$prop_name;
	}

	/**
	 * Rename array key by font family name
	 *
	 * @param  array $items The array with Google fonts.
	 * @return array        Return the new array
	 */
	protected function rename_key_by_font_family_name( array $items = array() ) {

		$i = 0;
		foreach ( $items['items'] as $key => $object ) {
			$items['items'][ $object->family ] = $object;
			unset( $items['items'][ $i ] );
			$i++;
		}

		return $items;
	}
}
