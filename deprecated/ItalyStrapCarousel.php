<?php namespace ItalyStrap\Core;

use \ItalyStrap\Core\Carousel\Bootstrap;
/**
 * ItalyStrap Carousel initially forked from Agnosia Bootstrap Carousel by AuSoft
 *
 * Display a Bootstrap Carousel based on selected images and their titles and
 * descriptions. You need to include the Bootstrap CSS and Javascript files on
 * your own; otherwise the class will not work.
 *
 * @todo https://codex.wordpress.org/it:Shortcode_Gallery Aggiungere parametri mancanti
 *
 * @package ItalyStrap
 * @version 1.0
 * @since   1.0
 *
 * @deprecated 2.0 Class deprecated use ItalyStrap\Core\Carousel_Bootstrap
 */
if ( ! class_exists( 'ItalyStrapCarousel' ) ) {

	/**
	 * The Carousel Bootsrap class
	 */
	class ItalyStrapCarousel extends Bootstrap {

		/**
		 * Initialize the contstructor
		 * @param array $args The carousel attribute.
		 */
		function __construct( $args ) {

			_deprecated_function( __CLASS__, '2.0', 'ItalyStrap\\\Core\\\Carousel\\\Bootstrap' );

			parent::__construct( $args );

		}
	}
}
