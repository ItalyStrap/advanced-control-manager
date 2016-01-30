<?php
/**
 * Array definition for carousel default options
 *
 * @package ItalyStrap
 */

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) die();

/**
 * Definition array() with all the options connected to the
 * module which must be called by an include (setoptions).
 */
return array(

	/**
	 * Ids for the images to use.
	 */
	'ids'				=> array(
				'name'		=> __( 'Images ID', 'ItalyStrap' ),
				'desc'		=> __( 'Enter the image ID.', 'ItalyStrap' ),
				'id'		=> 'ids',
				'type'		=> 'media_list',
				'class'		=> 'widefat ids',
				'default'	=> false,
				// 'validate'	=> 'numeric_comma',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Type of gallery. If it's not "carousel", nothing will be done.
	 */
	'type'				=> array(
				'name'		=> __( 'Type of gallery', 'ItalyStrap' ),
				'desc'		=> __( 'Enter the type of gallery, if it\'s not "carousel", nothing will be done.', 'ItalyStrap' ),
				'id'		=> 'type',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'class-p'	=> 'hidden',
				'default'	=> 'carousel',
				'options'	=> array(
							'standard'  => __( 'Standard Gallery', 'ItalyStrap' ),
							'carousel'  => __( 'Carousel (Default)', 'ItalyStrap' ),
				 			),
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Alternative appearing order of images.
	 */
	'orderby'			=> array(
				'name'		=> __( 'Order Image By', 'ItalyStrap' ),
				'desc'		=> __( 'Alternative appearing order of images.', 'ItalyStrap' ),
				'id'		=> 'orderby',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'default'	=> 'menu_order',
				'options'	=> array(
							'menu_order'	=> __( 'Menu order (Default)', 'ItalyStrap' ),
							'title'			=> __( 'Order by the image\'s title', 'ItalyStrap' ),
							'post_date'		=> __( 'Sort by date/time', 'ItalyStrap' ),
							'rand'			=> __( 'Order randomly', 'ItalyStrap' ),
							'ID'			=> __( 'Order by the image\'s ID', 'ItalyStrap' ),
						),
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'Order',
				 ),

	/**
	 * Any name. String will be sanitize to be used as HTML ID. Recomended
	 * when you want to have more than one carousel in the same page.
	 * Default: italystrap-bootstrap-carousel.
	 * */
	'name'				=> array(
				'name'		=> __( 'Carousel Name', 'ItalyStrap' ),
				'desc'		=> __( 'Recomended when you want to have more than one carousel in the same page.', 'ItalyStrap' ),
				'id'		=> 'name',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'default'	=> 'italystrap-media-carousel-' . rand(),
				// 'validate'	=> 'alpha_numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Carousel container width, in px or %
	 */
	'width'				=> array(
				'name'		=> __( 'Carousel container width', 'ItalyStrap' ),
				'desc'		=> __( 'Enter the Carousel container width (optional).', 'ItalyStrap' ),
				'id'		=> 'width',
				'type'		=> 'number',
				'class'		=> 'widefat',
				'default'	=> '',
				'validate'	=> 'numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'size',
				 ),

	/**
	 * Carousel item height, in px or %
	 */
	'height'			=> array(
				'name'		=> __( 'Carousel container height', 'ItalyStrap' ),
				'desc'		=> __( 'Enter the Carousel container height (optional).', 'ItalyStrap' ),
				'id'		=> 'height',
				'type'		=> 'number',
				'class'		=> 'widefat',
				'default'	=> '',
				'validate'	=> 'numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'size',
				 ),

	/**
	 * Accepted values: before-inner, after-inner, after-control, false.
	 * Default: before-inner.
	 * */
	'indicators'		=> array(
				'name'		=> __( 'Indicators', 'ItalyStrap' ),
				'desc'		=> __( 'Indicators.', 'ItalyStrap' ),
				'id'		=> 'indicators',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'default'	=> 'before-inner',
				'options'	=> array(
							'before-inner'	=> __( 'Before inner (Default)', 'ItalyStrap' ),
							'after-inner'	=> __( 'After inner', 'ItalyStrap' ),
							'after-control'	=> __( 'After control', 'ItalyStrap' ),
							'false'			=> __( 'False', 'ItalyStrap' ),
						),
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Enable or disable arrow right and left
	 * Accepted values: true, false. Default: true.
	 */
	'control'			=> array(
				'name'		=> __( 'Output as control', 'ItalyStrap' ),
				'desc'		=> __( 'Wraps posts with the <li> tag.', 'ItalyStrap' ),
				'id'		=> 'control',
				'type'		=> 'checkbox',
				'default'	=> 1,
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

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
	'interval'			=> array(
				'name'		=> __( 'Carousel interval', 'ItalyStrap' ),
				'desc'		=> __( 'The amount of time to delay between automatically cycling an item in milliseconds.', 'ItalyStrap' ),
				'id'		=> 'interval',
				'type'		=> 'number',
				'class'		=> 'widefat',
				'default'	=> 0,
				'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Pauses the cycling of the carousel on mouseenter and resumes the
	 * cycling of the carousel on mouseleave.
	 * @type string Default hover.
	 */
	'pause'				=> array(
				'name'		=> __( 'Pause', 'ItalyStrap' ),
				'desc'		=> __( 'Pauses the cycling of the carousel on mouseenter and resumes the cycling of the carousel on mouseleave.', 'ItalyStrap' ),
				'id'		=> 'pause',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'default'	=> 'hover',
				'options'	=> array(
							'false'			=> __( 'none', 'ItalyStrap' ),
							'hover'			=> __( 'hover (Default)', 'ItalyStrap' ),
						),
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Define tag for image title. Default: h4.
	 */
	'titletag'			=> array(
				'name'		=> __( 'Carousel titletag', 'ItalyStrap' ),
				'desc'		=> __( 'Define tag for image title. Default: h4.', 'ItalyStrap' ),
				'id'		=> 'titletag',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'default'	=> 'h4',
				'validate'	=> 'alpha_numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Show or hide image title. Set false to hide. Default: true.
	 */
	'image_title'		=> array(
				'name'		=> __( 'Image Title', 'ItalyStrap' ),
				'desc'		=> __( 'Show or hide image title. Set false to hide. Default: true.', 'ItalyStrap' ),
				'id'		=> 'image_title',
				'type'		=> 'checkbox',
				'default'	=> 1,
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Type of link to show if "title" is set to true.
	 * Default Link Parameters file, none, link
	 */
	'link'				=> array(
				'name'		=> __( 'Title link', 'ItalyStrap' ),
				'desc'		=> __( 'Type of link to show if "title" is set to true.', 'ItalyStrap' ),
				'id'		=> 'link',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'default'	=> 'none',
				'options'	=> array(
							'file'			=> __( 'File', 'ItalyStrap' ),
							'none'			=> __( 'None (Default)', 'ItalyStrap' ),
							'link'			=> __( 'Link', 'ItalyStrap' ),
						),
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Show or hide image text. Set false to hide. Default: true.
	 */
	'text'				=> array(
				'name'		=> __( 'Text', 'ItalyStrap' ),
				'desc'		=> __( 'Show or hide image text. Set false to hide. Default: true.', 'ItalyStrap' ),
				'id'		=> 'text',
				'type'		=> 'checkbox',
				'default'	=> 1,
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),
	/**
	 * Auto-format text. Default: true.
	 */
	'wpautop'			=> array(
				'name'		=> __( 'wpautop', 'ItalyStrap' ),
				'desc'		=> __( 'Auto-format text. Default: true.', 'ItalyStrap' ),
				'id'		=> 'wpautop',
				'type'		=> 'checkbox',
				'default'	=> 1,
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),
	/**
	 * Extra class for container.
	 */
	'containerclass'	=> array(
				'name'		=> __( 'Container Class', 'ItalyStrap' ),
				'desc'		=> __( 'Extra class for container.', 'ItalyStrap' ),
				'id'		=> 'containerclass',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'Class',
				 ),

	/**
	 * Extra class for item.
	 */
	'itemclass'			=> array(
				'name'		=> __( 'Item Class', 'ItalyStrap' ),
				'desc'		=> __( 'Extra class for item.', 'ItalyStrap' ),
				'id'		=> 'itemclass',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'Class',
				 ),

	/**
	 * Extra class for caption.
	 */
	'captionclass'		=> array(
				'name'		=> __( 'Caption Class', 'ItalyStrap' ),
				'desc'		=> __( 'Extra class for caption.', 'ItalyStrap' ),
				'id'		=> 'captionclass',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'validate'	=> 'alpha_numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'Class',
				 ),

	/**
	 * Size for image attachment. Accepted values: thumbnail, medium,
	 * large, full or own custom name added in add_image_size function.
	 * Default: full.
	 * @see wp_get_attachment_image_src() for further reference.
	 */
	'size'				=> array(
				'name'		=> __( 'Size for images', 'ItalyStrap' ),
				'desc'		=> __( '', 'ItalyStrap' ),
				'id'		=> 'size',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'default'	=> 'full',
				'options'	=> ( ( isset( $image_size_media_array ) ) ? $image_size_media_array : '' ),
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'size',
				 ),

	/**
	 * Activate responsive image. Accepted values: true, false.
	 * Default false.
	 */
	'responsive'		=> array(
				'name'		=> __( 'Responsive image', 'ItalyStrap' ),
				'desc'		=> __( 'Activate responsive image.', 'ItalyStrap' ),
				'id'		=> 'responsive',
				'type'		=> 'checkbox',
				'default'	=> 0,
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'size',
				 ),

	/**
	 * Size for image attachment. Accepted values: thumbnail, medium,
	 * large, full or own custom name added in add_image_size function.
	 * Default: large.
	 * @see wp_get_attachment_image_src() for further reference.
	 */
	'sizetablet'		=> array(
				'name'		=> __( 'Size for images', 'ItalyStrap' ),
				'desc'		=> __( '', 'ItalyStrap' ),
				'id'		=> 'sizetablet',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'default'	=> 'large',
				'options'	=> ( ( isset( $image_size_media_array ) ) ? $image_size_media_array : '' ),
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'size',
				 ),

	/**
	 * Size for image attachment. Accepted values: thumbnail, medium,
	 * large, full or own custom name added in add_image_size function.
	 * Default: medium.
	 * @see wp_get_attachment_image_src() for further reference.
	 */
	'sizephone'			=> array(
				'name'		=> __( 'Size for images', 'ItalyStrap' ),
				'desc'		=> __( '', 'ItalyStrap' ),
				'id'		=> 'sizephone',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'default'	=> 'medium',
				'options'	=> ( ( isset( $image_size_media_array ) ) ? $image_size_media_array : '' ),
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'size',
				 ),

);
