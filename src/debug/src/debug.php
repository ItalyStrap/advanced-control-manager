<?php

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
