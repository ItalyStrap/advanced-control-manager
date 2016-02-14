<?php namespace ItalyStrap\Core;
/**
 * General functions for the plugin
 *
 * @package ItalyStrap
 * @since   2.0.0
 */

/**
 * Combine user attributes with known attributes and fill in defaults when needed.
 *
 * The pairs should be considered to be all of the attributes which are
 * supported by the caller and given as a list. The returned attributes will
 * only contain the attributes in the $pairs list.
 *
 * If the $atts list has unsupported attributes, then they will be ignored and
 * removed from the final returned list.
 *
 * @since 2.0.0
 *
 * @param array  $pairs     Entire list of supported attributes and their defaults.
 * @param array  $atts      User defined attributes in shortcode tag.
 * @param string $shortcode Optional. The name of the shortcode, provided for context to enable filtering
 * @return array Combined and filtered attribute list.
 */
function shortcode_atts_multidimensional_array( array $pairs, array $atts, $shortcode = '' ) {

	$atts = (array)$atts;

	$out = array();

	foreach ( $pairs as $name => $default ) {

		if ( array_key_exists( $name, $atts ) )
			$out[ $name ] = $atts[ $name ];
		else
			$out[ $name ] = ( ( ! empty( $default['default'] ) ) ? $default['default'] : '' );

	}

	/**
	 * Filter a shortcode's default attributes.
	 *
	 * If the third parameter of the shortcode_atts() function is present then this filter is available.
	 * The third parameter, $shortcode, is the name of the shortcode.
	 *
	 * @param array  $out       The output array of shortcode attributes.
	 * @param array  $pairs     The supported attributes and their defaults.
	 * @param array  $atts      The user defined shortcode attributes.
	 * @param string $shortcode The shortcode name.
	 */
	if ( $shortcode ) {
		$out = apply_filters( "shortcode_atts_multidimensional_array_{$shortcode}", $out, $pairs, $atts, $shortcode );
	}

	return $out;

}

/**
 * Read and return file content
 *
 * @link https://tommcfarlin.com/reading-files-with-php/
 * @param  file $filename	The file for lazyloading
 * @return string $content	Return the content of the file
 */
function get_file_content( $filename ) {

	// Check to see if the file exists at the specified path
	if ( ! file_exists( $filename ) )
		throw new Exception( __( 'The file doesn\'t exist.', 'ItalyStrap' ) );

	// Open the file for reading
	$file_resource = fopen( $filename, 'r' );

	/**
	 * Read the entire contents of the file which is indicated by
	 * the filesize argument
	 */
	$content = fread( $file_resource, filesize( $filename ) );
	fclose( $file_resource );

	return $content;

}

/**
 * Get an array with the taxonomies list
 *
 * @param  string $tax The name of taxonomy type
 * @return array       Return an array with tax list
 */
function get_taxonomies_list_array( $tax ) {

	/**
	 * Array of taxonomies
	 *
	 * @todo Make object cache, see https://10up.github.io/Engineering-Best-Practices/php/#performance
	 * @todo Add a default value
	 * @var array
	 */
	$tax_arrays = get_terms( $tax );

	$get_taxonomies_list_array = array();

	foreach ( $tax_arrays as $tax_array ) {

		$get_taxonomies_list_array[ $tax_array->term_id ] = $tax_array->name;

	}

	return $get_taxonomies_list_array;
}
