<?php namespace ItalyStrap\Core;

use \WP_Widget;
use \ItalyStrapCarousel;
use \ItalyStrapAdminMediaSettings;
/**
 * Widget API: CarouselMediaWidget class
 *
 * @package ItalyStrap
 * @since 1.4.0
 */

/**
 * Core class used to implement a Bootstrap Carousel widget.
 *
 * @since 1.4.0
 *
 * @see WP_Widget
 */
class CarouselMediaWidget extends Widget {

	/**
	 * Array with default value
	 * @var array
	 */
	private $fields = array();

	private $carousel_options = array();

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
		$this->fields = require( ITALYSTRAP_PLUGIN_PATH . 'options/options-carousel.php' );

		$this->carousel_options = array(
			'orderby'		=> array(
					'menu_order'	=> __( 'menu_order (Default)', 'ItalyStrap' ),
					'title'			=> __( 'title', 'ItalyStrap' ),
					'post_date'		=> __( 'post_date', 'ItalyStrap' ),
					'rand'			=> __( 'rand', 'ItalyStrap' ),
					'ID'			=> __( 'ID', 'ItalyStrap' ),
				),
			'indicators'	=> array(
					'before-inner'	=> __( 'before-inner', 'ItalyStrap' ),
					'after-inner'	=> __( 'after-inner', 'ItalyStrap' ),
					'after-control'	=> __( 'after-control', 'ItalyStrap' ),
					'false'	=> __( 'false', 'ItalyStrap' ),
				),
			'control'		=> array(
				'true'	=> __( 'true', 'ItalyStrap' ),
				'false'	=> __( 'false', 'ItalyStrap' ),
				),
			'pause'			=> array(
					'false'	=> __( 'none', 'ItalyStrap' ),
					'hover'	=> __( 'hover', 'ItalyStrap' ),
				),
			'image_title'	=> array(
				'true'	=> __( 'true', 'ItalyStrap' ),
				'false'	=> __( 'false', 'ItalyStrap' ),
				),
			'text'			=> array(
				'true'	=> __( 'true', 'ItalyStrap' ),
				'false'	=> __( 'false', 'ItalyStrap' ),
				),
			'wpautop'		=> array(
				'true'	=> __( 'true', 'ItalyStrap' ),
				'false'	=> __( 'false', 'ItalyStrap' ),
				),


			);

		$widget_ops = array(
			'classname'		=> 'widget_italystrap_media_carousel',
			'description'	=> __( 'Use this widget to add a Bootstrap Media Carousel', 'ItalyStrap' ),
			);

		/**
		 * The width and eight of the widget in admin
		 * @var array
		 */
		$control_ops = array(
			// 'width' => 350,
			// 'height' => 350
			);

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

		$instance['type'] = 'carousel';
		$instance['name'] = 'media-bootstrap-carousel';


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
			} else if ( 'size' === $key ) {

				// var_dump(new ItalyStrapAdminMediaSettings);

				$image_size = new ItalyStrapAdminMediaSettings;
var_dump( $image_size->get_image_sizes() );
			?>
			<p>
				<label for="<?php esc_attr_e( $this->get_field_id( $key ) ); ?>">
					<?php echo $label; ?>
				</label>
				<input class="widefat" id="<?php esc_attr_e( $this->get_field_id( $key ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo ${ $key }; ?>" placeholder="<?php echo $label; ?>">
			</p>
			<?php
			} else {
			?>
			<p>
				<label for="<?php esc_attr_e( $this->get_field_id( $key ) ); ?>">
					<?php echo esc_attr( $key ); ?>:
				</label>
				<?php if ( isset( $this->carousel_options[ $key ] ) && is_array( $this->carousel_options[ $key ] ) ) :

					$saved_option = ( isset( ${$key} ) ) ? ${$key} : '' ;
				?>
				<select name="<?php esc_attr_e( $this->get_field_name( $key ) ); ?>" id="<?php esc_attr_e( $this->get_field_id( $key ) ); ?>" class="widefat">

					<?php
					$option = '';

					foreach ( $this->carousel_options[ $key ] as $key => $value ) {

						$option .= '<option ' . ( selected( $key, $saved_option ) ) . ' value="' . $key . '">' . $value . '</option>';

					}

					echo $option;
					?>
				</select>
				<?php else : ?>
				<input class="widefat" id="<?php esc_attr_e( $this->get_field_id( $key ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo ${$key}; ?>" placeholder="<?php echo $label; ?>">
				<?php endif; ?>
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
}
