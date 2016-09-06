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

$wp_upload_dir = wp_upload_dir();

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
				'title'		=> __( 'Show Post Type IDs', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'general',
				'args'		=> array(
						'name'			=> __( 'Show Post Type IDs', 'italystrap' ),
						'desc'			=> __( 'Show Post Type IDs on table in post type edit screen', 'italystrap' ),
						'id'			=> 'show-ids',
						'type'			=> 'checkbox',
						'class'			=> 'show-ids easy',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',				),
			),
			array(
				'id'		=> 'show-thumb',
				'title'		=> __( 'Show Post Type Thumb', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'general',
				'args'		=> array(
						'name'			=> __( 'Show the Post Type Thumbnail', 'italystrap' ),
						'desc'			=> __( 'Show Post Type Thumbnail on table in post type edit screen', 'italystrap' ),
						'id'			=> 'show-thumb',
						'type'			=> 'checkbox',
						'class'			=> 'show-thumb easy',
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
						'desc'			=> __( 'The Emojis adds extra code in the header of your theme but if you don\'t use it then kill it.', 'italystrap' ),
						'id'			=> 'kill-emojis',
						'type'			=> 'checkbox',
						'class'			=> 'kill-emojis easy',
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
						'class'			=> 'web_font_loading hard',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
				'show_on'				=> \ItalyStrap\Core\is_beta(),
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
						'class'			=> 'google_api_key easy',
						'default'		=> '',
						// 'validate'		=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
				'show_on'				=> \ItalyStrap\Core\is_beta(),
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
				'id'		=> 'widget_attributes',
				'title'		=> __( 'HTML attributes for widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'widget',
				'args'		=> array(
						'name'			=> __( 'Activate two new input HTML attributes for every widget', 'italystrap' ),
						'desc'			=> __( 'This will add two new input in every widget that allow you to add custom "id" and "class" attributes in the html widget container.', 'italystrap' ),
						'id'			=> 'widget_attributes',
						'type'			=> 'checkbox',
						'class'			=> 'widget_attributes medium',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'render_html_in_widget_title',
				'title'		=> __( 'HTML in Widget Title', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'widget',
				'args'		=> array(
						'name'			=> __( 'Render HTML in Widget Title', 'italystrap' ),
						'desc'			=> __( 'Activate this if you want to add some HTML tag to Widget title, then change open and close html tag with <code>{{ or }}</code> like this example: &lt;strong&gt;Widget Title&lt;/strong&gt; becomes {{strong}}Widget Title{{/strong}}', 'italystrap' ),
						'id'			=> 'render_html_in_widget_title',
						'type'			=> 'checkbox',
						'class'			=> 'render_html_in_widget_title medium',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'vcardwidget',
				'title'		=> __( 'vCard Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'widget',
				'args'		=> array(
						'name'			=> __( 'ItalyStrap vCard Widget for Local Business (DEPRECATED)', 'italystrap' ),
						'desc'			=> __( 'Activate a widget for vCard Local Business with schema.org markup (DEPRECATED)', 'italystrap' ),
						'id'			=> 'vcardwidget',
						'type'			=> 'checkbox',
						'class'			=> 'vcardwidget',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'widget_vcard',
				'title'		=> __( 'vCard Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'widget',
				'args'		=> array(
						'name'			=> __( 'vCard Local Business', 'italystrap' ),
						'desc'			=> __( 'Add a vCard Local Business with Schema.org markup to your theme widgetized area. (This plugin does not provide any style, you have to add it in the style.css of your theme).', 'italystrap' ),
						'id'			=> 'widget_vcard',
						'type'			=> 'checkbox',
						'class'			=> 'widget_vcard medium',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array( // DEPRECATED
				'id'		=> 'post_widget',
				'title'		=> __( 'Posts Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'widget',
				'args'		=> array(
						'name'			=> __( 'Posts Widget for Custom Loop (DEPRECATED)', 'italystrap' ),
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
				'id'		=> 'widget_post',
				'title'		=> __( 'Posts Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'widget',
				'args'		=> array(
						'name'			=> __( 'Posts Widget for Custom Loop', 'italystrap' ),
						'desc'			=> __( 'Activate posts widget and displays list of posts with an array of options. (This plugin does not provide any style, you have to add it in the style.css of your theme)', 'italystrap' ),
						'id'			=> 'widget_post',
						'type'			=> 'checkbox',
						'class'			=> 'widget_post hard',
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
						'desc'			=> __( 'Activate product widget and displays list of product with an array of options, this widget will work only if you have WooCommerce installed and activated. (This plugin does not provide any style, you have to add it in the style.css of your theme)', 'italystrap' ),
						'id'			=> 'widget_product',
						'type'			=> 'checkbox',
						'class'			=> 'widget_product hard',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
				'show_on'				=> \ItalyStrap\Core\is_beta(),
			),
			array(
				'id'		=> 'widget_image',
				'title'		=> __( 'Image Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'widget',
				'args'		=> array(
						'name'			=> __( 'Image Widget', 'italystrap' ),
						'desc'			=> __( 'Activate image widget and displays an image from media with an array of options. (This plugin does not provide any style, you have to add it in the style.css of your theme)', 'italystrap' ),
						'id'			=> 'widget_image',
						'type'			=> 'checkbox',
						'class'			=> 'widget_image medium',
						'default'		=> '',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
				'show_on'				=> \ItalyStrap\Core\is_beta(),
			),
			array(
				'id'		=> 'media_carousel_widget',
				'title'		=> __( 'Carousel Widget', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'widget',
				'args'		=> array(
						'name'			=> __( 'Widget for Media Carousel', 'italystrap' ),
						'desc'			=> __( 'this will activate a Bootstrap media Carousel with a ton of options, make shure you have a Twitter Bootstrap CSS in your site.', 'italystrap' ),
						'id'			=> 'media_carousel_widget',
						'type'			=> 'checkbox',
						'class'			=> 'media_carousel_widget medium',
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
				'id'		=> 'do_shortcode_widget_text',
				'title'		=> __( 'Do ShortCode in Widget Text', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'shortcode',
				'args'		=> array(
						'name'			=> __( 'Do ShortCode in Widget Text', 'italystrap' ),
						'desc'			=> __( 'This get you the possibility to insert any shortcode into a widget text.', 'italystrap' ),
						'id'			=> 'do_shortcode_widget_text',
						'type'			=> 'checkbox',
						'class'			=> 'do_shortcode_widget_text easy',
						'default'		=> '1',
						// 'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'		=> 'media_carousel_shortcode',
				'title'		=> __( 'Carousel Shortcode', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'shortcode',
				'args'		=> array(
						'name'			=> __( 'Shortcode for Media Carousel', 'italystrap' ),
						'desc'			=> __( 'This will activate a Bootstrap media Carousel inside built-in WordPress gallery shortcode with a ton of options, make shure you have a Twitter Bootstrap CSS in your site', 'italystrap' ),
						'id'			=> 'media_carousel_shortcode',
						'type'			=> 'checkbox',
						'class'			=> 'media_carousel_shortcode medium',
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
	'style'	=> array(
		'tab_title'			=> __( 'Style', 'italystrap' ),
		'id'				=> 'style',
		'title'				=> __( 'ItalyStrap options page for style purpose', 'italystrap' ),
		'callback'			=> 'render_section_cb',
		'desc'				=> __( 'This is the tab for changing the style of your site. Code entered here will be included in every page of the front-end of your site.', 'italystrap' ),
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'activate_custom_css',
				'title'		=> __( 'Activate Custom CSS', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'style',
				'args'		=> array(
						'name'			=> __( 'Custom CSS', 'italystrap' ),
						'desc'			=> __( 'This will add new fields in the wp editor for adding custom CSS, ID and class attribute to post/page and also let you use the new functionality below.', 'italystrap' ),
						'id'			=> 'activate_custom_css',
						'type'			=> 'checkbox',
						'class'			=> 'activate_custom_css medium',
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
				'section'		=> 'style',
				'args'			=> array(
						'_name'			=> 'italystrap_settings[custom_css]',
						'_id'			=> 'italystrap_settings[custom_css]',
						'name'			=> __( 'Custom CSS', 'italystrap' ),
						'desc'			=> __( 'Enter your custom CSS, this styles will be included verbatim in <style> tags in the <head> element of your html. The code will appear before styles that were registered individually and after your styles enqueued with the WordPress API.', 'italystrap' ),
						'id'			=> 'custom_css',
						'type'			=> 'textarea',
						'class'			=> 'custom_css medium',
						'rows'			=> 20,
						'cols'			=> 100,
						'placeholder'	=> '.my_css{color:#fff;}',
						'default'		=> '',
						// 'validate'		=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'			=> 'body_class',
				'title'			=> __( 'Body Class', 'italystrap' ),
				'callback'		=> 'get_field_type',
				'page'			=> 'italystrap_options_group',
				'section'		=> 'style',
				'args'			=> array(
						'name'			=> __( 'Body Class', 'italystrap' ),
						'desc'			=> __( 'This will add a CSS class to body_class filter in every page.', 'italystrap' ),
						'id'			=> 'body_class',
						'type'			=> 'text',
						'class'			=> 'body_class medium',
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
				'section'		=> 'style',
				'args'			=> array(
						'name'			=> __( 'Post Class', 'italystrap' ),
						'desc'			=> __( 'This will add a CSS class to post_class filter in every page.', 'italystrap' ),
						'id'			=> 'post_class',
						'type'			=> 'text',
						'class'			=> 'post_class medium',
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
	// 'script'	=> array(
	// 	'tab_title'			=> __( 'Script', 'italystrap' ),
	// 	'id'				=> 'script',
	// 	'title'				=> __( 'ItalyStrap options page for script', 'italystrap' ),
	// 	'desc'				=> __( 'Some functionality for JS', 'italystrap' ),
	// 	'callback'			=> 'render_section_cb',
	// 	'page'				=> 'italystrap_options_group',
	// 	'settings_fields'	=> array(
	// 		array(
	// 			'id'		=> 'google_analytics_id',
	// 			'title'		=> __( 'Activate Google Analytics', 'italystrap' ),
	// 			'callback'	=> 'get_field_type',
	// 			'page'		=> 'italystrap_options_group',
	// 			'section'	=> 'script',
	// 			'args'		=> array(
	// 					'name'			=> __( 'Analytics ID', 'italystrap' ),
	// 					'desc'			=> __( 'Insert your google analytics ID', 'italystrap' ),
	// 					'id'			=> 'google_analytics_id',
	// 					'type'			=> 'text',
	// 					'class'			=> 'google_analytics_id',
	// 					'default'		=> '',
	// 					// 'validate'	=> 'ctype_alpha',
	// 					'sanitize'		=> 'sanitize_text_field',
	// 			),
	// 			'show_on'				=> \ItalyStrap\Core\is_beta(),
	// 		),
	// 	),
	// ),
	/**
	 * This is the Lazy Load configuration
	 */
	'media'	=> array(
		'tab_title'			=> __( 'Media', 'italystrap' ),
		'id'				=> 'media',
		'title'				=> __( 'Settings page for media addon functionality', 'italystrap' ),
		'callback'			=> 'render_section_cb',
		'desc'				=> __( 'In this section you can customize the way your WordPress handles media.', 'italystrap' ),
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'lazyload',
				'title'		=> __( 'LazyLoad', 'italystrap' ),
				'callback'	=> 'get_field_type',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'media',
				'args'		=> array(
						'name'			=> __( 'LazyLoad', 'italystrap' ),
						'desc'			=> __( 'Activate LazyLoad for images', 'italystrap' ),
						'id'			=> 'lazyload',
						'type'			=> 'checkbox',
						'class'			=> 'lazyload easy',
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
				'section'	=> 'media',
				'args'			=> array(
						'name'			=> __( 'Custom Placeholder (Optional)', 'italystrap' ),
						'desc'			=> __( 'Insert here your custom placeholder for image lazyloading, this is the src attribute of the img tag.
							<br>Example:', 'italystrap' ) . ' ' . $wp_upload_dir['url'] . '/my-placeholder.gif' . '
							<br>Default: <code>data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7</code>',
						'id'			=> 'lazyload-custom-placeholder',
						'type'			=> 'text',
						'class'			=> 'lazyload-custom-placeholder medium',
						'placeholder'	=> '',
						'default'		=> 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
						'validate'		=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
				'show_on'				=> \ItalyStrap\Core\is_beta(),
			),
		),
	),
);
