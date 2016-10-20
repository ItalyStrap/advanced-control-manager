<?php
/**
 * Array definition for Image widget and shortcode
 *
 * @package ItalyStrap
 */

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

/**
 * Instance of list of image sizes
 *
 * @var ItalyStrapAdminMediaSettings
 */
$image_size_media = new ItalyStrapAdminMediaSettings;
$image_size_media_array = $image_size_media->get_image_sizes( array( 'full' => __( 'Real size', 'italystrap' ) ) );

/**
 * Definition array() with all the options connected to the
 * module which must be called by an include (setoptions).
 */
return array(

	/**
	 * Ids for the images to use.
	 */
	'id'				=> array(
				'name'		=> __( 'Enter Images ID', 'italystrap' ),
				'desc'		=> __( 'Enter the media or post type ID.', 'italystrap' ),
				'id'		=> 'id',
				'type'		=> 'media',
				'class'		=> 'widefat ids',
				// 'class-p'	=> 'hidden',
				'default'	=> false,
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Ids for the images to use.
	 */
	'image_title'			=> array(
				'name'		=> __( 'Image title', 'italystrap' ),
				'desc'		=> __( 'Enter the image title.', 'italystrap' ),
				'id'		=> 'image_title',
				'type'		=> 'text',
				'class'		=> 'widefat image_title',
				'default'	=> '',
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Define tag for image title. Default: h4.
	 */
	'image_title_tag'			=> array(
				'name'		=> __( 'Image title tag', 'italystrap' ),
				'desc'		=> __( 'Define tag for image title. Default: h4.', 'italystrap' ),
				'id'		=> 'image_title_tag',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'default'	=> 'h4',
				// 'validate'	=> 'esc_attr',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Ids for the images to use.
	 */
	'caption'			=> array(
				'name'		=> __( 'Image caption', 'italystrap' ),
				'desc'		=> __( 'Enter the image caption.', 'italystrap' ),
				'id'		=> 'caption',
				'type'		=> 'textarea',
				'class'		=> 'widefat caption',
				'default'	=> '',
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Ids for the images to use.
	 */
	'description'		=> array(
				'name'		=> __( 'Image description', 'italystrap' ),
				'desc'		=> __( 'Enter the image description.', 'italystrap' ),
				'id'		=> 'description',
				'type'		=> 'textarea',
				'class'		=> 'widefat description',
				'default'	=> '',
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Add paragraphs.
	 */
	'wpautop'		=> array(
				'name'		=> __( 'Add paragraphs', 'italystrap' ),
				'desc'		=> __( 'Automatically add paragraphs.', 'italystrap' ),
				'id'		=> 'wpautop',
				'type'		=> 'checkbox',
				'class'		=> 'wpautop',
				'default'	=> 0,
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Ids for the images to use.
	 */
	'do_shortcode'		=> array(
				'name'		=> __( 'Do Shortcode', 'italystrap' ),
				'desc'		=> __( '.', 'italystrap' ),
				'id'		=> 'do_shortcode',
				'type'		=> 'checkbox',
				'class'		=> 'do_shortcode',
				'default'	=> 0,
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Size for image attachment. Accepted values: thumbnail, medium,
	 * large, full or own custom name added in add_image_size function.
	 * Default: full.
	 *
	 * @see wp_get_attachment_image_src() for further reference.
	 */
	'size'				=> array(
				'name'		=> __( 'Size for images', 'italystrap' ),
				'desc'		=> __( 'Size for image attachment. Accepted values: thumbnail, medium, large, full or own custom name added in add_image_size function. Default: full.', 'italystrap' ),
				'id'		=> 'size',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'default'	=> 'full',
				'options'	=> ( ( isset( $image_size_media_array ) ) ? $image_size_media_array : '' ),
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Alignment for image
	 */
	'alignment'				=> array(
				'name'		=> __( 'Alignment', 'italystrap' ),
				'desc'		=> __( 'Alignment for image', 'italystrap' ),
				'id'		=> 'alignment',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'default'	=> 'none',
				'options'	=> array(
					'alignnone'		=> 'none',
					'aligncenter'	=> 'center',
					'alignleft'		=> 'left',
					'alignright'	=> 'right',
					),
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Ids for the images to use.
	 */
	'add_figure_container'		=> array(
				'name'		=> esc_html__( 'Add container "figure"', 'italystrap' ),
				'desc'		=> __( 'Check if you want to add <code>&lt;figure&gt;&lt;/figure&gt;</code> tag for img container.', 'italystrap' ),
				'id'		=> 'add_figure_container',
				'type'		=> 'checkbox',
				'class'		=> 'add_figure_container',
				'default'	=> 1,
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'style',
				 ),

	/**
	 * Ids for the images to use.
	 */
	'container_css_class'		=> array(
				'name'		=> __( 'Container CSS class', 'italystrap' ),
				'desc'		=> __( 'Enter the image css class (optional).', 'italystrap' ),
				'id'		=> 'container_css_class',
				'type'		=> 'text',
				'class'		=> 'widefat container_css_class',
				'default'	=> '',
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'style',
				 ),

	/**
	 * Ids for the images to use.
	 */
	'image_css_class'		=> array(
				'name'		=> __( 'Image CSS class', 'italystrap' ),
				'desc'		=> __( 'Enter the image css class (optional).', 'italystrap' ),
				'id'		=> 'image_css_class',
				'type'		=> 'text',
				'class'		=> 'widefat image_css_class',
				'default'	=> '',
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'style',
				 ),

	/**
	 * Ids for the images to use.
	 */
	'link'		=> array(
				'name'		=> __( 'Link URL', 'italystrap' ),
				'desc'		=> __( 'When you click on image.', 'italystrap' ),
				'id'		=> 'link',
				'type'		=> 'text',
				'class'		=> 'widefat link',
				'default'	=> '',
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Ids for the images to use.
	 */
	'link_target_blank'		=> array(
				'name'		=> __( 'Open in a new windows', 'italystrap' ),
				'desc'		=> __( 'Check if you want to open in a new tab/windows.', 'italystrap' ),
				'id'		=> 'link_target_blank',
				'type'		=> 'checkbox',
				'class'		=> 'link_target_blank',
				'default'	=> '',
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

);
