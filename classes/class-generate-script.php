<?php
/**
 * Abstract class for admin panel
 *
 * This class add some functions for use in admin panel
 *
 * @link http://codex.wordpress.org/Adding_Administration_Menus
 * @link http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-4-on-theme-options--wp-24902
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Generate script
 *
 * @todo A quick WordPress template tag to create Google Analytics Event Tracking on links. Built on behalf of CFO Publishing.
 * @link https://gist.github.com/AramZS/8930496
 */
class Generate_Script {

	/**
	 * [$var description]
	 *
	 * @var null
	 */
	private $var = null;

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( $argument = null ) {
		// Code...
	}

	/**
	 * Get a safe string for custom variables or event tracking for Google Analytics.
	 *
	 * @param  string $string The string to escape.
	 *
	 * @return string         The string escaped
	 */
	public function get_for_google_analytics( $string ) {
		$string = strip_tags( $string );
		$string = remove_accents( html_entity_decode( $string ) );
		$safe_string = esc_js( $string );
		return $safe_string;
	}

	/**
	 * Echo an event tracking code for Google Analytics.
	 */
	/**
	 * Echo an event tracking code for Google Analytics.
	 *
	 * @param  string $category       [description].
	 * @param  string $action         [description].
	 * @param  string $label          [description].
	 * @param  int    $value          [description].
	 * @param  bool   $noninteraction [description].
	 */
	public function the_event_tracking( $category, $action, $label, $value = 1, $noninteraction = false ) {
		if ( ! is_int( $value ) ) {
			$value = 1;
		}
		if ( ! is_bool( $noninteraction ) ) {
			$noninteraction = false;
		}
		$bool_string = ( $noninteraction ) ? 'true' : 'false';
		$s = sprintf('onClick="_gaq.push([%1$s, %2$s, %3$s, %4$s, %5$s, %6$s]);"',
			"'_trackEvent'",
			"'" . get_for_google_analytics( $category ) . "'",
			"'" . get_for_google_analytics( $action ) . "'",
			"'" . get_for_google_analytics( $label ) . "'",
			$value,
			$bool_string
		);

		echo $s; // XSS ok.
	}
}
