<?php
/**
 * Renders extra controls in the Gallery Settings section of the new media UI.
 *
 * @since 1.1.0
 * @see JetPack functions-gallery.php gallery-settings.js
 * @package ItalyStrap
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

use ItalyStrap\Admin\I_Fields;

class ItalyStrapAdminGallerySettings {

	/**
	 * Array with default value
	 * @var array
	 */
	private $fields_old = array();

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

	private $instance_old = array();

	function __construct( I_Fields $fields_type ) {

		$this->fields_type = $fields_type;

		/**
		 * Define data by given attributes.
		 */
		$this->fields_old = require( ITALYSTRAP_PLUGIN_PATH . 'config/carousel.php' );

		$this->fields = require( ITALYSTRAP_PLUGIN_PATH . 'config/media-carousel.php' );

		$this->carousel_options = array(
			'orderby'		=> array(
					'menu_order'	=> __( 'Menu order (Default)', 'italystrap' ),
					'title'			=> __( 'Order by the image\'s title', 'italystrap' ),
					'post_date'		=> __( 'Sort by date/time', 'italystrap' ),
					'rand'			=> __( 'Order randomly', 'italystrap' ),
					'ID'			=> __( 'Order by the image\'s ID', 'italystrap' ),
				),
			'indicators'	=> array(
					'before-inner'	=> __( 'before-inner', 'italystrap' ),
					'after-inner'	=> __( 'after-inner', 'italystrap' ),
					'after-control'	=> __( 'after-control', 'italystrap' ),
					'false'			=> __( 'false', 'italystrap' ),
				),
			'control'		=> true,
			'pause'			=> array(
					'false'			=> __( 'none', 'italystrap' ),
					'hover'			=> __( 'hover', 'italystrap' ),
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
				'italystrap_gallery_types',
				array(
					'default' => __( 'Standard gallery', 'italystrap' ),
					'carousel' => __( 'Bootstrap Carousel', 'italystrap' ),
				)
			);

		// Enqueue the media UI only if needed.
		// if ( count( $this->gallery_types ) > 0 ) {
			add_action( 'wp_enqueue_media', array( $this, 'wp_enqueue_media' ) );
			add_action( 'print_media_templates', array( $this, 'print_media_templates_old' ) );
			// add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
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

		$translation_array = json_encode( require( ITALYSTRAP_PLUGIN_PATH . 'config/carousel.php' ) );
		// var_dump( $translation_array ); die();
		wp_localize_script( 'italystrap-gallery-settings', 'gallery_fields', $translation_array );

		wp_enqueue_script( 'italystrap-gallery-settings' );

	}

	/**
	 * Outputs a view template which can be used with wp.media.template
	 */
	function print_media_templates_old() {

		$default_gallery_type = apply_filters( 'italystrap_default_gallery_type', 'default' );

		echo '<script type="text/html" id="tmpl-italystrap-gallery-settings">';


		?><style>.setting select{float: right;}</style><?php

			$instance_old = array();

			$instance_old = wp_parse_args( (array) $instance_old, (array) $this->fields_old );

			$instance_old['name'] = $instance_old['name'] . $this->randID;

			?>
			<label class="setting">
				<span><?php esc_attr_e( 'Type', 'italystrap' ); ?></span>
				<select class="type" name="type" data-setting="type">
					<?php foreach ( $this->gallery_types as $value => $caption ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $default_gallery_type ); ?>><?php echo esc_html( $caption ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
			<div id="italystrap-carousel-option">
		<?php
		/**
		 * Instance of list of image sizes
		 * @var ItalyStrapAdminMediaSettings
		 */
		$image_size_media = new ItalyStrapAdminMediaSettings;
		$image_size_media_array = $image_size_media->get_image_sizes( array( 'full' => __( 'Real size', 'italystrap' ) ) );

		foreach ( $this->fields_old as $key => $label ) {

			$instance_old[ $key ] = isset( $instance_old[ $key ] ) ? $instance_old[ $key ] : '';

			/**
			 * Save select in widget
			 * @link https://wordpress.org/support/topic/wordpress-custom-widget-select-options-not-saving
			 * Display select only if is schema
			 */
			if ( 'ids' === $key || 'type' === $key || 'size' === $key ) {


			} elseif ( 'sizetablet' === $key || 'sizephone' === $key ) {

			?>
				<?php $saved_option = ( isset( $instance_old[ $key ] ) ) ? $instance_old[ $key ] : '' ; ?>

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

					<?php $saved_option = ( isset( $instance_old[ $key ] ) ) ? $instance_old[ $key ] : '' ; ?>

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

					if ( 'true' === $instance_old[ $key ] ) {
						$instance_old[ $key ] = true;
					}
				?>
					<label class="setting" for="<?php echo esc_attr( $key ); ?>">
						<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value='1' data-setting="<?php echo esc_attr( $key ); ?>" <?php checked( $instance_old[ $key ], true ); ?>>
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
				<!-- <p><?php esc_attr_e( 'For more informations about the options below see the <a href="admin.php?page=italystrap-documentation#carousel" target="_blank">documentation.</a>', 'italystrap' ); ?></p>
				<label class="setting">
					<span><?php esc_attr_e( 'Indicators', 'italystrap' ); ?></span>
					<select class="indicators" name="indicators" data-setting="indicators">
						<option value="" disabled selected>Select your option</option>
						<?php foreach ( $this->indicators as $value ) : ?>
							<option value="<?php if ( 'before-inner' !== $value ) echo esc_attr( $value ); ?>" ><?php echo esc_html( $value ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="setting">
					<span><?php esc_attr_e( 'HTML ID', 'italystrap' ); ?></span>
					<input type="text" name="name" value="" data-setting="name" placeholder="eg. myCssID">
				</label>
				<label class="setting">
					<span><?php esc_attr_e( 'Interval', 'italystrap' ); ?></span>
				</label>
				<input type="text" name="interval" value="" data-setting="interval" placeholder="5000"> -->

		<?php

		echo '</div></script>';
	}

	/**
	 * Outputs a view template which can be used with wp.media.template
	 */
	function print_media_templates() {

		$output = '';

		$default_gallery_type = apply_filters( 'italystrap_default_gallery_type', 'default' );

		$instance = array();

		$key = array(
			'name'		=> __( 'Type', 'italystrap' ),
			'desc'		=> __( 'Enter the widget class name.', 'italystrap' ),
			'id'		=> 'type',
			'_id'		=> 'type',
			'_name'		=> 'type',
			'type'		=> 'select',
			'class'		=> 'widefat',
			'data-setting'=> 'type',
			// 'class-p'	=> 'setting',
			'default'	=> $default_gallery_type,
			'options'	=> $this->gallery_types,
			// 'value'		=> 'Some value',
		 );

		$output = sprintf(
			'<script type="text/html" id="tmpl-italystrap-gallery-settings">%s<div id="italystrap-carousel-option">%s</div></script>',
			$this->fields_type->get_field_type( $key, $instance ),
			$this->get_field_types( $this->fields )
		);

		echo $output; // XSS ok.
	}

	/**
	 * Create Fields
	 *
	 * Creates each field defined.
	 *
	 * @access protected
	 * @param  array  $fields The fields array.
	 * @param  string $out    The HTML form output.
	 * @return string         Return the HTML Fields
	 */
	protected function get_field_types( array $fields, $out = '' ) {

		foreach ( $fields as $key ) {
			$out .= $this->get_field_type( $key );
		}

		return $out;

	}

	/**
	 * Create the Fields
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 *
	 * @return string      Return the HTML Fields
	 */
	protected function get_field_type( array $key ) {

		/**
		 * Set field id and name
		 */
		$key['_id'] = $key['_name'] = $key['data-setting'] = $key['id'];

		return $this->fields_type->get_field_type( $key, array() );

	}
}




/**
 * http://shibashake.com/wordpress-theme/how-to-expand-the-wordpress-media-manager-interface
 */


// add_filter( 'media_view_settings', 'my_media_view_settings', 10, 2 );

function my_media_view_settings( $settings, $post ) {

	// var_dump($post);
	// var_dump($settings);

	$post_types = get_post_types( array( 'public' => true ) );

	// Add in post types.
	foreach ( $post_types as $slug => $label ) {

		// if ( 'uploaded' === $slug ) {
		// 	unset( $settings['postTypes'][ $slug ] );
		// }

		if ( 'attachment' === $slug ) {
			continue;
		}
		$settings['postTypes'][ $slug ] = ucfirst( $label );

	}

	return $settings;
}

// Override relevant media manager javascript functions
// add_action( 'admin_print_footer_scripts', 'override_the_filter_object', 51 );
function override_the_filter_object() { ?>
	<script type="text/javascript">
	// Add custom post type filters
	l10n = wp.media.view.l10n = typeof _wpMediaViewsL10n === 'undefined' ? {} : _wpMediaViewsL10n;
	wp.media.view.AttachmentFilters.Uploaded.prototype.createFilters = function() {
		var type = this.model.get('type'),
			types = wp.media.view.settings.mimeTypes,
			text;
		if ( types && type )
			text = types[ type ];
 
		filters = {
			all: {
				text:  text || l10n.allMediaItems,
				props: {
					uploadedTo: null,
					orderby: 'date',
					order:   'DESC'
				},
				priority: 10
			},
 
			uploaded: {
				text:  l10n.uploadedToThisPost,
				props: {
					uploadedTo: wp.media.view.settings.post.id,
					orderby: 'menuOrder',
					order:   'ASC'
				},
				priority: 20
			}
		};
		console.log(this.options.controller._state);
		// Add post types only for gallery
		if (this.options.controller._state.indexOf('gallery') !== -1) {
			console.log(filters);
			console.log(filters.all);
			delete(filters.all);
			filters.image = {
				text:  'Images',
				props: {
					type:    'image',
					// uploadedTo: null,
					orderby: 'date',
					order:   'DESC'
				},
				priority: 10
			};
			_.each( wp.media.view.settings.postTypes || {}, function( text, key ) {
				filters[ key ] = {
					text: text,
					props: {
						type:    key,
						// uploadedTo: null,
						orderby: 'date',
						order:   'DESC'
					}
				};
			});
		}
		this.filters = filters;
		 
	}; // End create filters
// Add to my_override_filter_object function
wp.media.view.MediaFrame.Post.prototype.mainGalleryToolbar = function( view ) {
    var controller = this;
 
    this.selectionStatusToolbar( view );
 
    view.set( 'gallery', {
        style:    'primary',
        text:     l10n.createNewGallery,
        priority: 60,
        requires: { selection: true },
 
        click: function() {
            var selection = controller.state().get('selection'),
                edit = controller.state('gallery-edit');
//              models = selection.where({ type: 'image' });
 
            // Don't filter based on type
            edit.set( 'library', selection);
/*          edit.set( 'library', new wp.media.model.Selection( selection, {
                props:    selection.props.toJSON(),
                multiple: true
            }) );
*/                  
            this.controller.setState('gallery-edit');
        }
    });
};
	</script>
<?php }

// add_action( 'wp_ajax_query-attachments', 'my_wp_ajax_query_attachments', 1 );
function my_wp_ajax_query_attachments() {
	if ( ! current_user_can( 'upload_files' ) )
		wp_send_json_error();
 
	$query = isset( $_REQUEST['query'] ) ? (array) $_REQUEST['query'] : array();
	$query = array_intersect_key( $query, array_flip( array(
		's', 'order', 'orderby', 'posts_per_page', 'paged', 'post_mime_type',
		'post_parent', 'post__in', 'post__not_in',
	) ) );
 
	if (isset($query['post_mime_type']) && ($query['post_mime_type'] != "image")) {
		// post type
		$query['post_type'] = $query['post_mime_type'];
		$query['post_status'] = 'publish';
		unset($query['post_mime_type']);
	} else { 
		// image
		$query['post_type'] = 'attachment';
		$query['post_status'] = 'inherit';
		if ( current_user_can( get_post_type_object( 'attachment' )->cap->read_private_posts ) )
			$query['post_status'] .= ',private';
	}
	 
	$query = apply_filters( 'ajax_query_attachments_args', $query );
	$query = new WP_Query( $query );
 
	// $posts = array_map( 'wp_prepare_attachment_for_js', $query->posts );
	$posts = array_map( 'my_prepare_items_for_js', $query->posts );
	$posts = array_filter( $posts );
 
	wp_send_json_success( $posts );
}

function my_prepare_items_for_js($item) {
	switch($item->post_type) {
	case 'attachment':
		return wp_prepare_attachment_for_js($item);
	case 'post':
	case 'page':
	case 'gallery':
	default:
		return my_prepare_post_for_js($item);
	}
}
 
function my_prepare_post_for_js( $post ) {
	if ( ! $post = get_post( $post ) )
		return;
 
	$attachment_id = get_post_thumbnail_id( $post->ID );
	$attachment = get_post($attachment_id);
	$post_link = get_permalink( $post->ID );
 
	$type = $post->post_type; $subtype = 'none';
	if ($attachment) {
		$url = wp_get_attachment_url( $attachment->ID );
	} else { // Show default image
		$url = includes_url('images/crystal/default.png');
	}
	 
	$response = array(
		'id'          => $post->ID,
		'title'       => $post->post_title, 
		'filename'    => wp_basename( $post_link ), 
		'url'         => $url,
		'link'        => $post_link,
		'alt'         => '',
		'author'      => $post->post_author,
		'description' => $post->post_content,
		'caption'     => $post->post_excerpt,
		'name'        => $post->post_name,
		'status'      => $post->post_status,
		'uploadedTo'  => $post->post_parent,
		'date'        => strtotime( $post->post_date_gmt ) * 1000,
		'modified'    => strtotime( $post->post_modified_gmt ) * 1000,
		'menuOrder'   => '', // $attachment->menu_order,
		'mime'        => '', // $attachment->post_mime_type,
		'type'        => $type,
		'subtype'     => $subtype,
		'icon'        => $url, // wp_mime_type_icon( $attachment_id ),
		'dateFormatted' => mysql2date( get_option('date_format'), $post->post_date ),
		'nonces'      => array(
			'update' => false,
			'delete' => false,
		),
		'editLink'   => false,
	);
 
	// Don't allow delete or update for posts. So don't create nonces.
	 
	return apply_filters( 'wp_prepare_post_for_js', $response, $post );
}

