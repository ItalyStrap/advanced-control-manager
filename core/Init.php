<?php
/**
 * Init some plugin stuff
 *
 * @link www.italystrap.com
 * @since 1.0
 * @since 2.4
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core;

use ItalyStrap\Shortcode\Gallery;

/**
 * Init Class for ItalyStrap Core
 */
class Init {

	/**
	 * The plugin's options
	 *
	 * @var string
	 */
	private $options = '';

	/**
	 * Fire the construct
	 */
	public function __construct( array $options = array() ) {
		// delete_option( 'italystrap_settings' );
		$this->options = $options;
		// $this->options = (array) get_option( 'italystrap_settings' );
		// var_dump( $this->options );
	}

	/**
	 * Get Options
	 *
	 * @since 2.0.0
	 * @return array        Get options
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Init functions
	 */
	public function on_load() {

		/**
		 * Load po file
		 */
		load_plugin_textdomain( 'italystrap', false, dirname( ITALYSTRAP_BASENAME ) . '/lang' );
	}

	/**
	 * Add type Carousel to built-in gallery shortcode
	 */
	public function add_carousel_to_gallery_shortcode() {
	
		/**
		 * Istantiate Shortcode_Carousel only if [gallery] shortcode exist
		 *
		 * @link http://wordpress.stackexchange.com/questions/103549/wp-deregister-register-and-enqueue-dequeue
		 */
		$post = get_post();
		$gallery = false;

		if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'gallery' ) ) {
			$gallery = true; // A http://dannyvankooten.com/3935/only-load-contact-form-7-scripts-when-needed/ .
		}

		if ( ! $gallery ) {
			$shortcode_carousel = new Gallery();
			add_filter( 'post_gallery', array( $shortcode_carousel, 'gallery_shortcode' ), 10, 3 );
			add_filter( 'jetpack_gallery_types', array( $shortcode_carousel, 'gallery_types' ) );
			// add_filter( 'ItalyStrap_gallery_types', array( $shortcode_carousel, 'gallery_types' ), 999 );
		}
	
	}
} // End Init
