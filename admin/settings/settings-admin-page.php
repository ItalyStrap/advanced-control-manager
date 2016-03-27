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
	'widget'	=> array(
		'tab_title'			=> __( 'Widget', 'italystrap' ),
		'id'				=> 'italystrap_pluginPage_section',
		'title'				=> __( 'ItalyStrap options page for widget', 'italystrap' ),
		'callback'			=> 'widget_section',
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'vcardwidget',
				'title'		=> __( 'Vcard Widget Local Business', 'italystrap' ),
				'callback'	=> 'option_vcardwidget',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_pluginPage_section',
				'args'		=> null,
			),
			array(
				'id'		=> 'post_widget',
				'title'		=> __( 'Post Widget for Custom Loop', 'italystrap' ),
				'callback'	=> 'option_post_widget',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_pluginPage_section',
				'args'		=> null,
			),
			array(
				'id'		=> 'media_widget',
				'title'		=> __( 'Widget for Media Carousel', 'italystrap' ),
				'callback'	=> 'option_media_widget',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_pluginPage_section',
				'args'		=> null,
			),
		),
	),
	'script'	=> array(
		'tab_title'			=> __( 'Script', 'italystrap' ),
		'id'				=> 'italystrap_pluginPage_section2',
		'title'				=> __( 'ItalyStrap options page for script', 'italystrap' ),
		'description'		=> __( 'Script Description', 'italystrap' ),
		'callback'			=> 'script_section',
		'page'				=> 'italystrap_options_group',
		'settings_fields'	=> array(
			array(
				'id'		=> 'activate_custom_css',
				'title'		=> __( 'Activate Custom CSS', 'italystrap' ),
				'callback'	=> 'option_vcardwidget',
				'page'		=> 'italystrap_options_group',
				'section'	=> 'italystrap_pluginPage_section2',
				'args'		=> null,
			),
			array(
				'id'			=> 'custom_css',
				'title'			=> __( 'Custom CSS', 'italystrap' ),
				'callback'		=> 'option_custom_css',
				'page'			=> 'italystrap_options_group',
				'section'		=> 'italystrap_pluginPage_section2',
				'args'			=> array(
						'_name'			=> 'italystrap_settings[custom_css]',
						'_id'			=> 'italystrap_settings[custom_css]',
						'name'			=> __( 'Custom CSS', 'italystrap' ),
						'desc'			=> __( 'Enter your custom CSS, this styles will be included verbatim in <style> tags in the <head> element of your html. The code will appear before styles that were registered individually and after your styles enqueued with the WordPress API.', 'italystrap' ),
						'id'			=> 'custom_css',
						'type'			=> 'textarea',
						'class'		=> 'custom_css',
						'rows'			=> 5,
						'cols'			=> 70,
						'placeholder'	=> '.my_css{color:#fff;}',
						'default'		=> '',
						'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
			array(
				'id'			=> 'custom_js',
				'title'			=> __( 'Custom JavaScript', 'italystrap' ),
				'callback'		=> 'option_custom_js',
				'page'			=> 'italystrap_options_group',
				'section'		=> 'italystrap_pluginPage_section2',
				'args'			=> array(
						'_name'			=> 'italystrap_settings[custom_js]',
						'_id'			=> 'italystrap_settings[custom_js]',
						'name'			=> __( 'Custom JavaScript', 'italystrap' ),
						'desc'			=> __( 'Enter your custom JavaScript, this styles will be included verbatim in <script> tags before the </body> element of your html. The code will appear before script that were registered individually and after your script enqueued with the WordPress API.', 'italystrap' ),
						'id'			=> 'custom_js',
						'type'			=> 'textarea',
						'class'		=> 'custom_js',
						'rows'			=> 5,
						'cols'			=> 70,
						'placeholder'	=> '',
						'default'		=> '',
						'validate'	=> 'ctype_alpha',
						'sanitize'		=> 'sanitize_text_field',
				),
			),
		),
	),
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
		),
	),
);
