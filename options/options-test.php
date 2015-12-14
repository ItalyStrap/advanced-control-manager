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
	 * Ids for the images to use.
	 */
	'ids'				=> array(
				'name'		=> __( 'Images ID', 'ItalyStrap' ),
				'desc'		=> __( 'Enter the image ID.', 'ItalyStrap' ),
				'id'		=> 'ids',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'default'	=> false,
				'validate'	=> 'alpha_dash',
				'filter'	=> 'strip_tags|esc_attr',
				 ),

	/**
	 * Type of gallery. If it's not "carousel", nothing will be done.
	 */
	'type'				=> array(
				'name'		=> __( 'Type of gallery', 'ItalyStrap' ),
				'desc'		=> __( 'Enter the type of gallery, if it\'s not "carousel", nothing will be done.', 'ItalyStrap' ),
				'id'		=> 'type',
				'type'		=> 'select',
				'class'		=> 'widefat',
				'fields' => array(
								array(
									'name'  => __( 'Standard Gallery', 'mv-my-recente-posts' ),
									'value' => '',
								 ),
								array(
									'name'  => __( 'Carousel', 'mv-my-recente-posts' ),
									'value' => 'carousel',
								 ),
				 			),
				'validate'	=> 'alpha_dash',
				'filter'	=> 'strip_tags|esc_attr',
				 ),

);
