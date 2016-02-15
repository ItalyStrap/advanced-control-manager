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
// 	var_dump($GLOBALS);
// 	var_dump($GLOBALS['wp_filter']);
// 	var_dump($GLOBALS['wp_actions']);
// 	foreach( $GLOBALS['wp_actions'] as $action => $count )
// 		printf( '%s (%d) <br/>' . PHP_EOL, $action, $count );
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



function test_carousel_posts() {

	$atts = array();

	$atts['ids'] = '1045,2051,13,12,1177,16,1163';
	$atts['type'] = 'carousel';
	$atts['name'] = 'prova';
	$atts['size'] = 'large';

	// $atts['ids'] = array(
	// 	'id'	=> 'ids',
	// 	'default'	=> '1777,1016,1011',
	// 	);
	// $atts['type'] = array(
	// 	'id'		=> 'type',
	// 	'default'	=> 'carousel',
	// 	);

	// 'ids'				=> array(
	// 			'name'		=> __( 'Images ID', 'ItalyStrap' ),
	// 			'desc'		=> __( 'Enter the image ID.', 'ItalyStrap' ),
	// 			'id'		=> 'ids',
	// 			'type'		=> 'media_list',
	// 			'class'		=> 'widefat ids',
	// 			'default'	=> false,
	// 			// 'validate'	=> 'numeric_comma',
	// 			'filter'	=> 'sanitize_text_field',
	// 			 ),

	// /**
	//  * Type of gallery. If it's not "carousel", nothing will be done.
	//  */
	// 'type'				=> array(
	// 			'name'		=> __( 'Type of gallery', 'ItalyStrap' ),
	// 			'desc'		=> __( 'Enter the type of gallery, if it\'s not "carousel", nothing will be done.', 'ItalyStrap' ),
	// 			'id'		=> 'type',
	// 			'type'		=> 'select',
	// 			'class'		=> 'widefat',
	// 			'class-p'	=> 'hidden',
	// 			'default'	=> 'carousel',
	// 			'options'	=> array(
	// 						'standard'  => __( 'Standard Gallery', 'ItalyStrap' ),
	// 						'carousel'  => __( 'Carousel (Default)', 'ItalyStrap' ),
	// 			 			),
	// 			'validate'	=> 'alpha_numeric',
	// 			'filter'	=> 'sanitize_text_field',
	// 			 ),

	$carousel_posts = new \ItalyStrap\Core\Carousel_Bootstrap( $atts );
	// var_dump( $carousel_posts->validate_data() );
	var_dump( $carousel_posts->__get( 'output' ) );
	echo $carousel_posts->__get( 'output' );

}
// add_action( 'content_container_open', 'test_carousel_posts' );
// add_action( 'single', 'test_carousel_posts' );

// Add Shortcode
function query_posts_shortcode( $atts , $content = null ) {

	// Attributes
	// extract( shortcode_atts(
	// 	array(
	// 		'posts' => '5',
	// 	), $atts )
	// );

	// Code
// $output = '<ul>';
$query_posts = new Query_Posts( $atts );
$output = $query_posts->output();
// $output = '</ul>';
return $output;

}
// add_shortcode( 'query_posts', 'query_posts_shortcode' );

// var_dump($scanned_directory = array_diff( scandir( ITALYSTRAP_PLUGIN_PATH . 'options' ), array('..', '.') ));
