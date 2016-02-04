<?php namespace ItalyStrap\Core;

use \ItalyStrapAdminMediaSettings;

/**
 * Da leggere https://carlalexander.ca/polymorphism-wordpress-interfaces/
 */

if ( ! class_exists( 'Widget_Media_Carousel' ) ) {

	/**
	 * Class
	 */
	class Widget_Media_Carousel extends Widget implements Interface_Widget {

		/**
		 * Init the constructor
		 */
		function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'upload_scripts' ) );

			/**
			 * Instance of list of image sizes
			 * @var ItalyStrapAdminMediaSettings
			 */
			$image_size_media = new ItalyStrapAdminMediaSettings;
			$image_size_media_array = $image_size_media->get_image_sizes( array( 'full' => __( 'Real size', 'ItalyStrap' ) ) );

			$fields = array_merge( $this->title_field(), require( ITALYSTRAP_PLUGIN_PATH . 'options/options-media-carousel.php' ) );

			/**
			 * Configure widget array.
			 * @var array
			 */
			$args = array(
				// Widget Backend label.
				'label'				=> __( 'ItalyStrap Media Carousel', 'ItalyStrap' ),
				// Widget Backend Description.
				'description'		=> __( 'Add a image carousel for all your media files from any posts type (posts, pages, attachments and custom post type)', 'ItalyStrap' ),
				'fields'			=> $fields,
				'control_options'	=> array( 'width' => 450 ),
			 );

			/**
			 * Create Widget
			 */
			$this->create_widget( $args );
		}

		/**
		 * Dispay the widget content
		 *
		 * @param  array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param  array $instance The settings for the particular instance of the widget.
		 */
		public function widget_render( $args, $instance ) {

			$mediacarousel = new Carousel_Bootstrap( $instance );
			$out = $mediacarousel->__get( 'output' );

			return apply_filters( 'widget_text', $out );
		}

		/**
		 * Validate if is the format num,num,
		 * @param  int $value The vallue of the field.
		 * @return bool       Return tru if is format num,num, else return false
		 */
		function numeric_comma( $value ) {

			return (bool) preg_match( '/(?:\d+\,)+?/', $instance_value );

		}
	} // class
}
