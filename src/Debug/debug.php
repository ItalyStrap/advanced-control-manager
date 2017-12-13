<?php
/**
 * File for debuggind purpose.
 *
 * It is loaded only if WP_DEBUG is defined && true
 *
 * @link https://ttalystrap.com
 *
 * @package ItalyStrap
 */

/**
 * Print style for kint and pre wp-admin > pre
 */
function add_style_for_pre_tag_var_dump_and_kint_debugger() {
	echo '<style>.wp-admin > pre, .kint{margin-left: 170px;}</style>';
}
add_action( 'admin_head', 'add_style_for_pre_tag_var_dump_and_kint_debugger' );

/**
 * Rimuovo lo stile alla admin bar creato da UpDevTools
 */
remove_filter( 'get_user_option_admin_color', 'KnowTheCode\UpDevTools\Admin\set_local_development_admin_color_scheme', 5 );
remove_action( 'admin_head', __NAMESPACE__ . 'KnowTheCode\UpDevTools\Admin\render_admin_bar_css', 9999 );
remove_action( 'wp_head', __NAMESPACE__ . 'KnowTheCode\UpDevTools\Admin\render_admin_bar_css', 9999 );

/**
 * Per sicurezza ricarico lo stile predefinito
 */
add_filter( 'get_user_option_admin_color', function ( $color ) {
	return 'fresh';
}, 5 );

/**
 * Print debug output on debug.log file
 *
 * @param mixed $log The input value.
 */
function debug( $log ) {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return;
	}

	error_log( print_r( $log, true ) );

	// if ( is_array( $log ) || is_object( $log ) ) {
	// 	error_log( print_r( $log, true ) );
	// } else {
	// 	error_log( $log );
	// }
}

/**
 * Log
 *
 * @param mixed $log The input value.
 */
function the_log( $log ) {
	debug( $log );
}

if ( ! function_exists( 'd' ) ) {
	function d( $value = '' ) {
		add_action( 'plugins_loaded', function () use ( $value ) {
			if ( ! function_exists( 'd' ) ) {

				echo "<pre>";
				print_r( $value );
				echo "</pre>";

				debug( $value );

				return;
			}
			\d( $value );
		});
	
	}
}

if ( ! function_exists( 'ddd' ) ) {
	function ddd( $value = '' ) {
		add_action( 'plugins_loaded', function () use ( $value ) {
			if ( ! function_exists( 'd' ) ) {

				echo "<pre>";
				print_r( $value );
				echo "</pre>";

				debug( $value );

				die();
			}
			\ddd( $value );
		});
	
	}
}


// var_dump(get_option( 'stylesheet' ));
// var_dump(get_option( "theme_mods_ItalyStrap" ));
// var_dump(get_theme_mods());
// add_action('wp','My_Test');
function My_Test() {

	var_dump( microtime( true ) );

	for ( $i = 1; $i < 100; $i++ ) {
		get_option( 'blogdescription' );
	}

	var_dump( microtime( true ) );

	for ( $i = 1; $i < 100;$i++ ) {
		get_theme_mod( 'blogdescription' );
	}

	var_dump( microtime( true ) );
	exit;
}

/**
 * Test column in 'the_content'
 * https://digwp.com/2010/03/wordpress-post-content-multiple-columns/
 *
 * Vedere la funzione get_the_content per prendere spunto sul rendering delle colonne.
 * @see https://developer.wordpress.org/reference/functions/get_the_content/
 *
 * @param  string $value [description]
 * @return string        [description]
 */
function render_column( $content ) {
	// if ( is_single() && in_the_loop() && is_main_query() )

	$search = array(
		'<!--start-column-->',
		'<!--end-column-->',
	);

	$replace = array(
		'<div class="row">',
		'</div>',
	);
	
	$content = str_replace( $search, $replace, $content );

	$columns = explode( '<!--column-->', $content );
	$count = count( $columns );
// d( $columns );
// d( $content );
	$output = '';
	foreach ( $columns as $key => $column ) {
		$output .= sprintf(
			'<div class="col-md-%s">%s</div>',
			floor( 12 / $count ),
			wpautop( $column )
		);
	}
	// d( $output );
	return $output;
	// return $content;

}
// add_action( 'the_content', __NAMESPACE__ . '\render_column', 10, 1 );
// remove_filter( 'the_content', 'wpautop', 10 );


// http://php.net/manual/en/function.get-defined-vars.php
// get_defined_vars();
// 
// http://php.net/manual/en/function.get-defined-functions.php
// get_defined_functions();
// 
// http://php.net/manual/en/function.get-defined-constants.php
// get_defined_constants();
// 

// http://codex.wordpress.org/Function_Reference/wp_get_theme
// $my_theme = wp_get_theme( 'italystrap' );
// if ( $my_theme->exists() ) add some code
// 
// 

// add_action( 'wp_print_footer_scripts', 'display_priority', 999 );
// function display_priority(){
// 	var_dump(Inline_Script::get());
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
	// 			'name'		=> __( 'Images ID', 'italystrap' ),
	// 			'desc'		=> __( 'Enter the image ID.', 'italystrap' ),
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
	// 			'name'		=> __( 'Type of gallery', 'italystrap' ),
	// 			'desc'		=> __( 'Enter the type of gallery, if it\'s not "carousel", nothing will be done.', 'italystrap' ),
	// 			'id'		=> 'type',
	// 			'type'		=> 'select',
	// 			'class'		=> 'widefat',
	// 			'class-p'	=> 'hidden',
	// 			'default'	=> 'carousel',
	// 			'options'	=> array(
	// 						'standard'  => __( 'Standard Gallery', 'italystrap' ),
	// 						'carousel'  => __( 'Carousel (Default)', 'italystrap' ),
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

// if ( ! function_exists( 'add_action' ) ) {
// 	function add_action() {

// 		return null;
// 	}
// }


// add_action( 'wp_enqueue_scripts', function () {
// 	global $wp_filter;
// 	d( $wp_filter['wp_head'] );
// 	remove_action( 'wp_head', 'wp_print_styles', 8 );
// 	remove_action( 'wp_head', 'wp_print_scripts' );
// 	remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
// 	remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
// 	d( $wp_filter['wp_head'] );
// });

// $event_manager->remove_subscriber( $italystrap_title );
// $injector->execute(function( $args ) use ( $injector ) { d( $injector ); } );
// add_action( 'wp_footer', function () {
// 	$debug_asset = new \ItalyStrap\Debug\Asset_Queued();
// 	$debug_asset->styles();
// 	$debug_asset->scripts();
// }, 100000 );
