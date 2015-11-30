<?php
/**
 * Renders extra controls in the Gallery Settings section of the new media UI.
 *
 * @since 1.1.0
 * @see JetPack functions-gallery.php gallery-settings.js
 * @package ItalyStrap
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
if ( ! class_exists( 'ItalyStrapAdminGallerySettings' ) ) {

	class ItalyStrapAdminGallerySettings {

		/**
		 * Array with default value
		 * @var array
		 */
		private $fields = array();

		private $randID = null;

		/**
		 * Default option
		 * @var array
		 */
		private $carousel_options = array();

		private $indicators = array(
								'before-inner',
								'after-inner',
								'after-control',
								'false',
								);

		function __construct() {

			/**
			 * Define data by given attributes.
			 */
			$this->fields = require( ITALYSTRAP_PLUGIN_PATH . 'options/options-carousel.php' );

			$this->carousel_options = array(
				'orderby'		=> array(
						'menu_order'	=> __( 'Menu order (Default)', 'ItalyStrap' ),
						'title'			=> __( 'Order by the image\'s title', 'ItalyStrap' ),
						'post_date'		=> __( 'Sort by date/time', 'ItalyStrap' ),
						'rand'			=> __( 'Order randomly', 'ItalyStrap' ),
						'ID'			=> __( 'Order by the image\'s ID', 'ItalyStrap' ),
					),
				'indicators'	=> array(
						'before-inner'	=> __( 'before-inner', 'ItalyStrap' ),
						'after-inner'	=> __( 'after-inner', 'ItalyStrap' ),
						'after-control'	=> __( 'after-control', 'ItalyStrap' ),
						'false'			=> __( 'false', 'ItalyStrap' ),
					),
				'control'		=> true,
				'pause'			=> array(
						'false'			=> __( 'none', 'ItalyStrap' ),
						'hover'			=> __( 'hover', 'ItalyStrap' ),
					),
				'image_title'	=> true,
				'text' 			=> true,
				'wpautop' 		=> true,
				'responsive'	=> false,
			);

			$this->randID = rand(2, 5);

			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}

		/**
		 * @todo applicare filtro in ItalyStrapCarousel e togliere il 2° valore dell'array qui sotto
		 */
		function admin_init() {
			$this->gallery_types =
				apply_filters(
					'ItalyStrap_gallery_types',
					array(
						'default' => __( 'Standard gallery', 'ItalyStrap' ),
						'carousel' => __( 'Bootstrap Carousel', 'ItalyStrap' ),
					)
				);

			// Enqueue the media UI only if needed.
			// if ( count( $this->gallery_types ) > 0 ) {
				add_action( 'wp_enqueue_media', array( $this, 'wp_enqueue_media' ) );
				add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
			// }
		}

		/**
		 * Registers/enqueues the gallery settings admin js.
		 */
		function wp_enqueue_media() {

			if ( ! wp_script_is( 'italystrap-gallery-settings', 'registered' ) ) {
				/**
				 * This only happens if we're not in ItalyStrap, but on WPCOM instead.
				 * This is the correct path for WPCOM.
				 */
				wp_register_script( 'italystrap-gallery-settings', plugins_url( 'admin/js/src/gallery-settings.js', ITALYSTRAP_FILE ), array( 'media-views' ), '20121225666' );

			}

			$translation_array = wp_json_encode( require( ITALYSTRAP_PLUGIN_PATH . 'options/options-carousel.php' ) );
			wp_localize_script( 'italystrap-gallery-settings', 'gallery_fields', $translation_array );

			wp_enqueue_script( 'italystrap-gallery-settings' );

		}

		/**
		 * Outputs a view template which can be used with wp.media.template
		 */
		function print_media_templates() {

			$default_gallery_type = apply_filters( 'ItalyStrap_default_gallery_type', 'default' );

			echo '<script type="text/html" id="tmpl-italystrap-gallery-settings">';


			?>
				<style>
				.setting select{
					float: right;
				}
				</style>
				<label class="setting">
					<span><?php esc_attr_e( 'Type', 'ItalyStrap' ); ?></span>
					<select class="type" name="type" data-setting="type">
						<?php foreach ( $this->gallery_types as $value => $caption ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $default_gallery_type ); ?>><?php echo esc_html( $caption ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<div id="italystrap-carousel-option">
			<?php


			$instance = array();

			$instance = wp_parse_args( (array) $instance, (array) $this->fields );

			$instance['name'] = $instance['name'] . $this->randID;

			/**
			 * Instance of list of image sizes
			 * @var ItalyStrapAdminMediaSettings
			 */
			$image_size_media = new ItalyStrapAdminMediaSettings;
			$image_size_media_array = $image_size_media->get_image_sizes( array( 'full' => __( 'Real size', 'ItalyStrap' ) ) );

			foreach ( $this->fields as $key => $label ) {

				$instance[ $key ] = isset( $instance[ $key ] ) ? $instance[ $key ] : '';

				/**
				 * Save select in widget
				 * @link https://wordpress.org/support/topic/wordpress-custom-widget-select-options-not-saving
				 * Display select only if is schema
				 */
				if ( 'ids' === $key || 'type' === $key || 'size' === $key ) {


				} elseif ( 'sizetablet' === $key || 'sizephone' === $key ) {

				?>
					<?php $saved_option = ( isset( $instance[ $key ] ) ) ? $instance[ $key ] : '' ; ?>

					<label class="setting" for="<?php echo esc_attr( $key ); ?>">
						<span><?php echo esc_attr( $key ); ?></span>
						<select class="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" data-setting="<?php echo esc_attr( $key ); ?>">
							<!-- <option value="" disabled selected>Select your option</option> -->
							<?php
							$option = '';

							foreach ( $image_size_media_array as $k => $v ) {

								$option .= '<option ' . ( selected( $k, $saved_option ) ) . ' value="' . $k . '">' . $v . '</option>';

							}

							echo $option;
							?>
						</select>
					</label>
				<?php
				} else {
				?>

					<?php if ( isset( $this->carousel_options[ $key ] ) && is_array( $this->carousel_options[ $key ] ) ) :	?>

						<?php $saved_option = ( isset( $instance[ $key ] ) ) ? $instance[ $key ] : '' ; ?>

						<label class="setting" for="<?php echo esc_attr( $key ); ?>">
							<span><?php echo esc_attr( $key ); ?></span>
							<select class="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" data-setting="<?php echo esc_attr( $key ); ?>">
								<!-- <option value="" disabled selected>Select your option</option> -->
								<?php
								$option = '';

								foreach ( $this->carousel_options[ $key ] as $k => $v ) {

									$option .= '<option ' . ( selected( $k, $saved_option ) ) . ' value="' . $k . '">' . $v . '</option>';

								}

								echo $option;
								?>
							</select>
						</label>

					<?php elseif ( isset( $this->carousel_options[ $key ] ) ) :

						if ( 'true' === $instance[ $key ] ) {
							$instance[ $key ] = true;
						}
					?>
						<label class="setting" for="<?php echo esc_attr( $key ); ?>">
							<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value='1' data-setting="<?php echo esc_attr( $key ); ?>" <?php checked( $instance[ $key ], true ); ?>>
							<span><?php echo esc_attr( $key ); ?></span>
						</label>

					<?php else : ?>

						<label class="setting" for="<?php echo esc_attr( $key ); ?>">
							<span><?php echo esc_attr( $key ); ?></span>
							<input type="text" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $label ); ?>" data-setting="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $label ); ?>">
						</label>

					<?php endif; ?>

				<?php } //!- else
			}//!- foreach

			?>
					<!-- <p><?php esc_attr_e( 'For more informations about the options below see the <a href="admin.php?page=italystrap-documentation#carousel" target="_blank">documentation.</a>', 'ItalyStrap' ); ?></p>
					<label class="setting">
						<span><?php esc_attr_e( 'Indicators', 'ItalyStrap' ); ?></span>
						<select class="indicators" name="indicators" data-setting="indicators">
							<option value="" disabled selected>Select your option</option>
							<?php foreach ( $this->indicators as $value ) : ?>
								<option value="<?php if ( 'before-inner' !== $value ) echo esc_attr( $value ); ?>" ><?php echo esc_html( $value ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<label class="setting">
						<span><?php esc_attr_e( 'HTML ID', 'ItalyStrap' ); ?></span>
						<input type="text" name="name" value="" data-setting="name" placeholder="eg. myCssID">
					</label>
					<label class="setting">
						<span><?php esc_attr_e( 'Interval', 'ItalyStrap' ); ?></span>
					</label>
					<input type="text" name="interval" value="" data-setting="interval" placeholder="5000"> -->

			<?php

			echo '</div></script>';
		}
	}
}
