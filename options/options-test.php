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
				'name' => __( 'Output as list1', 'mv-my-recente-posts' ), 							
				'desc' => __( 'Wraps posts with the <li> tag.', 'mv-my-recente-posts' ), 
				'id' => 'list1',
				'class' => 'widefat',
				'type'=>'text', 				
				// checked by default: 
				'default_value' => false, // 0 or 1
				'filter' => 'strip_tags|esc_attr', 
				 ), 

	/**
	 * Type of gallery. If it's not "carousel", nothing will be done.
	 */
	'type'				=> array( 
				'name' => __( 'Output as list2', 'mv-my-recente-posts' ), 							
				'desc' => __( 'Wraps posts with the <li> tag.', 'mv-my-recente-posts' ), 
				'id' => 'list2',
				'class' => 'widefat',
				'type'=>'text',			
				// checked by default: 
				'default_value' => 'Ciao ', // 0 or 1
				'filter' => 'strip_tags|esc_attr', 
				 ), 

	/**
	 * Alternative appearing order of images.
	 */
	'orderby'			=> array( 
				'name' => __( 'Output as list3', 'mv-my-recente-posts' ), 							
				'desc' => __( 'Wraps posts with the <li> tag.', 'mv-my-recente-posts' ), 
				'id' => 'list3',
				'class' => 'widefat',
				'type'=>'text', 				
				// checked by default: 
				'default_value' => 'Bello',
				'filter' => 'strip_tags|esc_attr', 
				 ), 

	array( 
				'name' => __( 'Output as list4', 'mv-my-recente-posts' ), 							
				'desc' => __( 'Wraps posts with the <li> tag.', 'mv-my-recente-posts' ), 
				'id' => 'list4',
				'class' => 'widefat',
				'type'=>'checkbox', 				
				// checked by default: 
				'default_value' => 1, // 0 or 1
				'filter' => 'strip_tags|esc_attr', 
				 ), 

);