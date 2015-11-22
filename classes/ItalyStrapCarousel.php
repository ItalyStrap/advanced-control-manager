<?php
/**
 * ItalyStrap Carousel forked from Agnosia Bootstrap Carousel by AuSoft
 */

/**
 * ItalyStrapCarouselLoader
 *
 * Loads ItalyStrapCarousel to display a new carousel when needed. 
 *
 * @since   1.1
 */
if ( ! class_exists('ItalyStrapCarouselLoader') ) {

	class ItalyStrapCarouselLoader {

		function __construct() {

			add_filter( 'post_gallery', array( $this, 'gallery_shortcode' ), 10, 4 );
			add_filter( 'jetpack_gallery_types', array( $this, 'gallery_types' ) );
			// add_filter( 'ItalyStrap_gallery_types', array( $this, 'gallery_types' ), 999 );

		}

		function gallery_shortcode( $output = '', $atts, $content = false, $tag = false ) {

			/**
			 * @deprecated 1.4.0 Deprecated title attribute for shortcode, use image_title instead
			 */
			if ( ! empty( $atts['title'] ) )
				_deprecated_argument( __FUNCTION__, '1.4.0', __( 'Use $atts[\'image_title\'] instead of $atts[\'title\']', 'ItalyStrap' ) );

			$atts['image_title'] = ( isset( $atts['title'] ) ) ? $atts['title'] : null ;

			$ItalyStrapCarousel = new ItalyStrapCarousel( $atts );
			return $ItalyStrapCarousel->__get( 'output' );

		}

		function gallery_types( $gallery_types ) {

			$gallery_types['carousel'] = 'Bootstrap Carousel';
			return $gallery_types;

		}

	}

}

/**
 * ItalyStrapCarousel
 *
 * Display a Bootstrap Carousel based on selected images and their titles and
 * descriptions. You need to include the Bootstrap CSS and Javascript files on
 * your own; otherwise the class will not work.
 *
 * @package ItalyStrapCarousel
 * @version 1.0
 * @since   1.0
 */
