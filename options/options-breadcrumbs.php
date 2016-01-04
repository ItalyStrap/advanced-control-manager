<?php
/**
 * Array definition for carousel default options
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
	 * Text below the image.
	 */
	'text'				=> array(
				'name'		=> __( 'Text of the image', 'ItalyStrap' ),
				'desc'		=> __( 'Enter the text of the image.', 'ItalyStrap' ),
				'id'		=> 'text',
				'type'		=> 'textarea',
				'class'		=> 'widefat',
				'default'	=> false,
				// 'validate'	=> 'alpha_dash',
				'filter'	=> 'esc_textarea',
				 ),


);
