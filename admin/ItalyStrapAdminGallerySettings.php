<?php
/**
 * Renders extra controls in the Gallery Settings section of the new media UI.
 *
 * @since 1.1.0
 * @see JetPack functions-gallery.php gallery-settings.js
 * @package ItalyStrap
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
if ( !class_exists('ItalyStrapAdminGallerySettings') ){

	class ItalyStrapAdminGallerySettings {

		private $indicators = array(
								'before-inner',
								'after-inner',
								'after-control',
								'false'
								);

				/**
				 * Alternative appearing order of images.
				 */
				// 'orderby' => '',

				/**
				 * Any name. String will be sanitize to be used as HTML ID. Recomended 
				 * when you want to have more than one carousel in the same page. 
				 * Default: italystrap-bootstrap-carousel.
				 * */
				// 'name' => 'italystrap-bootstrap-carousel',

				/**
				 * Carousel container width, in px or %
				 */
				// 'width' => '',

				/**
				 * Carousel item height, in px or %
				 */
				// 'height' => '',

				/**
				 * Accepted values: before-inner, after-inner, after-control, false.
				 * Default: before-inner.
				 * */
				// 'indicators' => 'before-inner',

				/**
				 * Enable or disable arrow right and left
				 * Accepted values: true, false. Default: true.
				 */
				// 'control' => 'true',

				/**
				 * @todo Aggiungere la possibilità di poter decidere quali simbili
				 *       usare come selettori delle immagini (@see Vedi sotto)
				 * Enable or disable arrow from Glyphicons
				 * Accepted values: true, false. Default: true.
				 */
				// 'arrow' => 'true',

				/**
				 * @todo Aggiungere inserimento glyphicon nello shortcode
				 *       decidere se fare inserire tutto lo span o solo l'icona
				 */
				// 'control-left' => '<span class="glyphicon glyphicon-chevron-left"></span>',
				// 'control-right' => '<span class="glyphicon glyphicon-chevron-right"></span>',

				/**
				 * The amount of time to delay between automatically
				 * cycling an item in milliseconds.
				 * @type integer Example 5000 = 5 seconds.
				 * Default 0, carousel will not automatically cycle.
				 * @link http://www.smashingmagazine.com/2015/02/09/carousel-usage-exploration-on-mobile-e-commerce-websites/
				 */
				// 'interval' => 0,

				/**
				 * Pauses the cycling of the carousel on mouseenter and resumes the
				 * cycling of the carousel on mouseleave.
				 * @type string Default hover.
				 */
				// 'pause' => 'hover',

				/**
				 * Define tag for image title. Default: h4.
				 */
				// 'titletag' => 'h4',

				/**
				 * Show or hide image title. Set false to hide. Default: true.
				 */
				// 'title' => 'true',

				/**
				 * Type of link to show if "title" is set to true.
				 * Default Link Parameters file, none, link
				 */
				// 'link' => '',

				/**
				 * Show or hide image text. Set false to hide. Default: true.
				 */
				// 'text' => 'true',

				/**
				 * Auto-format text. Default: true.
				 */
				// 'wpautop' => 'true',

				/**
				 * Extra class for container.
				 */
				// 'containerclass' => '',

				/**
				 * Extra class for item.
				 */
				// 'itemclass' => '',

				/**
				 * Extra class for caption.
				 */
				// 'captionclass' => '',

				/**
				 * Size for image attachment. Accepted values: thumbnail, medium,
				 * large, full or own custom name added in add_image_size function.
				 * Default: full.
				 * @see wp_get_attachment_image_src() for further reference.
				 */ 
				// 'size' => 'full',

				/**
				 * Activate responsive image. Accepted values: true, false.
				 * Default false.
				 */
				// 'responsive' => false,

				/**
				 * Size for image attachment. Accepted values: thumbnail, medium,
				 * large, full or own custom name added in add_image_size function.
				 * Default: large.
				 * @see wp_get_attachment_image_src() for further reference.
				 */ 
				// 'sizetablet' => 'large',

				/**
				 * Size for image attachment. Accepted values: thumbnail, medium,
				 * large, full or own custom name added in add_image_size function.
				 * Default: medium.
				 * @see wp_get_attachment_image_src() for further reference.
				 */ 
				// 'sizephone' => 'medium'




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
				<!-- <div id="italystrap-carousel-option">
					<p><?php _e( 'For more informations about the options below see the <a href="admin.php?page=italystrap-documentation#carousel" target="_blank">documentation.</a>', 'ItalyStrap' ); ?></p>
					<label class="setting">
						<span><?php _e( 'Indicators', 'ItalyStrap' ); ?></span>
						<select class="indicators" name="indicators" data-setting="indicators">
							<option value="" disabled selected>Select your option</option>
							<?php foreach ( $this->indicators as $value ) : ?>
								<option value="<?php if ($value !== 'before-inner') echo esc_attr( $value ); ?>" ><?php echo esc_html( $value ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<label class="setting">
						<span><?php _e( 'HTML ID', 'ItalyStrap' ); ?></span>
						<input type="text" name="name" value="" data-setting="name" placeholder="eg. myCssID">
					</label>
					<label class="setting">
						<span><?php _e( 'Interval', 'ItalyStrap' ); ?></span>
						<input type="text" name="interval" value="" data-setting="interval" placeholder="5000">
					</label>
				</div> -->
			</script>
			<?php
		}
	}
}