<?php namespace ItalyStrap\Core;
/**
 * ItalyStrap Carousel initially forked from Agnosia Bootstrap Carousel by AuSoft
 *
 * Display a Bootstrap Carousel based on selected images and their titles and
 * descriptions. You need to include the Bootstrap CSS and Javascript files on
 * your own; otherwise the class will not work.
 *
 * @todo https://codex.wordpress.org/it:Shortcode_Gallery Aggiungere parametri mancanti
 *
 * @package ItalyStrapCarousel
 * @version 1.0
 * @since   1.0
 */
if ( ! class_exists( 'ItalyStrapCarousel' ) ) {

	/**
	 * The Carousel Bootsrap class
	 */
	class ItalyStrapCarousel {

		/**
		 * The attribute for Carousel
		 * @var array
		 */
		public $attributes = array();

		/**
		 * Array of WordPress $post objects.
		 * @var array
		 */
		public $posts = array();

		/**
		 * The width of the container
		 * @var string
		 */
		public $container_style = '';

		/**
		 * The height of the item
		 * @var string
		 */
		public $item_style = '';

		/**
		 * The carousel output
		 * @var string
		 */
		public $output = '';

		/**
		 * Initialize the contstructor
		 * @param array $atts The carousel attribute.
		 */
		function __construct( $atts ) {

			$this->attributes = $this->get_attributes( $atts );
			$this->output = '';
			if ( $this->validate_data() ) {
				do_action( 'ItalyStrap_carousel_before_init' );
				$this->container_style = $this->get_container_style();
				$this->item_style      = $this->get_item_style();
				$this->posts           = $this->get_posts();
				$this->output          = $this->get_output();
				do_action( 'ItalyStrap_carousel_init' );
			}

			/**
			 * Append javascript in static variable and print in front-end footer
			 */
			\ItalyStrapGlobals::set( $this->get_javascript() );
		}

		/**
		 * Magic methods __get
		 * @param  string $property The $property argument is the name of the property being interacted with.
		 * @return string           Return the $property argument.
		 */
		public function __get( $property ) {

			if ( property_exists( $this, $property ) )
				return $this->$property;

		}

		/**
		 * Magic methods __set
		 * @param string $property The $property argument is the name of the property being interacted with.
		 * @param mixed  $value    The __set() method's $value argument specifies the value the $name'ed property should be set to.
		 */
		public function __set( $property, $value ) {

			if ( property_exists( $this, $property ) )
				$this->$property = $value;

			return $this;
		}

		/**
		 * Get shortcode attributes.
		 *
		 * @param  array $atts The carousel attribute.
		 * @return array Mixed array of shortcode attributes.
		 */
		public function get_attributes( $atts ) {

			/**
			 * Define data by given attributes.
			 */
			$attributes = shortcode_atts( require( ITALYSTRAP_PLUGIN_PATH . 'options/options-carousel.php' ), $atts, 'gallery' );

			$attributes = apply_filters( 'ItalyStrap_carousel_attributes', $attributes );

			return $attributes;

		}

		/**
		 * Check if the received data can make a valid carousel.
		 *
		 * @return boolean
		 */
		public function validate_data() {
			// Initialize boolean.
			$bool = false;

			/* Validate for necessary data */
			if ( isset( $this->attributes['ids'] )
				&& isset( $this->attributes['type'] )
				&& 'carousel' === $this->attributes['type']
			) $bool = true;

			return $bool;

		}

		/**
		 * Obtain posts array by given IDs.
		 *
		 * @return array Array of WordPress $post objects.
		 */
		public function get_posts() {

			$posts = array();

			$post_type_ids = $this->make_array( $this->attributes['ids'], $this->attributes['orderby'] );

			if ( is_array( $post_type_ids ) and ! empty( $post_type_ids ) )
				foreach ( $post_type_ids as $post_type_id )
					$posts[] = get_post( intval( $post_type_id ) , ARRAY_A );

			$posts = apply_filters( 'ItalyStrap_carousel_posts', $posts, $this->attributes );

			return $posts;

		}

		/**
		 * Define width of carousel container.
		 *
		 * @return string HTML result.
		 */
		public function get_container_style() {

			$container_style = '';

			if ( $this->attributes['width'] )
				$container_style = 'style="width:' . $this->attributes['width'] . ';"';

			$container_style = apply_filters( 'ItalyStrap_carousel_container_style', $container_style, $this->attributes );

			return $container_style;

		}

		/**
		 * Define height of carousel item.
		 *
		 * @return string HTML result.
		 */
		public function get_item_style() {

			$item_style = '';

			if ( $this->attributes['height'] )
				$item_style = 'style="height:' . $this->attributes['height'] . ';"' ;

			$item_style = apply_filters( 'ItalyStrap_carousel_item_style', $item_style, $this->attributtes );

			return $item_style;
		}

		/**
		 * Obtain complete HTML output for carousel.
		 *
		 * @return string HTML result.
		 */
		public function get_output() {

			// Initialize carousel HTML.
			$output = $this->get_carousel_container( 'start' );
			// Try to obtain indicators before inner.
			$output .= ( 'before-inner' === $this->attributes['indicators'] ) ? $this->get_indicators() : '' ;
			// Initialize inner.
			$output .= $this->get_carousel_inner( 'start' );
			// Start counter for posts iteration.
			$i = 0;

			/**
			 * Process each item into $this->posts array and create HTML.
			 *
			 * @todo $post viene creato da get_post() che è usato anche dentro
			 *       $this->get_img con la funzione wp_get_attachment_image()
			 *       Valutare se è possibile ridurre a una sola chiamata di get_post()
			 */
			foreach ( $this->posts as $post ) {

				$class = ( 0 === $i ) ? 'active ' : '';
				$output .= $this->get_img_container( 'start', $i );
				$output .= $this->get_img( $post, $i );
				if ( 'false' !== $this->attributes['image_title'] || 'false' !== $this->attributes['text'] ) {
					$output .= $this->get_caption_container( 'start' );
					$output .= $this->get_title( $post );
					$output .= $this->get_excerpt( $post );
					$output .= $this->get_caption_container( 'end' );
				}
				$output .= $this->get_img_container( 'end' );
				$i++;

			}

			// End inner.
			$output .= $this->get_carousel_inner( 'end' );
			// Try to obtain indicators after inner.
			$output .= ( 'after-inner' === $this->attributes['indicators'] ) ? $this->get_indicators() : '' ;
			// Obtain links for carousel control.
			$output .= ( 'false' !== $this->attributes['control'] ) ? $this->get_control() : '' ;
			// Try to obtain indicators after control.
			$output .= ( 'after-control' === $this->attributes['indicators'] ) ? $this->get_indicators() : '' ;
			// End carousel HTML.
			$output .= $this->get_carousel_container( 'end' );

			$output = apply_filters( 'ItalyStrap_carousel_output', $output, $this->attributes );

			return $output;

		}

		/**
		 * Get starting and ending HTML tag for carousel container.
		 *
		 * @param  string $position Indicator for starting or ending tag.
		 * @return string           HTML result.
		 */
		public function get_carousel_container( $position ) {

			$output = '';

			switch ( $position ) {
				case 'start':
					$output .= '<div id="' . $this->attributes['name'] . '" class="carousel slide ' . $this->attributes['containerclass'] . '" ' . $this->container_style . '>';
					break;
				case 'end':
					$output .= '</div>';
					break;
				default:
					// Do nothing.
					break;
			}

			$output = apply_filters( 'ItalyStrap_carousel_container', $output, $this->attributes );

			return $output;
		}

		/**
		 * Get starting and ending HTML tag for carousel inner element.
		 *
		 * @param  string $position Indicator for starting or ending tag.
		 * @return string           HTML result.
		 */
		public function get_carousel_inner( $position ) {

			$output = '';

			switch ( $position ) {
				case 'start':
					$output = '<div class="carousel-inner" itemscope itemtype="http://schema.org/ImageGallery">';
					break;
				case 'end':
					$output = '</div>';
					break;
				default:
					// Do nothing.
					break;
			}

			$output = apply_filters( 'ItalyStrap_carousel_inner', $output, $this->attributes );

			return $output;

		}

		/**
		 * Get starting and ending HTML tag for container of a caption.
		 *
		 * @param  string $position Indicator for starting or ending tag.
		 * @return string           HTML result.
		 */
		public function get_caption_container( $position ) {

			$output = '';

			switch ( $position ) {
				case 'start':
					$output .= '<div class="carousel-caption ' . $this->attributes['captionclass'] . '" itemprop="caption">';
					break;
				case 'end':
					$output .= '</div>';
					break;
				default:
					// Do nothing.
					break;
			}

			$output = apply_filters( 'ItalyStrap_carousel_caption_container', $output, $this->attributes );

			return $output;

		}

		/**
		 * Get starting and ending HTML tag for container of a gallery item.
		 *
		 * @param  string  $position Indicator for starting or ending tag.
		 * @param  integer $i        Position of the current item into carousel.
		 * @return string            HTML result.
		 */
		public function get_img_container( $position, $i = 0 ) {

			$output = '';

			switch ( $position ) {
				case 'start':
					$class = ( 0 === $i ) ? 'active ' : '';
					$output .= '<div class="' . $class . 'item carousel-item ' . $this->attributes['itemclass'] . '" data-slide-no="' . $i . '" ' . $this->item_style . ' itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
					break;
				case 'end':
					$output .= '</div>';
					break;
				default:
					// Do nothing.
					break;
			}

			$output = apply_filters( 'ItalyStrap_carousel_img_container', $output, $this->attributes );

			return $output;

		}

		/**
		 * Get HTML-formatted image for a carousel item.
		 *
		 * @param  array $post           A WordPress $post object.
		 * @param  array $schemaposition The position of the item for schema.org.
		 * @return string                HTML result.
		 */
		public function get_img( $post, $schemaposition ) {

			$post_thumbnail_id = ( 'attachment' === $post['post_type'] ) ? ( (int) $post['ID'] ) : ( (int) get_post_thumbnail_id( $post['ID'] ) ) ;

			if ( empty( $post_thumbnail_id ) ) {
				return;
			}

			$size_class = $this->get_img_size_attr();

			$img_attr = array();

			/**
			 * Get the image attribute
			 * [0] => url
			 * [1] => larghezza
			 * [2] => altezza
			 * [3] => boolean: true se $url è un'immagine ridimensionata,
			 *        false se è l'originale.
			 * @var array
			 */
			$img_attr = wp_get_attachment_image_src( $post_thumbnail_id, $size_class );

			$attr = array(
				'class'		=> "center-block img-responsive attachment-$size_class size-$size_class",
				'itemprop'	=> 'image',
				'style'		=> 'max-height:' . absint( $img_attr[2] ) . 'px',
				);

			$output = wp_get_attachment_image( $post_thumbnail_id , $size_class, false, $attr );

			$output .= $this->get_img_metadata( $post_thumbnail_id, $post, $img_attr, $schemaposition );

			$output = apply_filters( 'ItalyStrap_carousel_img', $output, $img_attr, $this->attributes, $post );

			return $output;
		}

		/**
		 * Get the image metadata (built for get the exif data)
		 * @param  int   $id             ID.
		 * @param  array $post           Array of post.
		 * @param  array $img_attr       Attributes of image.
		 * @param  int   $schemaposition The position number of image.
		 * @return string                The metadata fo image.
		 */
		public function get_img_metadata( $id, array $post, array $img_attr, $schemaposition ) {

			/**
			 * Array for exifData informations
			 * @link https://wordpress.org/support/topic/retrieving-exif-data?replies=5
			 * @var array
			 */
			$imgmeta = wp_get_attachment_metadata( $id );
			$imgmeta = ( isset( $imgmeta['image_meta'] ) ) ? $imgmeta['image_meta'] : array();

			/**
			 * The metadata of the image.
			 * @var string
			 */
			$metadata = '<meta  itemprop="name" content="' . esc_attr( $post['post_title'] ) . '"/><meta  itemprop="width" content="' . absint( $img_attr[1] ) . '"/><meta  itemprop="height" content="' . absint( $img_attr[2] ) . '"/><meta  itemprop="position" content="' . $schemaposition . '"/>';

			foreach ( $imgmeta as $key => $value )
				if ( ! empty( $value ) )
					$metadata .= '<meta  itemprop="exifData" content="' . esc_attr( $key ) . ': ' . esc_attr( $value ) . '"/>';

			return $metadata;
		}

		/**
		 * Get the image size attribute from user options and selecte wich to use on desktop, tablet or mobile.
		 * @return string The image size selected
		 */
		public function get_img_size_attr() {

			global $detect;

			$image_size = '';

			if ( $detect->isTablet() && $responsive )
				$image_size = $this->attributes['sizetablet'];

			elseif ( $detect->isMobile() && $responsive )
				$image_size = $this->attributes['sizephone'];

			else $image_size = $this->attributes['size'];

			return $image_size;
		}

		/**
		 * Obtain the HTML-formatted title for an image.
		 *
		 * @param array $post The post object.
		 * @return string HTML result.
		 */
		public function get_title( $post ) {

			$post_thumbnail_id = ( 'attachment' === $post['post_type'] ) ? ( (int) $post['ID'] ) : ( (int) get_post_thumbnail_id( $post['ID'] ) ) ;

			if ( empty( $post_thumbnail_id ) ) {
				return;
			}

			$size_class = $this->get_img_size_attr();
			$img_attr = wp_get_attachment_image_src( $post_thumbnail_id, $size_class );

			$link_file = ( 'attachment' === $post['post_type'] ) ? $post['guid'] : $img_attr[0];

			$output = '';

			if ( 'false' !== $this->attributes['image_title'] ) :

				switch ( $this->attributes['link'] ) {

				 	case 'file':
				 		$post_title = '<a href="' . esc_url( $link_file ) . '" itemprop="url">' . esc_attr( $post['post_title'] ) . '</a>';
				 		break;
				 	case 'none':
				 		$post_title = esc_attr( $post['post_title'] );
				 		break;
				 	default:
				 		$post_title = '<a href="' . esc_url( get_permalink( $post['ID'] ) ). '" itemprop="url">' . esc_attr( $post['post_title'] ) . '</a>';
				 		break;

				}

				$output .= '<'. esc_attr( $this->attributes['titletag'] ) .'>' . $post_title . '</' . esc_attr( $this->attributes['titletag'] ) . '>';

			endif;

			$output = apply_filters( 'ItalyStrap_carousel_title', $output, $this->attributes );

			return $output;

		}

		/**
		 * Get the excerpt for an image.
		 *
		 * @param array $post The post object.
		 * @return string     HTML result.
		 */
		public function get_excerpt( $post ) {

			/**
			 * Da fare.
			 * @todo L'excerpt lo prende dal post se l'Id è quello di un post
			 *       Valutare se farlo prendere dall'immagine come comportamento standard
			 */

			$output = '';

			if ( 'false' !== $this->attributes['text'] )
				$output .= ( 'false' !== $this->attributes['wpautop'] ) ? wpautop( esc_attr( $post['post_excerpt'] ) ) : esc_attr( $post['post_excerpt'] );

			$output = '<div itemprop="description">' . $output . '</div>';

			$output = apply_filters( 'ItalyStrap_carousel_excerpt', $output, $this->attributes );

			return $output;

		}


		/**
		 * Obtain indicators from $this->posts array.
		 *
		 * @return string HTML result.
		 */
		public function get_indicators() {

			$output = '<ol class="carousel-indicators">';
			$i = 0;

			/**
			 * Da fare.
			 * @todo fare il foreach solo sul numero degli ID validi nell'array degli ID
			 */
			foreach ( $this->posts as $post ) {

					$class = ( 0 === $i ) ? 'active' : '';
					$output .= '<li data-target="#' . $this->attributes['name'] . '" data-slide-to="' . $i . '" class="' . $class . '"></li>';
					$i++;

			}

			$output .= '</ol>';

			$output = apply_filters( 'ItalyStrap_carousel_indicators', $output, $this->attributes );

			return $output;
		}


		/**
		 * Obtain control links.
		 *
		 * @return string HTML control result.
		 */
		public function get_control() {

			/**
			 * THe output of the controllers
			 * @todo Dare la possibilità di scegliere l'icona o l'inserimento di un carattere
			 */
			$output = '<a class="carousel-control left" data-slide="prev" role="button" href="#' . $this->attributes['name'] . '" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>';

			$output .= '<a class="carousel-control right" data-slide="next" role="button" href="#' . $this->attributes['name'] . '" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>';

			$output = apply_filters( 'ItalyStrap_carousel_control', $output, $this->attributes );

			return $output;

		}

		/**
		 * Get Javascript for carousel.
		 *
		 * @return string HTML script tag.
		 */
		function get_javascript() {

			$pause = ( '1' === $this->attributes['pause'] ) ? ',pause:"' . $this->attributes['pause'] . '"' : '' ;

			/**
			 * LazyLoad for Bootstrap carousel
			 * http://stackoverflow.com/questions/27675968/lazy-load-not-work-in-bootstrap-carousel
			 * http://jsfiddle.net/51muqdLf/5/
			 */
			$lazyload = 'var cHeight = 0;$("#' . $this->attributes['name'] . '").on("slide.bs.carousel", function(){var $nextImage = $(".active.item", this).next(".item").find("img");var src = $nextImage.data("src");if (typeof src !== "undefined" && src !== ""){$nextImage.attr("src", src);$nextImage.data("src", "");}});';

			$output = 'jQuery(document).ready(function($){$(\'#' . $this->attributes['name'] . '\').carousel({interval:' . $this->attributes['interval'] . $pause .' });' . $lazyload . '});';

			$output = apply_filters( 'ItalyStrap_carousel_javascript', $output, $this->attributes, $lazyload );

			return $output;

		}

		/**
		 * Obtain array of id given comma-separated values in a string.
		 *
		 * @param  string $string  Comma-separated IDs of posts.
		 * @param  string $orderby Alternative order for array to be returned.
		 * @return array           Array of WordPress post IDs.
		 */
		public function make_array( $string, $orderby = '' ) {

			$array = explode( ',' , $string );

			// Support for random order.
			if ( 'rand' === $orderby )
				shuffle( $array );

			$array = apply_filters( 'ItalyStrap_carousel_make_array', $array, $this->attributes );

			return $array;

		}
	}
}
