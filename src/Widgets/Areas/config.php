<?php
/**
 * Configuration file for Widget Areas
 *
 * [Long Description.]
 *
 * @link http://italystrap.com
 * @since 2.5.1
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Widgets\Areas;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

$position = apply_filters( 'italystrap_widget_area_position', array() );

return array(
	'id'			=> $this->prefix . '-widget-areas-metabox',
	'title'			=> __( 'Widget Area settings', 'italystrap' ),
	'object_types'	=> array( 'sidebar' ),
	'context'		=> 'normal',
	'priority'		=> 'low',
	'fields'		=> array(
		'action'					=> array(
			'name'				=> __( 'Display on', 'italystrap' ),
			'desc'				=> __( 'Select the position to display this widget area.', 'italystrap' ),
			'id'				=> $this->_prefix . '_action',
			'type'				=> 'select',
			'show_option_none'	=> true,
			'default'			=> '',
			'sanitize'			=> 'sanitize_text_field',
			'options'			=> $position,
			'show_on'			=> (bool) ! empty( $position ),
		),
		'priority'					=> array(
			'name'				=> __( 'Priority', 'italystrap' ),
			'desc'				=> __( 'Type the priority you want to display this widget area, it must be a number, by default the priority is 10, you can choose a number between 1 and 99999, this is useful when you want to display more than one widget area in the same position but in different order.', 'italystrap' ),
			'id'				=> $this->_prefix . '_priority',
			'type'				=> 'text',
			'default'			=> 10,
			'sanitize'			=> 'absint',
			'show_on'			=> (bool) ! empty( $position ),
		),
		'container_width'			=> array(
			'name'				=> __( 'Width', 'italystrap' ),
			'desc'				=> __( 'Select the width of this widget area.', 'italystrap' ),
			'id'				=> $this->_prefix . '_container_width',
			'type'				=> 'select',
			'default'			=> 'container',
			'sanitize'			=> 'sanitize_text_field',
			'options'			=> array(
				'none'				=> __( 'None', 'italystrap' ),
				'container-fluid'	=> __( 'Full witdh', 'italystrap' ),
				'container'			=> __( 'Standard width', 'italystrap' ),
			),
			'attributes'		=> array( 'placeholder' => '' ),
		),
		'background_color'			=> array(
			'name'		=> __( 'Background color', 'italystrap' ),
			'desc'		=> __( 'Choose the color for the background of the widget area.', 'italystrap' ),
			'id'		=> $this->_prefix . '_background_color',
			'type'		=> 'colorpicker',
			'default'	=> '',
			'sanitize'	=> 'sanitize_hex_color',
		),
		'background_image'			=> array(
			'name'		=> __( 'Add background image', 'italystrap' ),
			'desc'		=> __( 'Upload an image.', 'itallystrap' ),
			'id'		=> $this->_prefix . '_background_image',
			'type'		=> 'file',
			'default'	=> null,
			'sanitize'	=> 'sanitize_text_field',
			// Optional:
			'options'	=> array(
				'url'	=> false, // Hide the text input for the url
			),
			'text'		=> array(
				'add_upload_file_text' => __( 'Select image', 'italystrap' ) // Change upload button text. Default: "Add or Upload File"
			),
		),
		'background_overlay_color'			=> array(
			'name'		=> __( 'Background overlay color', 'italystrap' ),
			'desc'		=> __( 'Choose the color for the background overlay of the widget area.', 'italystrap' ),
			'id'		=> $this->_prefix . '_background_overlay_color',
			'type'		=> 'colorpicker',
			'default'	=> '',
			'sanitize'	=> 'sanitize_hex_color',
		),
		/**
		 * numeric CMB2 fields
		 * @link https://gist.github.com/jtsternberg/c09f5deb7d818d0d170b
		 */
		'background_overlay_color_opacity'			=> array(
			'name'		=> __( 'Background overlay color opacity', 'italystrap' ),
			'desc'		=> __( 'Choose the background overlay opacity of the widget area.', 'italystrap' ),
			'id'		=> $this->_prefix . '_background_overlay_color_opacity',
			'type'		=> 'text',
			'attributes' => array(
				'type'		=> 'number',
				'pattern'	=> '\d*',
			),
			'default'	=> '',
			'sanitize'	=> 'sanitize_text_field',
		),
		'widget_area_class'			=> array(
			'name'		=> __( 'Widget Area Class', 'italystrap' ),
			'desc'		=> __( 'Custom CSS class selector for the Widget Area container.', 'itallystrap' ),
			'id'		=> $this->_prefix . '_widget_area_class',
			'type'		=> 'text',
			'default'	=> '',
			'sanitize'	=> 'sanitize_text_field',
		),
		'widget_before_after'		=> array(
			'name'		=> __( 'Before/After Widget', 'italystrap' ),
			'desc'		=> __( 'This is the HTML tag for the Widgets added in this Widget Area/Sidebar. Default: div', 'itallystrap' ),
			'id'		=> $this->_prefix . '_widget_before_after',
			'type'		=> 'text',
			'default'	=> 'div',
			'sanitize'	=> 'sanitize_text_field',
		),
		'widget_title_before_after'	=> array(
			'name'		=> __( 'Before/After Widget Title', 'italystrap' ),
			'desc'		=> __( 'This is the HTML tag for the Widget Title added in this Widget Area/Sidebar. Default: h3', 'itallystrap' ),
			'id'		=> $this->_prefix . '_widget_title_before_after',
			'type'		=> 'text',
			'default'	=> 'h3',
			'sanitize'	=> 'sanitize_text_field',
		),
		'widget_title_class'		=> array(
			'name'		=> __( 'Widget Title Class', 'italystrap' ),
			'desc'		=> __( 'Custom CSS class selector for the Widget Title.', 'itallystrap' ),
			'id'		=> $this->_prefix . '_widget_title_class',
			'type'		=> 'text',
			'default'	=> '',
			'sanitize'	=> 'sanitize_text_field',
		),
		// 'widget_area_visibility'		=> array(
		// 	'name'		=> __( 'Widget Area Visibility', 'italystrap' ),
		// 	// 'desc'		=> __( 'Custom CSS class selector for the Widget Title.', 'itallystrap' ),
		// 	'id'		=> $this->_prefix . '_widget_area_visibility',
		// 	'type'		=> 'text',
		// 	'before_row' => 'ItalyStrap\Widgets\Visibility\Visibility_Admin::widget_conditions_admin',
		// 	'default'	=> '',
		// 	'sanitize'	=> 'sanitize_text_field',
		// ),
	),
);
