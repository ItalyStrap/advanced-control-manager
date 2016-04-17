<?php
/**
 * Settingd for plugin options page
 *
 * This is the file with settings for ItalyStrap options page
 *
 * @since 2.0.0
 *
 * @package Italystrap
 */

namespace ItalyStrap\Admin;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

return array(
	/**
	 * This is the Lazy Load configuration
	 */
	'general'	=> array(
		'tab_title'			=> __( 'General', 'italystrap' ),
		'id'				=> 'italystrap_general_section',
		'title'				=> __( 'ItalyStrap options page for general', 'italystrap' ),
		'callback'			=> 'general_section',
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'show-ids',
				'title'		=> __( 'Show IDs', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_general_section',
				'args'		=> array(
						'name'			=> __( 'Show IDs', 'italystrap' ),
						'desc'			=> __( 'Show IDs on table in post type edit screen', 'italystrap' ),
						'id'			=> 'show-ids',
						'type'			=> 'checkbox',
						'class'			=> 'show-ids',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'show-thumb',
				'title'		=> __( 'Show Thumb', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_general_section',
				'args'		=> array(
						'name'			=> __( 'Show Thumb', 'italystrap' ),
						'desc'			=> __( 'Show Thumb on table in post type edit screen', 'italystrap' ),
						'id'			=> 'show-thumb',
						'type'			=> 'checkbox',
						'class'			=> 'show-thumb',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'kill-emojis',
				'title'		=> __( 'Kill the Emojis', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_general_section',
				'args'		=> array(
						'name'			=> __( 'Kill the Emojis', 'italystrap' ),
						'desc'			=> __( 'If you don\'t use it kill it.', 'italystrap' ),
						'id'			=> 'kill-emojis',
						'type'			=> 'checkbox',
						'class'			=> 'kill-emojis',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
		),
	),
	/**
	 * This is the Widget configuration
	 */
	'widget'	=> array(
		'tab_title'			=> __( 'Widget', 'italystrap' ),
		'id'				=> 'italystrap_pluginPage_section',
		'title'				=> __( 'ItalyStrap options page for widget', 'italystrap' ),
		'callback'			=> 'widget_section',
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'vcardwidget',
				'title'		=> __( 'vCard Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_pluginPage_section',
				'args'		=> array(
						'name'			=> __( 'vCard Widget for Local Business', 'italystrap' ),
						'desc'			=> __( 'Activate a widget for vCard Local Business with schema.org murkup', 'italystrap' ),
						'id'			=> 'vcardwidget',
						'type'			=> 'checkbox',
						'class'			=> 'vcardwidget',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'post_widget',
				'title'		=> __( 'Posts Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_pluginPage_section',
				'args'		=> array(
						'name'			=> __( 'Posts Widget for Custom Loop', 'italystrap' ),
						'desc'			=> __( 'Activate posts widget and displays list of posts with an array of options', 'italystrap' ),
						'id'			=> 'post_widget',
						'type'			=> 'checkbox',
						'class'			=> 'post_widget',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'widget_product',
				'title'		=> __( 'Product Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_pluginPage_section',
				'args'		=> array(
						'name'			=> __( 'Product Widget for Custom Loop', 'italystrap' ),
						'desc'			=> __( 'Activate product widget and displays list of product with an array of options', 'italystrap' ),
						'id'			=> 'widget_product',
						'type'			=> 'checkbox',
						'class'			=> 'widget_product',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'widget_image',
				'title'		=> __( 'Image Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_pluginPage_section',
				'args'		=> array(
						'name'			=> __( 'Image Widget for Custom Loop', 'italystrap' ),
						'desc'			=> __( 'Activate image widget and displays an image from media with an array of options', 'italystrap' ),
						'id'			=> 'widget_image',
						'type'			=> 'checkbox',
						'class'			=> 'widget_image',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'media_carousel_widget',
				'title'		=> __( 'Carousel Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_pluginPage_section',
				'args'		=> array(
						'name'			=> __( 'Widget for Media Carousel', 'italystrap' ),
						'desc'			=> __( 'this will activate a Bootstrap media Carousel with a ton of options, Make shure you have a Twitter Bootstrap CSS in your site', 'italystrap' ),
						'id'			=> 'media_carousel_widget',
						'type'			=> 'checkbox',
						'class'			=> 'media_carousel_widget',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
		),
	),
	/**
	 * This is the Lazy Load configuration
	 */
	'shortcode'	=> array(
		'tab_title'			=> __( 'Shortcode', 'italystrap' ),
		'id'				=> 'italystrap_shortcode_section',
		'title'				=> __( 'ItalyStrap options page for shortcode', 'italystrap' ),
		'callback'			=> 'shortcode_section',
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'media_carousel_shortcode',
				'title'		=> __( 'Carousel Shortcode', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_shortcode_section',
				'args'		=> array(
						'name'			=> __( 'Shortcode for Media Carousel', 'italystrap' ),
						'desc'			=> __( 'This will activate a Bootstrap media Carousel inside built-in WordPress gallery shortcode with a ton of options, Make shure you have a Twitter Bootstrap CSS in your site', 'italystrap' ),
						'id'			=> 'media_carousel_shortcode',
						'type'			=> 'checkbox',
						'class'			=> 'media_carousel_shortcode',
						'default'		=> '1',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
		),
	),
	/**
	 * This is the Script configuration
	 */
	'script'	=> array(
		'tab_title'			=> __( 'Script', 'italystrap' ),
		'id'				=> 'italystrap_pluginPage_section2',
		'title'				=> __( 'ItalyStrap options page for script', 'italystrap' ),
		'description'		=> __( 'Script Description', 'italystrap' ),
		'callback'			=> 'script_section',
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'activate_custom_script',
				'title'		=> __( 'Activate Custom script', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_pluginPage_section2',
				'args'		=> array(
						'name'			=> __( 'Activate', 'italystrap' ),
						'desc'			=> __( 'If you don\'t activate it the CSS and JS will not work.', 'italystrap' ),
						'id'			=> 'activate_custom_script',
						'type'			=> 'checkbox',
						'class'			=> 'activate_custom_script',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'activate_custom_css',
				'title'		=> __( 'Activate Custom CSS', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_pluginPage_section2',
				'args'		=> array(
						'name'			=> __( 'Custom CSS', 'italystrap' ),
						'desc'			=> __( 'Activate Custom CSS functionality', 'italystrap' ),
						'id'			=> 'activate_custom_css',
						'type'			=> 'checkbox',
						'class'			=> 'activate_custom_css',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'			=> 'custom_css',
				'title'			=> __( 'Custom CSS', 'italystrap' ),
				'callback'		=> 'get_field_type',
				'page'			=> 'italystrap_options_group',
				'section'		=> 'italystrap_pluginPage_section2',
				'args'			=> array(
						'_name'			=> 'italystrap_settings[custom_css]',
						'_id'			=> 'italystrap_settings[custom_css]',
						'name'			=> __( 'Custom CSS', 'italystrap' ),
						'desc'			=> __( 'Enter your custom CSS, this styles will be included verbatim in <style> tags in the <head> element of your html. The code will appear before styles that were registered individually and after your styles enqueued with the WordPress API.', 'italystrap' ),
						'id'			=> 'custom_css',
						'type'			=> 'textarea',
						'class'			=> 'custom_css',
						'rows'			=> 5,
						'cols'			=> 70,
						'placeholder'	=> '.my_css{color:#fff;}',
						'default'		=> '',
						'validate'		=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			// array(
			// 	'id'			=> 'custom_js',
			// 	'title'			=> __( 'Custom JavaScript', 'italystrap' ),
			// 	'callback'		=> 'get_field_type',
			// 	'page'			=> 'italystrap_options_group',
			// 	'section'		=> 'italystrap_pluginPage_section2',
			// 	'args'			=> array(
			// 			'_name'			=> 'italystrap_settings[custom_js]',
			// 			'_id'			=> 'italystrap_settings[custom_js]',
			// 			'name'			=> __( 'Custom JavaScript', 'italystrap' ),
			// 			'desc'			=> __( 'Enter your custom JavaScript, this styles will be included verbatim in <script> tags before the </body> element of your html. The code will appear before script that were registered individually and after your script enqueued with the WordPress API.', 'italystrap' ),
			// 			'id'			=> 'custom_js',
			// 			'type'			=> 'textarea',
			// 			'class'			=> 'custom_js',
			// 			'rows'			=> 5,
			// 			'cols'			=> 70,
			// 			'placeholder'	=> '',
			// 			'default'		=> '',
			// 			'validate'		=> 'ctype_alpha',
			// 			'sanitize'		=> 'sanitize_text_field',
			// 	),
			// ),
			array(
				'id'			=> 'body_class',
				'title'			=> __( 'Body Class', 'italystrap' ),
				'callback'		=> 'get_field_type',
				'page'			=> 'italystrap_options_group',
				'section'		=> 'italystrap_pluginPage_section2',
				'args'			=> array(
						'name'			=> __( 'Body Class', 'italystrap' ),
						'desc'			=> __( 'This will add a CSS class to body_class filter', 'italystrap' ),
						'id'			=> 'body_class',
						'type'			=> 'text',
						'class'			=> 'body_class',
						'placeholder'	=> '',
						'default'		=> '',
						'validate'		=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'			=> 'post_class',
				'title'			=> __( 'Post Class', 'italystrap' ),
				'callback'		=> 'get_field_type',
				'page'			=> 'italystrap_options_group',
				'section'		=> 'italystrap_pluginPage_section2',
				'args'			=> array(
						'name'			=> __( 'Post Class', 'italystrap' ),
						'desc'			=> __( 'This will add a CSS class to post_class filter', 'italystrap' ),
						'id'			=> 'post_class',
						'type'			=> 'text',
						'class'			=> 'post_class',
						'placeholder'	=> '',
						'default'		=> '',
						'validate'		=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
		),
	),
	/**
	 * This is the Lazy Load configuration
	 */
	'lazyload'	=> array(
		'tab_title'			=> __( 'LazyLoad', 'italystrap' ),
		'id'				=> 'italystrap_lazyload_section',
		'title'				=> __( 'ItalyStrap options page for lazyload', 'italystrap' ),
		'callback'			=> 'lazyload_section',
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'lazyload',
				'title'		=> __( 'LazyLoad', 'italystrap' ),
				'callback'	=> 'option_lazyload',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_lazyload_section',
				'args'		=> null,
			),
			array(
				'id'			=> 'lazyload-custom-placeholder',
				'title'			=> __( 'Custom Placeholder', 'italystrap' ),
				'callback'		=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_lazyload_section',
				'args'			=> array(
						'name'			=> __( 'Custom Placeholder', 'italystrap' ),
						'desc'			=> __( 'Insert here your custom placeholder for lazyload image, this is the src attribute of the img tag, example: http://www.mysite.tld/wp-content/upload/media/my-placeholder.gif', 'italystrap' ),
						'id'			=> 'lazyload-custom-placeholder',
						'type'			=> 'text',
						'class'			=> 'lazyload-custom-placeholder',
						'placeholder'	=> '',
						'default'		=> '',
						'validate'		=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
		),
	),
);
