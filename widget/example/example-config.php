<?php
/**
 * Example config file for the widget
 *
 * This is only an example for the widget configuration.
 *
 * @link italystrap.com
 * @since 1.0.0
 *
 * @package Vendor
 */

return array(
	/**
	 * Insert posts ID separated by comma. Example: 1,2,3
	 */
	'post_id'				=> array(
		'name'		=> __( 'Post/Page Id', 'italystrap' ),
		'desc'		=> __( 'Insert posts/pages ID separated by comma. Example: 1,2,3', 'italystrap' ),
		'id'		=> 'post_id',
		'type'		=> 'text',
		'class'		=> 'widefat post_id',
		'default'	=> '',
		'validate'	=> 'alpha_dash',
		'sanitize'	=> 'sanitize_text_field',
		// 'section'	=> 'filter',
	),
);
