<?php
/**
 * Asset_Queued API
 *
 * Debug all Asset_Queued
 *
 * @link www.italystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

/**
 * Example:
 */
// add_action( 'wp_footer', function () {

// 	$debug_asset = new \ItalyStrap\Debug\Asset_Queued();
// 	$debug_asset->styles();
// 	$debug_asset->scripts();
// }, 100000 );

namespace ItalyStrap\Debug;

/**
 * Asset_Queued
 */
class Asset_Queued {

	// Funzione per vedere dipendenze e script caricati nel WP-HEAD http://www.targetweb.it/eliminare-script-caricati-nel-wp-head-di-wordpress/
		// add_action('wp_head', 'scripts_queued');
		// add_action('wp_head', 'styles_queued');
		// add_action('init', 'styles_queued');
	public function scripts() {
		global $wp_scripts;
		// var_dump( $wp_scripts->in_footer );
		// // echo "<style>pre{display:none;}</style>";

		echo $this->make_output( $wp_scripts, 'Scripts'  );
	}

	public function styles() {
		global $wp_styles;
		// wp_styles();
		// var_dump($wp_styles->in_footer);
		// echo "<pre>";
		// print_r( $wp_styles->registered );
		// print_r( $wp_styles->queue );
		// print_r( $wp_styles->done );
		// print_r( $wp_styles->groups );
		// echo "</pre>";
		// echo "<style>pre{display:none;}</style>";

		echo $this->make_output( $wp_styles, 'Styles' );
	}

	/**
	 * Make the list assets output.
	 *
	 * @param  WP_Style|WP_Script $assets WP_Style or WP_Script object.
	 * @return string                     Return the list of asset enqueued.
	 */
	private function make_output( $assets, $type ) {

		$output = '';
	
		$output .= '<pre>' . $type . ' trovati in coda'."\r\n";
		foreach ( $assets->queue as $asset ) {

			if ( ! isset( $assets->registered[ $asset ] ) ) {
				continue;
			}

			$output .= "\r\nHandle: <strong>" . $asset . "</strong>\n";
			$output .= "<i class='small'>URL: " . $assets->registered[ $asset ]->src . "</i class='small'>\r\n";
			$deps = $assets->registered[ $asset ]->deps;
			if ( $deps ) {
				$output .= 'Dipende da >>>>>>> ';
				// $output .= print_r( $deps, true );
				foreach ( $deps as $dep ) {
					$output .= '<strong>' . $dep . '</strong>, ';
				}
				$output .= "\r\n";
			} else {
				$output .= "Non dipende da nessuno\r\n";
			}
		}
		$output .= "\r\n</pre>";

		return $output;
	}
}
