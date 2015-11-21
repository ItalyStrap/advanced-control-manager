<?php namespace ItalyStrap\Core;

use \WP_Widget;
use \ItalyStrapCarousel;
/**
 * Widget API: WP_Widget_Text class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */

/**
 * Core class used to implement a Text widget.
 *
 * @since 1.4.0
 *
 * @see WP_Widget
 */
class CarouselMediaWidget extends WP_Widget {

	/**
	 * Array with default value
	 * @var array
	 */
	private $fields = array();

	/**
	 * Sets up a new Bootstrap carousel widget instance.
	 *
	 * @since 1.4.0
	 * @access public
	 */
	public function __construct() {

		/**
		 * Define data by given attributes.
		 */
		$this->fields = array(

			/**
			 * Ids for the images to use.
			 */
			'ids' => false,

			/**
			 * Type of gallery. If it's not "carousel", nothing will be done.
			 */
			'type' => '',

			/**
			 * Alternative appearing order of images.
			 */
			'orderby' => '',

			/**
			 * Any name. String will be sanitize to be used as HTML ID. Recomended
			 * when you want to have more than one carousel in the same page.
			 * Default: italystrap-bootstrap-carousel.
			 * */
			'name' => 'media-bootstrap-carousel',

			/**
			 * Carousel container width, in px or %
			 */
			'width' => '',

			/**
			 * Carousel item height, in px or %
			 */
			'height' => '',

			/**
			 * Accepted values: before-inner, after-inner, after-control, false.
			 * Default: before-inner.
			 * */
			'indicators' => 'before-inner',

			/**
			 * Enable or disable arrow right and left
			 * Accepted values: true, false. Default: true.
			 */
			'control' => 'true',

			/**
			 * Add custom control icon
			 * @todo Aggiungere la possibilità di poter decidere quali simbili
			 *       usare come selettori delle immagini (@see Vedi sotto)
			 * Enable or disable arrow from Glyphicons
			 * Accepted values: true, false. Default: true.
			 * 'arrow' => 'true',
			 */

			/**
			 * Add custom control icon
			 * @todo Aggiungere inserimento glyphicon nello shortcode
			 *       decidere se fare inserire tutto lo span o solo l'icona
			 * 'control-left' => '<span class="glyphicon glyphicon-chevron-left"></span>',
			 * 'control-right' => '<span class="glyphicon glyphicon-chevron-right"></span>',
			 */

			/**
			 * The amount of time to delay between automatically
			 * cycling an item in milliseconds.
			 * @type integer Example 5000 = 5 seconds.
			 * Default 0, carousel will not automatically cycle.
			 * @link http://www.smashingmagazine.com/2015/02/09/carousel-usage-exploration-on-mobile-e-commerce-websites/
			 */
			'interval' => 0,

			/**
			 * Pauses the cycling of the carousel on mouseenter and resumes the
			 * cycling of the carousel on mouseleave.
			 * @type string Default hover.
			 */
			'pause' => 'hover',

			/**
			 * Define tag for image title. Default: h4.
			 */
			'titletag' => 'h4',

			/**
			 * Show or hide image title. Set false to hide. Default: true.
			 */
			'image_title' => 'true',

			/**
			 * Type of link to show if "title" is set to true.
			 * Default Link Parameters file, none, link
			 */
			'link' => '',

			/**
			 * Show or hide image text. Set false to hide. Default: true.
			 */
			'text' => 'true',

			/**
			 * Auto-format text. Default: true.
			 */
			'wpautop' => 'true',

			/**
			 * Extra class for container.
			 */
			'containerclass' => '',

			/**
			 * Extra class for item.
			 */
			'itemclass' => '',

			/**
			 * Extra class for caption.
			 */
			'captionclass' => '',

			/**
			 * Size for image attachment. Accepted values: thumbnail, medium,
			 * large, full or own custom name added in add_image_size function.
			 * Default: full.
			 * @see wp_get_attachment_image_src() for further reference.
			 */
			'size' => 'full',

			/**
			 * Activate responsive image. Accepted values: true, false.
			 * Default false.
			 */
			'responsive' => false,

			/**
			 * Size for image attachment. Accepted values: thumbnail, medium,
			 * large, full or own custom name added in add_image_size function.
			 * Default: large.
			 * @see wp_get_attachment_image_src() for further reference.
			 */
			'sizetablet' => 'large',

			/**
			 * Size for image attachment. Accepted values: thumbnail, medium,
			 * large, full or own custom name added in add_image_size function.
			 * Default: medium.
			 * @see wp_get_attachment_image_src() for further reference.
			 */
			'sizephone' => 'medium',

		);

		$widget_ops = array(
			'classname'		=> 'widget_italystrap_media_carousel',
			'description'	=> __( 'Use this widget to add a Bootstrap Media Carousel', 'ItalyStrap' ),
			);

		/**
		 * The width and eight of the widget in admin
		 * @var array
		 */
		$control_ops = array( 'width' => 350, 'height' => 350 );

		parent::__construct(
			'widget_italystrap_media_carousel',
			__( 'ItalyStrap: Bootstrap Media Carousel', 'ItalyStrap' ),
			$widget_ops,
			$control_ops
		);

		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'upload_scripts' ) );

	}

	/**
	 * Outputs the content for the current Bootstrap carousel widget instance.
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Bootstrap carousel widget instance.
	 */
	public function widget( $args, $instance ) {

		$cache = wp_cache_get( 'widget_italystrap_media_carousel', 'widget' );

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = null;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {

			echo $cache[ $args['widget_id'] ];
			return;

		}

		ob_start();

		$title = apply_filters(
			'italystrap_media_carousel_title',
			empty( $instance['title'] ) ? '' : $instance['title'],
			$instance,
			$this->id_base
		);

		// foreach ( $this->fields as $name => $label )
		// 	$instance[ $name ] = ! empty( $instance[ $name ] ) ? esc_attr( $instance[ $name ] ) : $this->fields[ $name ];

		echo $args['before_widget'];

		/**
		 * Print the optional widget title
		 */
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$instance['type'] = $instance['ids'] ? 'carousel' : '';

		$mediacarousel = new ItalyStrapCarousel( $instance );
		echo $mediacarousel->__get( 'output' );

		echo $args['after_widget'];

		$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'widget_italystrap_media_carousel', $cache, 'widget' );


	}

	/**
	 * Handles updating settings for the current Bootstrap carousel widget instance.
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		/**
		 * Sanitizzo l'array con array_map
		 * @var array
		 */
		$instance = array_map( 'sanitize_text_field', $new_instance );

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );

		if ( isset( $alloptions['widget_italystrap_media_carousel'] ) )
			delete_option( 'widget_italystrap_media_carousel' );

		return $instance;



	}

	/**
	 * Outputs the Bootstrap carousel widget settings form.
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_attr_e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<?php

		foreach ( $this->fields as $key => $label ) {

			${ $key } = ! empty( $instance[ $key ] ) ? esc_attr( $instance[ $key ] ) : $this->fields[ $key ];

			/**
			 * Save select in widget
			 * @link https://wordpress.org/support/topic/wordpress-custom-widget-select-options-not-saving
			 * Display select only if is schema
			 */
			if ( 'ids' === $key ) {

			?>
			<h5><?php esc_attr_e( 'Add your images', 'ItalyStrap' ); ?></h5>
			<hr>
			<p>
				<label for="<?php esc_attr_e( $this->get_field_id( $key ) ); ?>" style="display:none">
					<?php echo $key; ?>:
				</label>
				<input type="hidden" class="widefat ids" id="<?php esc_attr_e( $this->get_field_id( $key ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo ${ $key }; ?>" placeholder="<?php echo $label; ?>">
			</p>

<style>
/*.carousel-image:hover{
	cursor: move;
}
.carousel-image:hover .dashicons-no{
	background: white;
	border-radius: 50%;
	color: red !important;
	cursor: pointer !important;
	z-index: 10;
}
#media_carousel_sortable ul {list-style-type: none;min-height: 155px; overflow: hidden;}
#media_carousel_sortable ul:after {clear: both;content: "";display: block;}
#media_carousel_sortable li { margin: 3px 3px 3px 0; padding: 1px;float: left;}
#media_carousel_sortable li div{ position: relative;}
#media_carousel_sortable li div img:hover{ opacity:0.5; }
#media_carousel_sortable li div .dashicons-no{color: transparent; font-size: 20px; position: absolute; top: 10px; right: 10px;}*/
</style>
				<div id="media_carousel_sortable">
					<ul  id="sortable" class="carousel_images">
					<?php if ( ! empty( $ids ) ) : ?>
						<?php
						$images = explode( ',', $ids );
						foreach ( $images as $image ) :
							$image_attributes = wp_get_attachment_image_src( $image );
							if ( $image_attributes ) :
						?>
					
							<li class="carousel-image ui-state-default">
								<div>
									<i class="dashicons dashicons-no"></i>
									<img src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" data-id="<?php echo $image; ?>">
								</div>
							</li>
					
						<?php
							endif;
						endforeach; ?>
					<?php endif; ?>
					</ul>
				</div>
				<span style="clear:both;"></span>
				<input class="upload_carousel_image_button button button-primary" type="button" value="<?php esc_attr_e( 'Add Image', 'ItalyStrap' ); ?>" />
			<hr>
			<?php
			} else if ( 'schema' === $key ) {

			?>
			<p>
				<label for="<?php esc_attr_e( $this->get_field_id( $key ) ); ?>">
					<?php echo $label; ?>
				</label>
				<select name="<?php esc_attr_e( $this->get_field_name( $key ) ); ?>" id="<?php esc_attr_e( $this->get_field_id( $key ) ); ?>" style="width:100%;" id="selectSchema" class="selectSchema">

					<?php
					$option = '';
					foreach ( $this->schema as $key => $value )
						$option .= '<option ' . ( $selected = ( $key === ${$key} ) ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
					echo $option;
					?>
				</select>
			</p>
			<?php
			} else {
			?>
			<p>
				<label for="<?php esc_attr_e( $this->get_field_id( $key ) ); ?>">
					<?php echo esc_attr( $key ); ?>:
				</label>
				<input class="widefat" id="<?php esc_attr_e( $this->get_field_id( $key ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo ${$key}; ?>" placeholder="<?php echo $label; ?>">
			</p>
			<?php } //!- else
		}

	}

	/**
	 * Flush widget cache
	 */
	function flush_widget_cache() {

		wp_cache_delete( 'widget_italystrap_media_carousel', 'widget' );
	}

	/**
	 * Upload the Javascripts for the media uploader in widget config
	 *
	 * @param string $hook The name of the page.
	 */
	public function upload_scripts( $hook ) {

		if ( 'widgets.php' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'italystrap_widget', ITALYSTRAP_PLUGIN_URL . 'admin/css/widget.css' );

		if ( function_exists( 'wp_enqueue_media' ) ) {

			wp_enqueue_media();

		} else {

			$js_file = ( WP_DEBUG ) ? 'admin/js/src/widget.js' : 'admin/js/widget.min.js';

			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_script(
				'upload_media_widget',
				ITALYSTRAP_PLUGIN_URL . $js_file,
				array( 'jquery', 'jquery-ui-sortable' )
			);

		}

		wp_enqueue_script( 'jquery-ui-sortable' );

	}
}
