<?php
/**
 * Array definition for Posts default options
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
	 * Custom link for the widget title.
	 */
	'title_link'				=> array(
				'name'		=> __( 'Title link', 'ItalyStrap' ),
				'desc'		=> __( 'Enter a custom title link.', 'ItalyStrap' ),
				'id'		=> 'title_link',
				'type'		=> 'url',
				'class'		=> 'widefat title_link',
				'default'	=> '',
				'validate'	=> 'ctype_alpha',
				'filter'	=> 'esc_url',
				'section'	=> 'general',
				 ),

	/**
	 * Custom CSS class for widget.
	 */
	'widget_class'				=> array(
				'name'		=> __( 'Widget Class', 'ItalyStrap' ),
				'desc'		=> __( 'Enter the widget class name.', 'ItalyStrap' ),
				'id'		=> 'widget_class',
				'type'		=> 'text',
				'class'		=> 'widefat widget_class',
				'class-p'	=> 'widget_class',
				'default'	=> '',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	// 'before_posts'				=> array(
	// 			'name'		=> __( 'Before posts', 'ItalyStrap' ),
	// 			'desc'		=> __( 'Enter a custom text or HTML markup.', 'ItalyStrap' ),
	// 			'id'		=> 'before_posts',
	// 			'type'		=> 'textarea',
	// 			'class'		=> 'widefat before_posts',
	// 			'default'	=> '',
	// 			'filter'	=> 'sanitize_text_field',
	// 			'section'	=> 'general',
	// 			 ),
	/**
	 * Custom text or HTML markup.
	 */
	// 'after_posts'				=> array(
	// 			'name'		=> __( 'After posts', 'ItalyStrap' ),
	// 			'desc'		=> __( 'Enter a custom text or HTML markup.', 'ItalyStrap' ),
	// 			'id'		=> 'after_posts',
	// 			'type'		=> 'textarea',
	// 			'class'		=> 'widefat after_posts',
	// 			'default'	=> '',
	// 			'filter'	=> 'sanitize_text_field',
	// 			'section'	=> 'general',
	// 			 ),

	/**
	 * Custom text or HTML markup.
	 */
	'template'			=> array(
				'name'		=> __( 'Template', 'ItalyStrap' ),
				'desc'		=> __( 'Select the template to display posts.', 'ItalyStrap' ),
				'id'		=> 'template',
				'type'		=> 'select',
				'class'		=> 'widefat template',
				'default'	=> 'standard',
				'options'	=> array(
							'standard'  => __( 'Standard template (Default)', 'ItalyStrap' ),
							'custom'  => __( 'Custom template', 'ItalyStrap' ),
				 			),
				// 'validate'	=> 'numeric_comma',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'template_custom'			=> array(
				'name'		=> __( 'Template custom name', 'ItalyStrap' ),
				'desc'		=> __( 'Insert your template custom name.', 'ItalyStrap' ),
				'id'		=> 'template_custom',
				'type'		=> 'text',
				'class'		=> 'widefat template_custom',
				'class-p'	=> 'template_custom',
				'default'	=> '',
				// 'validate'	=> 'numeric_comma',
				'filter'	=> 'sanitize_option',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'posts_number'				=> array(
				'name'		=> __( 'Number of posts', 'ItalyStrap' ),
				'desc'		=> __( 'Insert the number of posts to display.', 'ItalyStrap' ),
				'id'		=> 'posts_number',
				'type'		=> 'number',
				'class'		=> 'widefat posts_number',
				'default'	=> 5,
				'validate'	=> 'numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_title'				=> array(
				'name'		=> __( 'Show the title', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to show the title.', 'ItalyStrap' ),
				'id'		=> 'show_title',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_title',
				'default'	=> 1,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom CSS class for widget.
	 */
	'entry_title'				=> array(
				'name'		=> __( 'HTML tag for post title', 'ItalyStrap' ),
				'desc'		=> __( 'Enter the heading tag for title (default is h4).', 'ItalyStrap' ),
				'id'		=> 'entry_title',
				'type'		=> 'text',
				'class'		=> 'widefat entry_title',
				'class-p'	=> 'entry_title',
				'default'	=> 'h4',
				// 'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_date'					=> array(
				'name'		=> __( 'Show the date', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to show the date.', 'ItalyStrap' ),
				'id'		=> 'show_date',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_date',
				'default'	=> 1,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'date_format'				=> array(
				'name'		=> __( 'Date format', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to show the date.', 'ItalyStrap' ),
				'id'		=> 'date_format',
				'type'		=> 'text',
				'class'		=> 'widefat date_format',
				'default'	=> get_option( 'date_format' ) . ' ' . get_option( 'time_format' ),
				// 'validate'	=> 'numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_author'				=> array(
				'name'		=> __( 'Show post author', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to show post\'s author.', 'ItalyStrap' ),
				'id'		=> 'show_author',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_author',
				'default'	=> 1,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_comments_number'		=> array(
				'name'		=> __( 'Show comments number', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to Show comments number.', 'ItalyStrap' ),
				'id'		=> 'show_comments_number',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_comments_number',
				'default'	=> 0,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_excerpt'				=> array(
				'name'		=> __( 'Show excerpt', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to show excerpt.', 'ItalyStrap' ),
				'id'		=> 'show_excerpt',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_excerpt',
				'default'	=> 0,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'excerpt_length'			=> array(
				'name'		=> __( 'Excerpt length', 'ItalyStrap' ),
				'desc'		=> __( 'Insert the numbers of words to display.', 'ItalyStrap' ),
				'id'		=> 'excerpt_length',
				'type'		=> 'number',
				'class'		=> 'widefat excerpt_length',
				'default'	=> 10,
				'validate'	=> 'numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_content'				=> array(
				'name'		=> __( 'Show content', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to show content.', 'ItalyStrap' ),
				'id'		=> 'show_content',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_content',
				'default'	=> 0,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_readmore'				=> array(
				'name'		=> __( 'Show readmore', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to show readmore tag.', 'ItalyStrap' ),
				'id'		=> 'show_readmore',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_readmore',
				'default'	=> 1,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'excerpt_readmore'			=> array(
				'name'		=> __( 'Excerpt length', 'ItalyStrap' ),
				'desc'		=> __( 'Insert the numbers of words to display.', 'ItalyStrap' ),
				'id'		=> 'excerpt_readmore',
				'type'		=> 'text',
				'class'		=> 'widefat excerpt_readmore',
				'default'	=> __( 'Read more &rarr;', 'ItalyStrap' ),
				'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),
	/**
	 * Custom text or HTML markup.
	 */
	'show_thumbnail'			=> array(
				'name'		=> __( 'Show thumbnail', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to show thumbnail.', 'ItalyStrap' ),
				'id'		=> 'show_thumbnail',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_thumbnail',
				'default'	=> 1,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'thumb_size'			=> array(
				'name'		=> __( 'thumb_size', 'ItalyStrap' ),
				'desc'		=> __( 'Select the thumb size to display posts.', 'ItalyStrap' ),
				'id'		=> 'thumb_size',
				'type'		=> 'select',
				'class'		=> 'widefat thumb_size',
				'default'	=> 'thumbnail',
				'options'	=> ( ( isset( $image_size_media_array ) ) ? $image_size_media_array : null ),
				// 'validate'	=> 'numeric_comma',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom CSS class for widget.
	 */
	'thumb_url'				=> array(
				'name'		=> __( 'Load fall-back thumbnail (optional)', 'ItalyStrap' ),
				'desc'		=> __( 'Enter a fall-back thumbnail.', 'ItalyStrap' ),
				'id'		=> 'thumb_url',
				'type'		=> 'media_list',
				'class'		=> 'widefat thumb_url',
				'class-p'	=> 'thumb_url',
				'default'	=> '',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_cats'					=> array(
				'name'		=> __( 'Show post categories', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to Show post categories.', 'ItalyStrap' ),
				'id'		=> 'show_cats',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_cats',
				'default'	=> 0,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_tags'					=> array(
				'name'		=> __( 'Show post tags', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to Show post tags.', 'ItalyStrap' ),
				'id'		=> 'show_tags',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_tags',
				'default'	=> 0,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'custom_fields'				=> array(
				'name'		=> __( 'Show custom fields', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to Show custom fields (comma separated).', 'ItalyStrap' ),
				'id'		=> 'custom_fields',
				'type'		=> 'text',
				'class'		=> 'widefat custom_fields',
				'default'	=> '',
				// 'validate'	=> 'numeric',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'atcat'						=> array(
				'name'		=> __( 'Show posts only from current category', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to Show posts only from current category.', 'ItalyStrap' ),
				'id'		=> 'atcat',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat atcat',
				'default'	=> 0,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'filter',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'cats'						=> array(
				'name'		=> __( 'Categories', 'ItalyStrap' ),
				'desc'		=> __( 'Select the categorie.', 'ItalyStrap' ),
				'id'		=> 'cats',
				'type'		=> 'multiple_select',
				'class'		=> 'widefat cats',
				'default'	=> '',
				'options'	=> ( ( isset( $get_category_list_array ) ) ? $get_category_list_array : null ),
				// 'validate'	=> 'numeric_comma',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'filter',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'attag'						=> array(
				'name'		=> __( 'Show posts only from current tag', 'ItalyStrap' ),
				'desc'		=> __( 'Check if you want to Show posts only from current tag.', 'ItalyStrap' ),
				'id'		=> 'attag',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat attag',
				'default'	=> 0,
				// 'validate'	=> 'numeric',
				'filter'	=> 'esc_attr',
				'section'	=> 'filter',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'tags'						=> array(
				'name'		=> __( 'Tags', 'ItalyStrap' ),
				'desc'		=> __( 'Select the Tag.', 'ItalyStrap' ),
				'id'		=> 'tags',
				'type'		=> 'multiple_select',
				'class'		=> 'widefat tags',
				'default'	=> '',
				'options'	=> ( ( isset( $get_post_tag_list_array ) ) ? $get_post_tag_list_array : null ),
				// 'validate'	=> 'numeric_comma',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'filter',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'post_types'				=> array(
				'name'		=> __( 'Post type', 'ItalyStrap' ),
				'desc'		=> __( 'Select the post type.', 'ItalyStrap' ),
				'id'		=> 'post_types',
				'type'		=> 'multiple_select',
				'class'		=> 'widefat post_types',
				'default'	=> 'post',
				'options'	=> ( ( isset( $get_post_types ) ) ? $get_post_types : null ),
				// 'validate'	=> 'numeric_comma',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'filter',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'sticky_post'				=> array(
				'name'		=> __( 'sticky_post', 'ItalyStrap' ),
				'desc'		=> __( 'Select Sticky posts.', 'ItalyStrap' ),
				'id'		=> 'sticky_post',
				'type'		=> 'select',
				'class'		=> 'widefat sticky_post',
				'default'	=> 'show',
				'options'	=> array(
						'show'	=> __( 'Show all posts', 'ItalyStrap' ),
						'hide'	=> __( 'Hide Sticky Posts', 'ItalyStrap' ),
						'only'	=> __( 'Show Only Sticky Posts', 'ItalyStrap' ),
					),
				// 'validate'	=> 'numeric_comma',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'filter',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'orderby'					=> array(
				'name'		=> __( 'Order by', 'ItalyStrap' ),
				'desc'		=> __( 'How posts have to be ordered.', 'ItalyStrap' ),
				'id'		=> 'orderby',
				'type'		=> 'select',
				'class'		=> 'widefat orderby',
				'default'	=> 'date',
				'options'	=> array(
						'date'			=> __( 'Published Date', 'ItalyStrap' ),
						'title'			=> __( 'Title', 'ItalyStrap' ),
						'comment_count'	=> __( 'Comment Count', 'ItalyStrap' ),
						'rand'			=> __( 'Random', 'ItalyStrap' ),
						'meta_value'	=> __( 'Custom Field', 'ItalyStrap' ),
						'menu_order'	=> __( 'Menu Order', 'ItalyStrap' ),
					),
				// 'validate'	=> 'numeric_comma',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'order',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'meta_key'			=> array(
				'name'		=> __( 'Custom fields', 'ItalyStrap' ),
				'desc'		=> __( 'Inser the custom field separated by comma.', 'ItalyStrap' ),
				'id'		=> 'meta_key',
				'type'		=> 'text',
				'class'		=> 'widefat meta_key',
				'default'	=> '',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'order',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'order'					=> array(
				'name'		=> __( 'order', 'ItalyStrap' ),
				'desc'		=> __( 'Select.', 'ItalyStrap' ),
				'id'		=> 'order',
				'type'		=> 'select',
				'class'		=> 'widefat order',
				'default'	=> 'DESC',
				'options'	=> array(
					'DESC'	=> __( 'Descending', 'ItalyStrap' ),
					'ASC'	=> __( 'Ascending', 'ItalyStrap' ),
					),
				// 'validate'	=> 'numeric_comma',
				'filter'	=> 'sanitize_text_field',
				'section'	=> 'order',
				 ),

);
