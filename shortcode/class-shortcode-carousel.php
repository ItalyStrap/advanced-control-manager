<?php
/**
 * Shortcode_Carousel initially forked from Agnosia Bootstrap Carousel by AuSoft
 *
 * This class adds Carousell functionality to [gallery] shortcode and it loads only when needed.
 *
 * @since 1.1.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core;

/**
 * Add Bootstrap Carousel to gallery shortcode
 */
class Shortcode_Carousel {

	/**
	 * Modify the gallery shortcode with Bootstrap Carousel functionality.
	 *
	 * @param  string  $output  The outputo of the shortcode.
	 * @param  array   $atts    The shortcode attribute.
	 * @param  boolean $content The content (set to false because no nedeed).
	 * @param  boolean $tag     The tag (set to false because no nedeed).
	 * @return string           Return the new Bootstrap carousel
	 */
	function gallery_shortcode( $output = '', $atts, $content = false, $tag = false ) {

		/**
		 * Deprecated title attribute for shortcode.
		 *
		 * @deprecated 1.4.0 Deprecated title attribute for shortcode, use image_title instead
		 */
		if ( ! empty( $atts['title'] ) ) {
			_deprecated_argument( __FUNCTION__, '1.4.0', __( 'Use $atts[\'image_title\'] instead of $atts[\'title\']', 'ItalyStrap' ) ); // WPCS: XSS OK.
		}
		$atts['image_title'] = ( isset( $atts['title'] ) ) ? $atts['title'] : null ;

		$carousel_bootstrap = new Carousel_Bootstrap( $atts );
		return $carousel_bootstrap->__get( 'output' );

	}

	/**
	 * Add 'Bootstrap Carousel' to $gallery_type array
	 *
	 * @param  array $gallery_types The array with gallery type.
	 * @return array                Return the new array
	 */
	function gallery_types( $gallery_types ) {

		$gallery_types['carousel'] = 'Bootstrap Carousel';
		return $gallery_types;

	}
}
