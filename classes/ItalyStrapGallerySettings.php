<?php
/**
 * Renders extra controls in the Gallery Settings section of the new media UI.
 *
 * @since 1.1.0
 * @see JetPack functions-gallery.php gallery-settings.js
 * @package ItalyStrap
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
if ( !class_exists('ItalyStrapGallerySettings') ){

	class ItalyStrapGallerySettings {
		function __construct() {
			
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}

		/**
		 * @todo applicare filtro in ItalyStrapCarousel e togliere il 2° vlore dell'arrai qui sotto
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

			wp_enqueue_script( 'italystrap-gallery-settings' );

		}

		/**
		 * Outputs a view template which can be used with wp.media.template
		 */
		function print_media_templates() {
			$default_gallery_type = apply_filters( 'ItalyStrap_default_gallery_type', 'default' );

			?>
			<script type="text/html" id="tmpl-italystrap-gallery-settings">
				<label class="setting">
					<span><?php _e( 'Type', 'ItalyStrap' ); ?></span>
					<select class="type" name="type" data-setting="type">
						<?php foreach ( $this->gallery_types as $value => $caption ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $default_gallery_type ); ?>><?php echo esc_html( $caption ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</script>
			<?php
		}
	}
}