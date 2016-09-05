<?php
/**
 * Array definition for advanced text default options
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
	 * CSS ID for the widgets.
	 */
	'widget_css_id'				=> array(
				'name'		=> __( 'Widget CSS ID', 'italystrap' ),
				'desc'		=> __( 'Enter the CSS ID for this widget.', 'italystrap' ),
				'id'		=> 'widget_css_id',
				'type'		=> 'text',
				'class'		=> 'widefat widget_css_id',
				'default'	=> false,
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				),

	/**
	 * CSS class for the widgets.
	 */
	'widget_css_class'				=> array(
				'name'		=> __( 'Widget CSS class', 'italystrap' ),
				'desc'		=> __( 'Enter the CSS ID for this widget.', 'italystrap' ),
				'id'		=> 'widget_css_class',
				'type'		=> 'text',
				'class'		=> 'widefat widget_css_class',
				'default'	=> false,
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				),

);
