<?php
/**
 * This file is only for debug purpose
 */

// function prova(){
// 	if ( defined('ITALYSTRAP_THEME') ){
// 		do somethings
// 	}
// }
// add_action( 'after_setup_theme', 'prova' );


// http://codex.wordpress.org/Function_Reference/wp_get_theme
// $my_theme = wp_get_theme( 'ItalyStrap' );
// if ( $my_theme->exists() ) add some code
// 
// 

// add_action( 'wp_footer', 'display_priority', 999 );
// function display_priority(){
// 	var_dump(ItalyStrapGlobals::get());
// }

/**
 * @link http://wordpress.stackexchange.com/questions/162862/wordpress-hooks-run-sequence
 * @link http://codex.wordpress.org/Plugin_API/Action_Reference
 * wp_footer (1)
 * wp_print_footer_scripts (1)
 * shutdown (1) 
 */
// add_action( 'shutdown', function(){
	// var_dump($GLOBALS['wp_actions']);
    // foreach( $GLOBALS['wp_actions'] as $action => $count )
    //     printf( '%s (%d) <br/>' . PHP_EOL, $action, $count );
// });