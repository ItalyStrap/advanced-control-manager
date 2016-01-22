<?php namespace ItalyStrap\Core;

use \ItalyStrapAdminMediaSettings;

/**
 * Da leggere https://carlalexander.ca/polymorphism-wordpress-interfaces/
 */

if ( ! class_exists( 'Widget_Posts2' ) ) {

	/**
	 * Class
	 */
	class Widget_Posts2 extends Widget implements Interface_Widget {

		/**
		 * Init the constructor
		 */
		function __construct() {

			/**
			 * Instance of list of image sizes
			 * @var ItalyStrapAdminMediaSettings
			 */
			$image_size_media = new ItalyStrapAdminMediaSettings;
			$image_size_media_array = $image_size_media->get_image_sizes( array( 'full' => __( 'Real size', 'ItalyStrap' ) ) );

			$fields = array_merge( $this->title_field(), require( ITALYSTRAP_PLUGIN_PATH . 'options/options-posts.php' ) );

			/**
			 * Configure widget array.
			 * @var array
			 */
			$args = array(
				// Widget Backend label.
				'label'				=> __( 'ItalyStrap Posts', 'ItalyStrap' ),
				// Widget Backend Description.
				'description'		=> __( 'Displays list of posts with an array of options', 'ItalyStrap' ),
				'fields'			=> $fields,
				'control_options'	=> array( 'width' => 450 ),
			 );

			/**
			 * Create Widget
			 */
			$this->create_widget( $args );
		}

		/**
		 * Create the Field Text
		 *
		 * @access protected
		 * @param  array  $key The key of field's array to create the HTML field.
		 * @param  string $out The HTML form output.
		 * @return string      Return the HTML Field Text
		 */
		protected function create_field_media_list( $key, $out = '' ) {

			$out .= $this->create_field_label( $key['name'], $key['_id'] ) . '<br/>';

			$out .= '<input type="text" ';

			if ( isset( $key['class'] ) )
				$out .= 'class="' . esc_attr( $key['class'] ) . '" ';

			$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

			$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="' . esc_attr__( $value ) . '" ';

			if ( isset( $key['size'] ) )
				$out .= 'size="' . esc_attr( $key['size'] ) . '" ';

			$out .= ' />';

			if ( isset( $key['desc'] ) )
				$out .= $this->create_field_description( $key['desc'] );

			ob_start();

			?>
				<h5><?php esc_attr_e( 'Add your images', 'ItalyStrap' ); ?></h5>
				<hr>
				<div class="media_carousel_sortable">
					<ul id="sortable" class="carousel_images">
					<?php if ( ! empty( $value ) ) : ?>
						<?php
						$images = explode( ',', $value );
						foreach ( $images as $image ) :
							$image_attributes = wp_get_attachment_image_src( $image );
							if ( $image_attributes ) :
						?>
					
							<li class="carousel-image ui-state-default">
								<div>
									<i class="dashicons dashicons-no"></i>
									<img src="<?php echo esc_attr( $image_attributes[0] ); ?>" width="<?php echo esc_attr( $image_attributes[1] ); ?>" height="<?php echo esc_attr( $image_attributes[2] ); ?>" data-id="<?php echo esc_attr( $image ); ?>">
								</div>
							</li>
					
						<?php
							endif;
						endforeach; ?>
					<?php endif; ?>
					</ul>
				</div>
				<span style="clear:both;"></span>
				<input class="upload_carousel_image_button button button-primary widefat" type="button" value="<?php esc_attr_e( 'Add images', 'ItalyStrap' ); ?>" />
			<hr>
			<?php

			$output = ob_get_contents();
			ob_end_clean();

			return $out . $output;
		}

		/**
		 * Dispay the widget content
		 *
		 * @param  array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param  array $instance The settings for the particular instance of the widget.
		 */
		public function widget_render( $args, $instance ) {

			$out = '';

			// Check for transient. If none, then execute ItalyStrapCarousel.
			// if ( false === ( $mediacarousel = get_transient( $this->id ) ) ) {

				// $mediacarousel = new ItalyStrapCarousel( $instance );

				// Put the results in a transient. Expire after 12 hours.
				// set_transient( $this->id, $mediacarousel, 24 * HOUR_IN_SECONDS );

			// }

			// delete_transient( $this->id );

			// $mediacarousel = new ItalyStrapCarousel( $instance );
			// $out = $mediacarousel->__get( 'output' );

			return apply_filters( 'widget_text', $out );
		}
	} // class
}
