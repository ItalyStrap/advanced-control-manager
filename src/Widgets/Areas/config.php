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
	'priority'		=> 'high',
	'fields'		=> array(
		'action'			=> array(
			'name'				=> __( 'Display on', 'italystrap' ),
			'desc'				=> __( 'Select the position to display this widget area.', 'italystrap' ),
			'id'				=> $this->_prefix . '_action',
			'type'				=> 'select',
			'show_option_none'	=> true,
			'default'			=> '',
			'sanitize'			=> 'sanitize_text_field',
			'options'			=> $position,
			'attributes'		=> array( 'placeholder' => '' ),
		),
		'priority'			=> array(
			'name'				=> __( 'Priority', 'italystrap' ),
			'desc'				=> __( 'Type the priority you want to display this widget area, it must be a number, by default the priority is 10, you can choose a number between 1 and 99999, this is useful when you want to display more than one widget area in the same position but in different order.', 'italystrap' ),
			'id'				=> $this->_prefix . '_priority',
			'type'				=> 'text',
			'default'			=> 10,
			'sanitize'			=> 'absint',
			'attributes'		=> array( 'placeholder' => '' ),
		),
		'container_width'	=> array(
			'name'				=> __( 'Width', 'italystrap' ),
			'desc'				=> __( 'Select the width of this widget area.', 'italystrap' ),
			'id'				=> $this->_prefix . '_container_width',
			'type'				=> 'select',
			'default'			=> 'container-fluid',
			'sanitize'			=> 'sanitize_text_field',
			'options'			=> array(
				// 'none'				=> __( 'None', 'italystrap' ),
				'container-fluid'	=> __( 'Full witdh', 'italystrap' ),
				'container'			=> __( 'Standard width', 'italystrap' ),
			),
			'attributes'		=> array( 'placeholder' => '' ),
		),
		'background_color'	=> array(
			'name'		=> __( 'Background color', 'italystrap' ),
			'desc'		=> __( 'Choose the color for the backgrount of the widget area.', 'italystrap' ),
			'id'		=> $this->_prefix . '_background_color',
			'type'		=> 'colorpicker',
			'default'	=> '',
			'sanitize'	=> 'sanitize_text_field',
		),
		'background_image'	=> array(
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
		'widget_area_class'	=> array(
			'name'		=> __( 'Custom CSS class', 'italystrap' ),
			'desc'		=> __( 'Custom CSS class attribute for the widget_area box.', 'itallystrap' ),
			'id'		=> $this->_prefix . '_widget_area_class',
			'type'		=> 'text',
			'default'	=> '',
			'sanitize'	=> 'sanitize_text_field',
		),
	),
);
