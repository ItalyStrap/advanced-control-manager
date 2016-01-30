<?php
/**
 * This file is only for internal use
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

// add_action( 'wp_print_footer_scripts', 'display_priority', 999 );
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
	// 	printf( '%s (%d) <br/>' . PHP_EOL, $action, $count );
// });


/**
 * Sperimentale, aggingereattributo style con altezza minima per quando
 * si attiva il lazyloading sulle immagini
 */
// function kia_attachment_attributes( $attr ) {
/**
 * @todo $attr è un array con 3 valori
 *       aggiungere un attributo style e dare min-height
 *       con altezza presa dalla misura dell'immagine
 *       Per esempio se è la misura media deve prendere il valore di 300px
 *       Se questo sistema funziona ricordarsi di togliere lo stile dentro il carousel
 */
// 	// var_dump($attr);
// 	return $attr;
// }
// add_filter('wp_get_attachment_image_attributes', 'kia_attachment_attributes', 10, 1);

// wp-admin > pre
// 
function add_style_for_pre_tag_var_dump() {
	echo '<style>.wp-admin > pre{margin-left: 170px;}</style>';
}
add_action( 'admin_head', 'add_style_for_pre_tag_var_dump' );

