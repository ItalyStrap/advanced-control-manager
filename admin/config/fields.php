<?php
declare(strict_types=1);

use ItalyStrap\Settings\Sections as S;
use ItalyStrap\DataParser\Filters\SanitizeFilter as SF;

$desc = __( 'This is the description of the field, you can change it in the configuration array.', 'italystrap' );

return [
	[
		'label'			=> __( 'Callable', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'callable',
		'value'			=> 'some default value',
		SF::KEY			=> 'sanitize_text_field',
		'callback'		=> function ( array $args_for_field ) {
			return 'This is a callback called instead of the field render method.';
		},
	],
	[
		'label'			=> __( 'Text', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'text',
		'type'			=> 'text',
		'value'			=> 'some default value',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Button', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'button',
		'type'			=> 'button',
		'value'			=> 'Click',
		'class'			=> 'button button-primary', // CSS class for field
		S::LABEL_CLASS	=> 'ciao', // CSS class for the label of the add_settings_field
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Color', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'color',
		'type'			=> 'color',
		'value'			=> '',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Date', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'date',
		'type'			=> 'date',
		'value'			=> '',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Datetime', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'datetime',
		'type'			=> 'datetime',
		'value'			=> '',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'datetime-local', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'datetime-local',
		'type'			=> 'datetime-local',
		'value'			=> '',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Email', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'email',
		'type'			=> 'email',
		'validate'		=> 'is_email',
		'sanitize'		=> 'sanitize_text_field',
		// 'option_type'	=> 'theme_mod',
	],
	[
		'label'			=> __( 'File', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'file',
		'type'			=> 'file',
		'sanitize'		=> 'sanitize_text_field',
	],
//	[
//		'label'			=> __( 'Image', 'italystrap' ),
//	'desc'			=> $desc,
//		'id'			=> 'image',
//		'type'			=> 'image',
//		'sanitize'		=> 'sanitize_text_field',
//	],
	[
		'label'			=> __( 'Month', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'month',
		'type'			=> 'month',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Number', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'number',
		'type'			=> 'number',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Password', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'password',
		'type'			=> 'password',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Range', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'range',
		'type'			=> 'range',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Search', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'search',
		'type'			=> 'search',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Submit', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'submit-is',
		'type'			=> 'submit',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Tel', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'tel',
		'type'			=> 'tel',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Time', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'time',
		'type'			=> 'time',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Url', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'url',
		'type'			=> 'url',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Week', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'week',
		'type'			=> 'week',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Checkbox', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'checkbox',
		'type'			=> 'checkbox',
		'value'			=> 1,
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Radio', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'radio',
		'type'			=> 'radio',
		'value'			=> 'key2',
		'options'		=> [
			'key1'	=> 'value1',
			'key2'	=> 'value2',
			'key3'	=> 'value3',
		],
		'sanitize'		=> 'sanitize_text_field',
	],
//	[
//		'label'			=> __( 'Editor', 'italystrap' ),
//	'desc'			=> $desc,
//		'id'			=> 'editor',
//		'type'			=> 'editor',
//		'sanitize'		=> 'sanitize_text_field',
//	],
	[
		'label'			=> __( 'Textarea', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'textarea',
		'type'			=> 'textarea',
		'value'			=> \str_repeat( 'Lorem Ipsum ', 25 ),
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Select', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'select',
		'type'			=> 'select',
		'options'		=> [
			'key1'	=> 'value1',
			'key2'	=> 'value2',
			'key3'	=> 'value3',
		],
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Multiple_select', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'multiple_select',
		'type'			=> 'multiple_select',
		'options'		=> [
			'key1'	=> 'value1',
			'key2'	=> 'value2',
			'key3'	=> 'value3',
		],
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Taxonomy_select', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'taxonomy_select',
		'type'			=> 'taxonomy_select',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Taxonomy_multiple_select', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'taxonomy_multiple_select',
		'type'			=> 'taxonomy_multiple_select',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Media', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'media',
		'type'			=> 'media',
		'sanitize'		=> 'sanitize_text_field',
	],
	[
		'label'			=> __( 'Media_list', 'italystrap' ),
		'desc'			=> $desc,
		'id'			=> 'media_list',
		'type'			=> 'media_list',
		'sanitize'		=> 'sanitize_text_field',
	],
];