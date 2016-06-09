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
		'id'				=> 'general',
		'title'				=> __( 'ItalyStrap options page for general', 'italystrap' ),
		'callback'			=> 'render_section_cb',
		'desc'				=> __( 'General setting for ItalyStrap plugin', 'italystrap' ),
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'show-ids',
				'title'		=> __( 'Show post_type IDs', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'general',
				'args'		=> array(
						'name'			=> __( 'Show post_type IDs', 'italystrap' ),
						'desc'			=> __( 'Show post_type IDs on table in post type edit screen', 'italystrap' ),
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
				'title'		=> __( 'Show post_type Thumb', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'general',
				'args'		=> array(
						'name'			=> __( 'Show post_type Thumb', 'italystrap' ),
						'desc'			=> __( 'Show post_type Thumb on table in post type edit screen', 'italystrap' ),
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
				'section'	=> 'general',
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
			array(
				'id'		=> 'web_font_loading',
				'title'		=> __( 'Web font Loading', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'general',
				'args'		=> array(
						'name'			=> __( 'Web font Loading', 'italystrap' ),
						'desc'			=> __( 'Activate it for using the web fonts loader, then you have to go to the customizer and select the font, weight and subsets you want to use in this site, after done that you have to add some CSS to your style, you can put it under ItalyStrap > Settings > Style > Custom CSS.
							<br>You can apply the font to any HTML5 elements in your page like body, main, header, footer, hx, p, ecc, remember that for each elements you have to put first the class <code>.fonts-loaded</code> and then the name of the single elements at time.
							<br>Example:
							<br><pre><code>.fonts-loaded body{ font-family: "Open Sans"; }<br>.fonts-loaded h1{ font-family: "Lato"; }</code></pre>', 'italystrap' ),
						'id'			=> 'web_font_loading',
						'type'			=> 'checkbox',
						'class'			=> 'web_font_loading',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'google_api_key',
				'title'		=> __( 'Google API Key', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'general',
				'args'		=> array(
						'name'			=> __( 'Google API Key', 'italystrap' ),
						'desc'			=> __( 'Insert here the google API key.', 'italystrap' ),
						'id'			=> 'google_api_key',
						'type'			=> 'text',
						'class'			=> 'google_api_key',
						'default'		=> '',
						'validate'		=> 'ctype_alpha',
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
		'id'				=> 'widget',
		'title'				=> __( 'ItalyStrap options page for widget', 'italystrap' ),
		'callback'			=> 'render_section_cb',
		'desc'				=> __( 'Select the widgets you want to use.', 'italystrap' ),
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'vcardwidget',
				'title'		=> __( 'vCard Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'widget',
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
				'section'	=> 'widget',
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
				'section'	=> 'widget',
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
				'section'	=> 'widget',
				'args'		=> array(
						'name'			=> __( 'Image Widget', 'italystrap' ),
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
				'section'	=> 'widget',
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
		'id'				=> 'shortcode',
		'title'				=> __( 'ItalyStrap options page for shortcode', 'italystrap' ),
		'callback'			=> 'render_section_cb',
		'desc'				=> __( 'Select the shortcodes you want to use.', 'italystrap' ),
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'media_carousel_shortcode',
				'title'		=> __( 'Carousel Shortcode', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'shortcode',
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
	 * This is the CSS configuration
	 */
	'css'	=> array(
		'tab_title'			=> __( 'CSS', 'italystrap' ),
		'id'				=> 'css',
		'title'				=> __( 'ItalyStrap options page for CSS', 'italystrap' ),
		'callback'			=> 'render_section_cb',
		'desc'				=> __( 'This is the tab for changing the style of your site. Code entered here will be included in every page of the front-end of your site.', 'italystrap' ),
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'activate_custom_css',
				'title'		=> __( 'Activate Custom CSS', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'css',
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
				'section'		=> 'css',
				'args'			=> array(
						'_name'			=> 'italystrap_settings[custom_css]',
						'_id'			=> 'italystrap_settings[custom_css]',
						'name'			=> __( 'Custom CSS', 'italystrap' ),
						'desc'			=> __( 'Enter your custom CSS, this styles will be included verbatim in <style> tags in the <head> element of your html. The code will appear before styles that were registered individually and after your styles enqueued with the WordPress API.', 'italystrap' ),
						'id'			=> 'custom_css',
						'type'			=> 'textarea',
						'class'			=> 'custom_css',
						'rows'			=> 20,
						'cols'			=> 100,
						'placeholder'	=> '.my_css{color:#fff;}',
						'default'		=> '',
						'validate'		=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'			=> 'body_class',
				'title'			=> __( 'Body Class', 'italystrap' ),
				'callback'		=> 'get_field_type',
				'page'			=> 'italystrap_options_group',
				'section'		=> 'css',
				'args'			=> array(
						'name'			=> __( 'Body Class', 'italystrap' ),
						'desc'			=> __( 'This will add a CSS class to body_class filter in every page.', 'italystrap' ),
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
				'section'		=> 'css',
				'args'			=> array(
						'name'			=> __( 'Post Class', 'italystrap' ),
						'desc'			=> __( 'This will add a CSS class to post_class filter in every page.', 'italystrap' ),
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
	 * This is the Script configuration
	 */
	'js'	=> array(
		'tab_title'			=> __( 'JS', 'italystrap' ),
		'id'				=> 'js',
		'title'				=> __( 'ItalyStrap options page for script', 'italystrap' ),
		'desc'				=> __( 'Some functionality for JS', 'italystrap' ),
		'callback'			=> 'render_section_cb',
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'google_analytics_id',
				'title'		=> __( 'Activate Google Analytics', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'js',
				'args'		=> array(
						'name'			=> __( 'Analytics ID', 'italystrap' ),
						'desc'			=> __( 'Insert your google analytics ID', 'italystrap' ),
						'id'			=> 'google_analytics_id',
						'type'			=> 'text',
						'class'			=> 'google_analytics_id',
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
	'lazyload'	=> array(
		'tab_title'			=> __( 'LazyLoad', 'italystrap' ),
		'id'				=> 'lazyload',
		'title'				=> __( 'ItalyStrap options page for lazyload', 'italystrap' ),
		'callback'			=> 'render_section_cb',
		'desc'				=> __( 'Load your image later.', 'italystrap' ),
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'lazyload',
				'title'		=> __( 'LazyLoad', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'lazyload',
				'args'		=> array(
						'name'			=> __( 'LazyLoad', 'italystrap' ),
						'desc'			=> __( 'Activate LazyLoad for images', 'italystrap' ),
						'id'			=> 'lazyload',
						'type'			=> 'checkbox',
						'class'			=> 'lazyload',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'			=> 'lazyload-custom-placeholder',
				'title'			=> __( 'Custom Placeholder', 'italystrap' ),
				'callback'		=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'lazyload',
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