if ( !class_exists('ItalyStrapCarousel') ) {

	class ItalyStrapCarousel {

		private $attributes = array();
		private $posts = array();
		private $container_style = '';
		private $item_style = '';
		private $output = '';

		function __construct( $atts ) {

			$this->attributes = $this->obtain_attributes( $atts );
			$this->output = '';
			if ( $this->validate_data() ) {
				do_action( 'ItalyStrap_carousel_before_init' );
				$this->container_style = $this->get_container_style();
				$this->item_style      = $this->get_item_style();
				$this->posts           = $this->get_posts();
				$this->output          = $this->get_output();
				do_action( 'ItalyStrap_carousel_init' );
			}

			// add_action( 'wp_footer', array( $this, 'get_javascript'), 9999 );
			/**
			 * Append javascript in static variable and print in front-end footer
			 */
			ItalyStrapGlobals::set( $this->get_javascript() );
		}

		public function __get( $property ) {

			if ( property_exists( $this, $property ) )
				return $this->$property;

		}

		public function __set( $property, $value ) {
			
			if ( property_exists( $this, $property ) )
				$this->$property = $value;

			return $this;
		}

		/**
		 * Obtain shortcode attributes.
		 *
		 * @return array Mixed array of shortcode attributes.
		 */
		private function obtain_attributes( $atts ) {

			/**
			 * Define data by given attributes.
			 */
			$attributes = shortcode_atts( array(

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
				'name' => 'italystrap-bootstrap-carousel',

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
				'sizephone' => 'medium'

			), $atts );

			$attributes = apply_filters( 'ItalyStrap_carousel_attributes', $attributes );

			return $attributes;

		} 

		/**
		 * Check if the received data can make a valid carousel.
		 *
		 * @return boolean
		 */
		private function validate_data() {
			// Initialize boolean.
			$bool = false;
			// Convert attributes to variables.
			// extract( $this->attributes );
			foreach($this->attributes as $key => $value)
				$$key = $value;

			/* Validate for necessary data */
			if (   isset( $ids ) 
				&& isset( $type )
				&& 'carousel' === $type 
			) $bool = true;

			return $bool;

		}

		/**
		 * Obtain posts array by given IDs.
		 *
		 * @return array Array of WordPress $post objects.
		 */
		private function get_posts() {
			$posts = array();

			// extract( $this->attributes );
			foreach($this->attributes as $key => $value)
				$$key = $value;

			$images = $this->make_array( $ids, $orderby );

			if ( is_array( $images ) and !empty( $images ) )
				foreach ( $images as $image_id )
					$posts[] = get_post( intval( $image_id ) , ARRAY_A );



			$posts = apply_filters( 'ItalyStrap_carousel_posts', $posts, $this->attributes );

			return $posts;

		}

		/**
		 * Define width of carousel container.
		 *
		 * @return string HTML result.
		 */
		private function get_container_style() {

			// extract( $this->attributes );
			foreach($this->attributes as $key => $value)
				$$key = $value;

			$container_style = '';

			if ( $width )
				$container_style = 'style="width:' . $width . ';"';


			$container_style = apply_filters( 'ItalyStrap_carousel_container_style', $container_style, $this->attributes );

			return $container_style;

		}

		/**
		 * Define height of carousel item.
		 *
		 * @return string HTML result.
		 */
		private function get_item_style() {

			// extract( $this->attributes );
			foreach($this->attributes as $key => $value)
				$$key = $value;

			$item_style = '';

			if ( $height )
				$item_style = 'style="height:' . $height . ';"' ;

			$item_style = apply_filters( 'ItalyStrap_carousel_item_style', $item_style, $this->attributtes );

			return $item_style;
		}

		/**
		 * Obtain complete HTML output for carousel.
		 * 
		 * @return string HTML result.
		 */
		private function get_output() {

			// Convert attributes to variables.
			// extract( $this->attributes );
			foreach($this->attributes as $key => $value)
				$$key = $value;

			// Initialize carousel HTML.
			$output = $this->get_carousel_container( 'start' );
			// Try to obtain indicators before inner.
			$output .= ( 'before-inner' === $indicators ) ? $this->get_indicators() : '' ;
			// Initialize inner.
			$output .= $this->get_carousel_inner( 'start' );
			// Start counter for posts iteration.
			$i = 0;

			// Process each item into $this->posts array and create HTML.
			foreach ( $this->posts as $post )
				// Make sure to include only attachments into the carousel.
				if ( 'attachment' === $post['post_type'] ) {
					$class = ( 0 === $i ) ? 'active ' : '';
					$output .= $this->get_img_container( 'start', $i );
					$output .= $this->get_img( $post, $i );
					if ( 'false' !== $image_title || 'false' !== $text ) {
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
			$output .= ( 'after-inner' === $indicators ) ? $this->get_indicators() : '' ;
			// Obtain links for carousel control.
			$output .= ( 'false' !== $control ) ? $this->get_control() : '' ;
			// Try to obtain indicators after control. */
			$output .= ( 'after-control' === $indicators ) ? $this->get_indicators() : '' ;
			// End carousel HTML.
			$output .= $this->get_carousel_container( 'end' );
			// Obtain javascript for carousel.
			// $output .= $this->get_javascript();

			$output = apply_filters( 'ItalyStrap_carousel_output', $output, $this->attributes );

			return $output;

		}

		/**
		 * Get starting and ending HTML tag for carousel container.
		 * 
		 * @param  string $position Indicator for starting or ending tag.
		 * @return string           HTML result.
		 */
		private function get_carousel_container( $position ) {

			$output = '';

			switch ( $position ) {
				case 'start':
					extract( $this->attributes );
					$output .= '<div id="' . $name . '" class="carousel slide ' . $containerclass . '" ' . $this->container_style . '>';
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
		private function get_carousel_inner( $position ) {

			$output = '';

			switch ( $position ) {
				case 'start':
					extract( $this->attributes );
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
		private function get_caption_container( $position ) {

			$output = '';

			switch ( $position ) {
				case 'start':
					extract( $this->attributes );
					$output .= '<div class="carousel-caption ' . $captionclass . '" itemprop="caption">';
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
		private function get_img_container( $position, $i = 0 ) {

			$output = '';

			switch ( $position ) {
				case 'start':
					extract( $this->attributes );
					$class = ( $i == 0 ) ? 'active ' : '';
					$output .= '<div class="' . $class . 'item carousel-item ' . $itemclass . '" data-slide-no="' . $i . '" ' . $this->item_style . ' itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
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
		 * @param  array $post A WordPress $post object.
		 * @return string      HTML result.
		 */
		private function get_img( $post, $schemaposition ) {
			
			// extract( $this->attributes );
			foreach($this->attributes as $key => $value)
				$$key = $value;

			global $detect;

			/**
			 * Array for exifData informations
			 * @link https://wordpress.org/support/topic/retrieving-exif-data?replies=5
			 * @var array
			 */
			$imgmeta = wp_get_attachment_metadata( $post['ID'] );
			$imgmeta = $imgmeta[ 'image_meta' ];
			
			$image_size = '';

			if ( $detect->isTablet() && $responsive )
				$image_size = $sizetablet;

			elseif( $detect->isMobile() && $responsive )
				$image_size = $sizephone;

			else
				$image_size = $size;

			$exifdata = '';

			foreach ($imgmeta as $key => $value)
				if ( !empty( $value ) )
					$exifdata .= '<meta  itemprop="exifData" content="' . $key . ': ' . $value . '"/>';

			$output = '';

			/**
			 * Get the image attribute
			 * @var array
			 */
			$image = wp_get_attachment_image_src( $post['ID'] , $image_size );

			/**
			 * Escape img value for security reasone
			 */
			$image[0] = esc_url( $image[0] );
			$image[1] = esc_attr( $image[1] );
			$image[2] = esc_attr( $image[2] );

			/**
			 * style="max-height:' . $image[2] . 'px"
			 * Is in testing
			 */
			$output .= '<img class="img-responsive" alt="' . esc_attr( $post['post_title'] ) . '" src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" itemprop="image" style="max-height:' . $image[2] . 'px"/><meta  itemprop="name" content="' . esc_attr( $post['post_title'] ) . '"/><meta  itemprop="width" content="' . $image[1] . '"/><meta  itemprop="height" content="' . $image[2] . '"/><meta  itemprop="position" content="' . $schemaposition . '"/>' . $exifdata;

			$output = apply_filters( 'ItalyStrap_carousel_img', $output, $image[0], $this->attributes, $post );

			return $output;
		}

		/**
		 * Obtain the HTML-formatted title for an image.
		 *
		 * @return string HTML result.
		 */
		private function get_title( $post ) {

			// extract( $this->attributes );
			foreach($this->attributes as $key => $value)
				$$key = $value;

			$output = '';
			if ( $image_title !== 'false' ) :
				switch ( $link ) {
				 	case 'file':
				 		$post_title = '<a href="' . $post['guid'] . '" itemprop="url">' . esc_attr( $post['post_title'] ) . '</a>';
				 		break;
				 	case 'none':
				 		$post_title = esc_attr( $post['post_title'] );
				 		break;
				 	default:
				 		$post_title = '<a href="' . esc_url( get_permalink( $post['ID'] ) ). '" itemprop="url">' . esc_attr( $post['post_title'] ) . '</a>';
				 		break;
				 } 
				$output .= '<'. $titletag .'>' . $post_title . '</' . $titletag . '>';
			endif;
			$output = apply_filters( 'ItalyStrap_carousel_title', $output, $this->attributes );
			return $output;
		}

		/**
		 * Get the excerpt for an image.
		 *
		 * @return string HTML result.
		 */
		private function get_excerpt( $post ) {

			// extract( $this->attributes );
			foreach($this->attributes as $key => $value)
				$$key = $value;

			$output = '';

			if ( 'false' !== 'text' )
				$output .= ( $wpautop != 'false' ) ? wpautop( $post['post_excerpt'] ) : $post['post_excerpt'];

			$output = '<div itemprop="description">' . $output . '</div>';

			$output = apply_filters( 'ItalyStrap_carousel_excerpt', $output, $this->attributes );

			return $output;

		}


		/**
		 * Obtain indicators from $this->posts array.
		 *
		 * @return string HTML result.
		 */
		private function get_indicators() {

			// extract( $this->attributes );
			foreach($this->attributes as $key => $value)
				$$key = $value;

			$output = '<ol class="carousel-indicators">';
			$i = 0;

			foreach ( $this->posts as $post )
				// Make sure to include only attachments into the carousel.
				if ( 'attachment' === $post['post_type'] ) {
					$class = ( $i === 0 ) ? 'active' : '';
					$output .= '<li data-target="#' . $name . '" data-slide-to="' . $i . '" class="' . $class . '"></li>';
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
		private function get_control() {

			// extract( $this->attributes );
			foreach($this->attributes as $key => $value)
				$$key = $value;

			/**
			 * @todo Dare la possibilità di scegliere l'icona o l'inserimento di un carattere
			 */
			$output = '<a class="carousel-control left" data-slide="prev" role="button" href="#' . $name . '" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>';

			$output .= '<a class="carousel-control right" data-slide="next" role="button" href="#' . $name . '" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>';

			$output = apply_filters( 'ItalyStrap_carousel_control', $output, $this->attributes );

			return $output;

		}

		/**
		 * Get Javascript for carousel.
		 *
		 * @return string HTML script tag.
		 */
		function get_javascript() {

			// extract( $this->attributes );
			// foreach($this->attributes as $key => $value)
			// 	$$key = $value;

			// $output = '<script type="text/javascript">// <![CDATA[
			// 	jQuery(document).ready(function($) {$(\'#' . $name . '\').carousel({ interval : ' . $interval . ' , pause : "' . $pause . '" });
			// 	 });
			// 	// ]]></script>';

			$pause = ( '1' === $this->attributes['pause'] ) ? ',pause:"' . $this->attributes['pause'] . '"' : '' ;

			/**
			 * LazyLoad for Bootstrap carousel
			 * http://stackoverflow.com/questions/27675968/lazy-load-not-work-in-bootstrap-carousel
			 * http://jsfiddle.net/51muqdLf/5/
			 */
			$lazyload = 'var cHeight = 0;$("#' . $this->attributes['name'] . '").on("slide.bs.carousel", function(){var $nextImage = $(".active.item", this).next(".item").find("img");var src = $nextImage.data("src");if (typeof src !== "undefined" && src !== ""){$nextImage.attr("src", src);$nextImage.data("src", "");}});';

			$output = 'jQuery(document).ready(function($){$(\'#' . $this->attributes['name'] . '\').carousel({interval:' . $this->attributes['interval'] . $pause .' });' . $lazyload . '});';

			$output = apply_filters( 'ItalyStrap_carousel_javascript', $output, $this->attributes );

			return $output;

		}

		/**
		 * Obtain array of id given comma-separated values in a string.
		 * 
		 * @param  string $string  Comma-separated IDs of posts.
		 * @param  string $orderby Alternative order for array to be returned.
		 * @return array           Array of WordPress post IDs.
		 */
		private function make_array( $string, $orderby = '' ) {

			$array = explode( ',' , $string );

			// Support for random order.
			if ( 'rand' === $orderby )
				shuffle( $array );

			$array = apply_filters( 'ItalyStrap_carousel_make_array', $array, $this->attributes );

			return $array;

		}

	}
}
