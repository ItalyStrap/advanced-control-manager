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

namespace ItalyStrap\Shortcode;

use ItalyStrap\Core\Carousel\Bootstrap;

/**
 * Add Bootstrap Carousel to gallery shortcode
 */
class Gallery {

	/**
	 * Modify the gallery shortcode with Bootstrap Carousel functionality.
	 *
	 * @param  string  $output   The gallery output. Default empty.
	 * @param  array   $atts     Attributes of the gallery shortcode.
	 * @param  boolean $instance Unique numeric ID of this gallery shortcode instance.
	 * @return string            Return the new Bootstrap carousel
	 */
	function gallery_shortcode( $output, $atts, $instance ) {

		/**
		 * If type is not set return the output.
		 */
		if ( ! isset( $atts['type'] ) ) {
			return $output;
		}

		/**
		 * Deprecated title attribute for shortcode.
		 *
		 * @deprecated 1.4.0 Deprecated title attribute for shortcode, use image_title instead
		 */
		if ( ! empty( $atts['title'] ) ) {
			_deprecated_argument( __FUNCTION__, '1.4.0', __( 'Use $atts[\'image_title\'] instead of $atts[\'title\']', 'italystrap' ) ); // WPCS: XSS OK.
		}

		if ( ! isset( $atts['image_title'] ) && isset( $atts['title'] ) ) {
			$atts['image_title'] = $atts['title'];
		}

		if ( 'carousel' === $atts['type'] ) {
			$carousel_bootstrap = new Bootstrap( $atts );
			return $carousel_bootstrap->__get( 'output' );
		}

		return $output;

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
