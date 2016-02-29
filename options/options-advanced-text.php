<?php
/**
 * Array definition for advanced text default options
 *
 * @package ItalyStrap
 */

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) die();

/**
 * Definition array() with all the options connected to the
 * module which must be called by an include (setoptions).
 */
return array(

	/**
	 * Ids for the images to use.
	 */
	'ids'				=> array(
				'name'		=> __( 'Images ID', 'ItalyStrap' ),
				'desc'		=> __( 'Enter the media or post type ID.', 'ItalyStrap' ),
				'id'		=> 'ids',
				'type'		=> 'media_list',
				'class'		=> 'widefat ids',
				'default'	=> false,
				// 'validate'	=> 'numeric_comma',
				'sanitize'	=> 'sanitize_text_field',
				'section'	=> 'general',
				 ),

);
