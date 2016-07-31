<?php
/**
 * Style Class API
 *
 * Handle the JS regiter and enque
 *
 * @since 2.0.0
 *
 * @package ItalyStrap\Core
 */

namespace ItalyStrap\Core;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

/**
 * Class description
 */
class Style extends Asset {

	// /**
	//  * [__construct description]
	//  *
	//  * @param array $config Configuration array.
	//  */
	// function __construct( array $config = array() ) {
	// 	// $this->config = apply_filters( 'italystrap_config_enqueue_style', $config );
	// 	$this->config = $config;
	// 	// var_dump( $this->config );
	// }

	/**
	 * Enqueue the script
	 *
	 * @since 1.0.0
	 *
	 * @return null
	 */
	protected function enqueue( array $config = array() ) {
		// echo "<pre>";
		// var_dump( ITALYSTRAP_PARENT_PATH );
		// var_dump( STYLESHEETPATH );
		// var_dump( $config );
		// echo "</pre>";
		wp_enqueue_style(
			$config['handle'],
			$config['file'],
			$config['deps'],
			$config['version'],
			$config['media']
		);
	}
}
