<?php namespace ItalyStrap\Core;

use \ItalyStrapAdminMediaSettings;

/**
 * Da leggere https://carlalexander.ca/polymorphism-wordpress-interfaces/
 */

/**
 * Class
 */
class Widget_Image extends Widget {

	/**
	 * Init the constructor
	 */
	function __construct() {

		/**
		 * I don't like this and I have to find a better solution for loading script and style for widgets.
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'upload_scripts' ) );

		/**
		 * Instance of list of image sizes
		 *
		 * @var ItalyStrapAdminMediaSettings
		 */
		$image_size_media = new ItalyStrapAdminMediaSettings;
		$image_size_media_array = $image_size_media->get_image_sizes( array( 'full' => __( 'Real size', 'ItalyStrap' ) ) );

		$fields = array_merge( $this->title_field(), require( ITALYSTRAP_PLUGIN_PATH . 'options/options-image.php' ) );

		/**
		 * Configure widget array.
		 *
		 * @var array
		 */
		$args = array(
			// Widget Backend label.
			'label'				=> __( 'ItalyStrap Image', 'ItalyStrap' ),
			// Widget Backend Description.
			'description'		=> __( 'Add a image with title, url and description', 'ItalyStrap' ),
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

		$size_class = esc_attr( $instance['size'] );

		$align = ( isset( $instance['alignment'] ) ) ? esc_attr( $instance['alignment'] ) : '';

		$image_css_class = ( isset( $instance['image_css_class'] ) ) ? esc_attr( $instance['image_css_class'] ) : '';

		$container_css_class = isset( $instance['container_css_class'] ) ? esc_attr( $instance['container_css_class'] ) : '';

		$out = '';

		if ( ! empty( $instance['add_figure_container'] ) ) {
			$out = '<figure class="widget-figure-media ' . $container_css_class . '">';
		}

		if ( ! empty( $instance['link'] ) ) {
			$out .= '<a href="' . esc_attr( $instance['link'] ) . '">';
		}

		$attr = array(
		'class'		=> "attachment-$size_class size-$size_class $align $image_css_class",
		'itemprop'	=> 'image',
		);

		if ( isset( $instance['image_title'] ) ) {
			$attr['title'] = esc_attr( $instance['image_title'] );
		}

		if ( isset( $instance['alt'] ) ) {
			$attr['alt'] = esc_attr( $instance['alt'] );
		}

		$out .= wp_get_attachment_image( $instance['id'] , $size_class, false, $attr );

		if ( ! empty( $instance['link'] ) ) {
			$out .= '</a>';
		}

		if ( ! empty( $instance['add_figure_container'] ) ) {

			if ( ! empty( $instance['caption'] ) ) {

				$out .= '<figcaption class="fig-null">' . esc_attr( $instance['caption'] ) . '</figcaption>';

			}

			$out .= '</figure>';
		}

		return $out;
	}
} // Class.
