<?php
/**
 * Array definition for Posts default options
 *
 * @package ItalyStrap
 */

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

/**
 * Definition array() with all the options connected to the
 * module which must be called by an include (setoptions).
 */
return array(

	/**
	 * Custom link for the widget title.
	 */
	// 'title_link'				=> array(
	// 			'name'		=> __( 'Title link', 'italystrap' ),
	// 			'desc'		=> __( 'Enter a custom title link.', 'italystrap' ),
	// 			'id'		=> 'title_link',
	// 			'type'		=> 'url',
	// 			'class'		=> 'widefat title_link',
	// 			'default'	=> '',
	// 			'validate'	=> 'ctype_alpha',
	// 			'sanitize'	=> 'esc_url',
	// 			'section'	=> 'general',
	// 			 ),

	/**
	 * Custom CSS class for widget.
	 */
	// 'widget_class'				=> array(
	// 			'name'		=> __( 'Widget Class', 'italystrap' ),
	// 			'desc'		=> __( 'Enter the widget class name.', 'italystrap' ),
	// 			'id'		=> 'widget_class',
	// 			'type'		=> 'text',
	// 			'class'		=> 'widefat widget_class',
	// 			'class-p'	=> 'widget_class',
	// 			'default'	=> '',
	// 			'validate'	=> 'alpha_dash',
	// 			'sanitize'	=> 'sanitize_text_field',
	// 			'section'	=> 'general',
	// 			 ),

	/**
	 * Custom CSS class for container.
	 */
	'container_class'			=> array(
				'name'		=> __( 'Container Class', 'italystrap' ),
				'desc'		=> __( 'Enter the container class name.', 'italystrap' ),
				'id'		=> 'container_class',
				'type'		=> 'text',
				'class'		=> 'widefat container_class',
				'class-p'	=> 'container_class',
				'default'	=> '',
				'validate'	=> 'alpha_dash',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Custom CSS class for the article html tag.
	 */
	'post_class'				=> array(
				'name'		=> __( 'Post Class', 'italystrap' ),
				'desc'		=> __( 'Enter the post class name.', 'italystrap' ),
				'id'		=> 'post_class',
				'type'		=> 'text',
				'class'		=> 'widefat post_class',
				'class-p'	=> 'post_class',
				'default'	=> '',
				'validate'	=> 'alpha_dash',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Custom CSS class for image.
	 */
	'image_class'				=> array(
				'name'		=> __( 'Image Class', 'italystrap' ),
				'desc'		=> __( 'Enter the image class name.', 'italystrap' ),
				'id'		=> 'image_class',
				'type'		=> 'text',
				'class'		=> 'widefat image_class',
				'class-p'	=> 'image_class',
				'default'	=> '',
				'validate'	=> 'alpha_dash',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	// 'before_posts'				=> array(
	// 			'name'		=> __( 'Before posts', 'italystrap' ),
	// 			'desc'		=> __( 'Enter a custom text or HTML markup.', 'italystrap' ),
	// 			'id'		=> 'before_posts',
	// 			'type'		=> 'textarea',
	// 			'class'		=> 'widefat before_posts',
	// 			'default'	=> '',
	// 			'sanitize'	=> 'sanitize_text_field',
	// 			'section'	=> 'general',
	// 			 ),
	/**
	 * Custom text or HTML markup.
	 */
	// 'after_posts'				=> array(
	// 			'name'		=> __( 'After posts', 'italystrap' ),
	// 			'desc'		=> __( 'Enter a custom text or HTML markup.', 'italystrap' ),
	// 			'id'		=> 'after_posts',
	// 			'type'		=> 'textarea',
	// 			'class'		=> 'widefat after_posts',
	// 			'default'	=> '',
	// 			'sanitize'	=> 'sanitize_text_field',
	// 			'section'	=> 'general',
	// 			 ),

	/**
	 * Custom CSS class for widget.
	 */
	'entry_title'				=> array(
				'name'		=> __( 'HTML tag for post title', 'italystrap' ),
				'desc'		=> __( 'Enter the heading tag for title (default is h4).', 'italystrap' ),
				'id'		=> 'entry_title',
				'type'		=> 'text',
				'class'		=> 'widefat entry_title',
				'class-p'	=> 'entry_title',
				'default'	=> 'h4',
				// 'validate'	=> 'alpha_dash',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Custom CSS.
	 */
	'custom_css'				=> array(
				'name'		=> __( 'Custom CSS', 'italystrap' ),
				'desc'		=> __( 'Enter your custom CSS.', 'italystrap' ),
				'id'		=> 'custom_css',
				'type'		=> 'textarea',
				'class'		=> 'widefat custom_css',
				'class-p'	=> 'custom_css hidden',
				'default'	=> '',
				// 'validate'	=> 'alpha_dash',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'template'			=> array(
				'name'		=> __( 'Template', 'italystrap' ),
				'desc'		=> __( 'Select the template to display posts.', 'italystrap' ),
				'id'		=> 'template',
				'type'		=> 'select',
				'class'		=> 'widefat template',
				'class-p'	=> 'template hidden',
				'default'	=> 'standard',
				'options'	=> array(
							'standard'  => __( 'Standard template (Default)', 'italystrap' ),
							'custom'  => __( 'Custom template', 'italystrap' ),
				 			),
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'template_custom'			=> array(
				'name'		=> __( 'Template custom name', 'italystrap' ),
				'desc'		=> __( 'Insert your template custom name.', 'italystrap' ),
				'id'		=> 'template_custom',
				'type'		=> 'text',
				'class'		=> 'widefat template_custom',
				'class-p'	=> 'template_custom hidden',
				'default'	=> '',
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'posts_number'				=> array(
				'name'		=> __( 'Number of posts', 'italystrap' ),
				'desc'		=> __( 'Insert the number of posts to display.', 'italystrap' ),
				'id'		=> 'posts_number',
				'type'		=> 'number',
				'class'		=> 'widefat posts_number',
				'default'	=> 5,
				'validate'	=> 'numeric',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_title'				=> array(
				'name'		=> __( 'Show the title', 'italystrap' ),
				'desc'		=> __( 'Check if you want to show the title.', 'italystrap' ),
				'id'		=> 'show_title',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_title',
				'default'	=> '1',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_date'					=> array(
				'name'		=> __( 'Show the date', 'italystrap' ),
				'desc'		=> __( 'Check if you want to show the date.', 'italystrap' ),
				'id'		=> 'show_date',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_date',
				'default'	=> '1',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'date_format'				=> array(
				'name'		=> __( 'Date format', 'italystrap' ),
				'desc'		=> __( 'Check if you want to show the date.', 'italystrap' ),
				'id'		=> 'date_format',
				'type'		=> 'text',
				'class'		=> 'widefat date_format',
				'default'	=> get_option( 'date_format' ) . ' ' . get_option( 'time_format' ),
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_author'				=> array(
				'name'		=> __( 'Show post author', 'italystrap' ),
				'desc'		=> __( 'Check if you want to show post\'s author.', 'italystrap' ),
				'id'		=> 'show_author',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_author',
				'default'	=> '1',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_comments_number'		=> array(
				'name'		=> __( 'Show comments number', 'italystrap' ),
				'desc'		=> __( 'Check if you want to Show comments number.', 'italystrap' ),
				'id'		=> 'show_comments_number',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_comments_number',
				'default'	=> '0',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_excerpt'				=> array(
				'name'		=> __( 'Show excerpt', 'italystrap' ),
				'desc'		=> __( 'Check if you want to show excerpt.', 'italystrap' ),
				'id'		=> 'show_excerpt',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_excerpt',
				'default'	=> '0',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'excerpt_length'			=> array(
				'name'		=> __( 'Excerpt length', 'italystrap' ),
				'desc'		=> __( 'Insert the numbers of words to display.', 'italystrap' ),
				'id'		=> 'excerpt_length',
				'type'		=> 'number',
				'class'		=> 'widefat excerpt_length',
				'default'	=> 10,
				'validate'	=> 'numeric',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_content'				=> array(
				'name'		=> __( 'Show content', 'italystrap' ),
				'desc'		=> __( 'Check if you want to show content.', 'italystrap' ),
				'id'		=> 'show_content',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_content',
				'default'	=> '0',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_readmore'				=> array(
				'name'		=> __( 'Show readmore', 'italystrap' ),
				'desc'		=> __( 'Check if you want to show readmore tag.', 'italystrap' ),
				'id'		=> 'show_readmore',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_readmore',
				'default'	=> '1',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'excerpt_readmore'			=> array(
				'name'		=> __( 'Excerpt read more', 'italystrap' ),
				'desc'		=> __( 'Insert the custom "read more" words.', 'italystrap' ),
				'id'		=> 'excerpt_readmore',
				'type'		=> 'text',
				'class'		=> 'widefat excerpt_readmore',
				'default'	=> __( 'Read more &rarr;', 'italystrap' ),
				'validate'	=> 'alpha_dash',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_thumbnail'			=> array(
				'name'		=> __( 'Show thumbnail', 'italystrap' ),
				'desc'		=> __( 'Check if you want to show thumbnail.', 'italystrap' ),
				'id'		=> 'show_thumbnail',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_thumbnail',
				'default'	=> '1',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'thumb_size'			=> array(
				'name'		=> __( 'Thumbnail size', 'italystrap' ),
				'desc'		=> __( 'Select the thumbnail size to display in posts list.', 'italystrap' ),
				'id'		=> 'thumb_size',
				'type'		=> 'select',
				'class'		=> 'widefat thumb_size',
				'default'	=> 'thumbnail',
				'options'	=> ( ( is_admin() ) ? \ItalyStrap\Core\get_image_size_array() : null ),
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'show_first_thumbnail_bigger'			=> array(
				'name'		=> __( 'Show the first thumbnail bigger', 'italystrap' ),
				'desc'		=> __( 'Check if you want to show the firt thumbnail bigger.', 'italystrap' ),
				'id'		=> 'show_first_thumbnail_bigger',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_first_thumbnail_bigger',
				'default'	=> '',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'first_thumb_size'			=> array(
				'name'		=> __( 'The first thumbnail size', 'italystrap' ),
				'desc'		=> __( 'Select the thumbnail size to display in posts list.', 'italystrap' ),
				'id'		=> 'first_thumb_size',
				'type'		=> 'select',
				'class'		=> 'widefat first_thumb_size',
				'default'	=> 'medium',
				'options'	=> ( ( is_admin() ) ? \ItalyStrap\Core\get_image_size_array() : null ),
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Custom CSS class for widget.
	 */
	'thumb_id'				=> array(
				'name'		=> __( 'Enter ID of a fall-back thumbnail (optional)', 'italystrap' ),
				'desc'		=> __( 'Add a fall-back thumbnail in case no one is found.', 'italystrap' ),
				'id'		=> 'thumb_id',
				'type'		=> 'media',
				'class'		=> 'widefat thumb_id ids',
				'class-p'	=> 'hidden',
				'default'	=> '',
				'validate'	=> 'alpha_dash',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Show post categories.
	 */
	'show_cats'					=> array(
				'name'		=> __( 'Show post categories', 'italystrap' ),
				'desc'		=> __( 'Check if you want to Show post categories.', 'italystrap' ),
				'id'		=> 'show_cats',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_cats',
				'default'	=> '0',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Show post tags.
	 */
	'show_tags'					=> array(
				'name'		=> __( 'Show post tags', 'italystrap' ),
				'desc'		=> __( 'Check if you want to Show post tags.', 'italystrap' ),
				'id'		=> 'show_tags',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat show_tags',
				'default'	=> '0',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'display',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'custom_fields'				=> array(
				'name'		=> __( 'Show custom fields', 'italystrap' ),
				'desc'		=> __( 'Insert here the custom fields (comma separated).', 'italystrap' ),
				'id'		=> 'custom_fields',
				'type'		=> 'text',
				'class'		=> 'widefat custom_fields',
				'default'	=> '',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'display',
				 ),

	/**
	 * Show posts only from current category.
	 */
	// 'atcat'						=> array(
	// 			'name'		=> __( 'Show posts only from current category', 'italystrap' ),
	// 			'desc'		=> __( 'Check if you want to Show posts only from current category.', 'italystrap' ),
	// 			'id'		=> 'atcat',
	// 			'type'		=> 'checkbox',
	// 			// 'class'		=> 'widefat atcat',
	// 			'default'	=> '0',
	// 			// 'validate'	=> 'numeric',
	// 			'sanitize'	=> 'esc_attr',
	// 			'section'	=> 'filter',
	// 			 ),

	/**
	 * Select Categories.
	 */
	'cats'						=> array(
				'name'		=> __( 'Select Categories', 'italystrap' ),
				'desc'		=> __( 'Select the categories. (Use ctrl for multiple select)', 'italystrap' ),
				'id'		=> 'cats',
				'type'		=> 'taxonomy_multiple_select',
				'class'		=> 'widefat cats',
				'show_option_none' => __( 'No selection', 'italystrap' ), // Default false, with tre write None
				'default'	=> '0',
				'taxonomy'	=> 'category',
				// 'options'	=> ( ( is_admin() ) ? get_taxonomies_list_array( 'category' ) : null ),
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_taxonomy_multiple_select',
				'section'	=> 'filter',
				 ),

	/**
	 * Show posts only from current tag.
	 */
	// 'attag'						=> array(
	// 			'name'		=> __( 'Show posts only from current tag', 'italystrap' ),
	// 			'desc'		=> __( 'Check if you want to Show posts only from current tag.', 'italystrap' ),
	// 			'id'		=> 'attag',
	// 			'type'		=> 'checkbox',
	// 			// 'class'		=> 'widefat attag',
	// 			'default'	=> '0',
	// 			// 'validate'	=> 'numeric',
	// 			'sanitize'	=> 'esc_attr',
	// 			'section'	=> 'filter',
	// 			 ),

	/**
	 * Select Tags.
	 */
	'tags'						=> array(
				'name'		=> __( 'Select Tags', 'italystrap' ),
				'desc'		=> __( 'Select the Tag. (Use ctrl for multiple select)', 'italystrap' ),
				'id'		=> 'tags',
				'type'		=> 'taxonomy_multiple_select',
				'class'		=> 'widefat tags',
				'show_option_none' => __( 'No selection', 'italystrap' ),
				'default'	=> 'none',
				'taxonomy'	=> 'post_tag',
				// 'options'	=> ( ( is_admin() ) ? get_taxonomies_list_array( 'post_tag' ) : null ),
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_taxonomy_multiple_select',
				'section'	=> 'filter',
				 ),

	/**
	 * Show related posts by tags
	 */
	'related_by_tags'			=> array(
				'name'		=> __( 'Show related posts by tags', 'italystrap' ),
				'desc'		=> __( 'Check if you want to Show related posts by tags.', 'italystrap' ),
				'id'		=> 'related_by_tags',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat related_by_tags',
				'default'	=> '0',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'filter',
				 ),

	/**
	 * Show related posts by tags
	 */
	'related_by_cats'			=> array(
				'name'		=> __( 'Show related posts by cats', 'italystrap' ),
				'desc'		=> __( 'Check if you want to Show related posts by cats.', 'italystrap' ),
				'id'		=> 'related_by_cats',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat related_by_cats',
				'default'	=> 0,
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'filter',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'offset'				=> array(
				'name'		=> __( 'Discard posts', 'italystrap' ),
				'desc'		=> __( 'Number of post to displace or pass over. Warning: Setting the offset parameter overrides/ignores the paged parameter and breaks pagination <a href="https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination">(Click here for a workaround)</a>. The "offset" parameter is ignored when "posts_per_page"=>-1 (show all posts) is used.', 'italystrap' ),
				'id'		=> 'offset',
				'type'		=> 'number',
				'class'		=> 'widefat offset',
				'default'	=> 0,
				'validate'	=> 'numeric',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'filter',
				 ),

	/**
	 * Esclude current post from loop
	 */
	'exclude_current_post'			=> array(
				'name'		=> __( 'Esclude current post from loop', 'italystrap' ),
				'desc'		=> __( 'Check if you want to Esclude the current post from loop.', 'italystrap' ),
				'id'		=> 'exclude_current_post',
				'type'		=> 'checkbox',
				// 'class'		=> 'widefat exclude_current_post',
				'default'	=> '0',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'filter',
				 ),

	/**
	 * Show posts only from current user.
	 */
	'from_current_user'			=> array(
				'name'		=> __( 'Show posts only from current user', 'italystrap' ),
				'desc'		=> __( 'Select the user you want to display posts.', 'italystrap' ),
				'id'		=> 'from_current_user',
				'type'		=> 'text',
				// 'class'		=> 'widefat from_current_user',
				'default'	=> '0',
				// 'validate'	=> 'numeric',
				'sanitize'	=> 'esc_attr',
				'section'	=> 'filter',
				 ),

	/**
	 * Select the post type.
	 */
	'post_types'				=> array(
				'name'		=> __( 'Post type', 'italystrap' ),
				'desc'		=> __( 'Select the post type to show.', 'italystrap' ),
				'id'		=> 'post_types',
				'type'		=> 'multiple_select',
				'class'		=> 'widefat post_types',
				'default'	=> 'post',
				'options'	=> ( ( isset( $get_post_types ) ) ? $get_post_types : null ),
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'filter',
				 ),

	/**
	 * Select the post ID.
	 */
	'post_id'				=> array(
				'name'		=> __( 'Post/Page Id', 'italystrap' ),
				'desc'		=> __( 'Insert posts ID separated by comma.', 'italystrap' ),
				'id'		=> 'post_id',
				'type'		=> 'text',
				'class'		=> 'widefat post_id',
				'default'	=> '',
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'filter',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	// 'users'				=> array(
	// 			'name'		=> __( 'Select user', 'italystrap' ),
	// 			'desc'		=> __( 'Select the post type.', 'italystrap' ),
	// 			'id'		=> 'users',
	// 			'type'		=> 'multiple_select',
	// 			'class'		=> 'widefat users',
	// 			'default'	=> 'post',
	// 			'options'	=> ( ( is_admin() ) ? get_users() : null ),
	// 			// 'validate'	=> 'numeric_comma',
	// 			'sanitize'	=> 'sanitize_text_field',
	// 			'section'	=> 'filter',
	// 			 ),

	/**
	 * Select if you want to show sticky posts or not.
	 */
	'sticky_post'				=> array(
				'name'		=> __( 'Sticky post', 'italystrap' ),
				'desc'		=> __( 'Select if you want to show sticky posts or not.', 'italystrap' ),
				'id'		=> 'sticky_post',
				'type'		=> 'select',
				'class'		=> 'widefat sticky_post',
				'default'	=> 'show',
				'options'	=> array(
						'show'	=> __( 'Show all posts', 'italystrap' ),
						'hide'	=> __( 'Hide Sticky Posts', 'italystrap' ),
						'only'	=> __( 'Show Only Sticky Posts', 'italystrap' ),
					),
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'filter',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'orderby'					=> array(
				'name'		=> __( 'Order by', 'italystrap' ),
				'desc'		=> __( 'How posts have to be ordered.', 'italystrap' ),
				'id'		=> 'orderby',
				'type'		=> 'select',
				'class'		=> 'widefat orderby',
				'default'	=> 'none',
				'options'	=> array(
						'none'			=> __( 'No order. (Default)', 'italystrap' ),
						'ID'			=> __( 'Order by post id.', 'italystrap' ),
						'author'		=> __( 'Order by author.', 'italystrap' ),
						'title'			=> __( 'Title.', 'italystrap' ),
						'name'			=> __( 'Order by post name (post slug).', 'italystrap' ),
						'type'			=> __( 'Order by post type (available since Version 4.0).', 'italystrap' ),
						'date'			=> __( 'Order by date', 'italystrap' ),
						'modified'			=> __( 'Order by last modified date.', 'italystrap' ),
						'parent'			=> __( 'Order by post/page parent id.', 'italystrap' ),
						'rand'			=> __( 'Random order.', 'italystrap' ),
						'comment_count'	=> __( 'Order by number of comments.', 'italystrap' ),
						'menu_order'	=> __( 'Order by Page Order.', 'italystrap' ),
						'meta_value'	=> __( 'Order by value in custom field ("meta_key=keyname" must also be present in the query)', 'italystrap' ),
						'meta_value_num'	=> __( 'Order by value in custom field ("meta_key=keyname" must also be present in the query)', 'italystrap' ),
						'post__in'		=> __( 'Preserve post ID order given in the post__in array.', 'italystrap' ),
					),
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'order',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'meta_key'			=> array(
				'name'		=> __( 'Order by meta key (custom fields)', 'italystrap' ),
				'desc'		=> __( 'Inser the custom field separated by comma and select "Order by Value..." above.', 'italystrap' ),
				'id'		=> 'meta_key',
				'type'		=> 'text',
				'class'		=> 'widefat meta_key',
				'default'	=> '',
				'validate'	=> 'alpha_dash',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'order',
				 ),

	/**
	 * Custom text or HTML markup.
	 */
	'order'					=> array(
				'name'		=> __( 'Order', 'italystrap' ),
				'desc'		=> __( 'How posts have to be ordered.', 'italystrap' ),
				'id'		=> 'order',
				'type'		=> 'select',
				'class'		=> 'widefat order',
				'default'	=> 'DESC',
				'options'	=> array(
					'DESC'	=> __( 'Descending', 'italystrap' ),
					'ASC'	=> __( 'Ascending', 'italystrap' ),
					),
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'order',
				 ),

);
