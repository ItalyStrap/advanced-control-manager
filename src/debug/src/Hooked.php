<?php
/**
 * Hooked API
 *
 * Get a list of hooked functions/method for a given hook
 *
 * @link www.itaòystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Debug;

/**
 * Hooked
 */
class Hooked {

	/**
	 * Get a list of hooked functions/methods
	 *
	 * @link http://www.wprecipes.com/list-all-hooked-wordpress-functions
	 *
	 * @param  bool   $tag  The name of the hook.
	 * @param  bool   $echo If true than print it.
	 *
	 * @return string       A list of hooked functions/methods.
	 */
	public function get_hooked_list( $tag = false, $echo = true ) {

		global $wp_filter;

		$output = '';

		if ( $tag ) {
			$hook[ $tag ] = $wp_filter[ $tag ]->callbacks;

			if ( ! is_array( $hook[ $tag ] ) ) {
				$hook[ $tag ] = array();
				trigger_error( "Nothing found for '$tag' hook", E_USER_WARNING );
				return;
			}
		} else {
			$hook = $wp_filter;
			ksort( $hook );
		}

		$output .= '<pre>';
		foreach ( $hook as $tag => $priorities ) {
			if ( ! is_array( $priorities ) ) {
				continue;
			}
			$output .= "\n&gt;&gt;&gt;&gt;&gt;\t<strong>$tag</strong>\n";
			ksort( $priorities );

			foreach ( $priorities as $priority => $functions ) {

				$output .= $priority;
				foreach ( $functions as $name => $properties ) {
					$output .= "\t$name";

					if ( is_array( $properties['function'] ) ) {

						if ( is_object( $properties['function'][0] ) ) {
							$output .= "\t\t" . get_class( $properties['function'][0] ) . " (Object)\n";
						}

					} elseif ( is_object( $properties['function'] ) && $properties['function'] instanceof \Closure ) {

						/**
						 * @link http://stackoverflow.com/questions/38241093/how-to-get-array-from-a-closure-object-in-php
						 */
						$reflection = new \ReflectionFunction( $properties['function'] );
						$reflection->getClosureScopeClass();

						$output .= "\t\t" . get_class( $reflection->getClosureThis() ) . " (Closure)\n";
					} else {
						$output .= "\t\t (Function)\n";
					}

					$output .= "\n";
				}
			}
		}
		$output .= '</pre>';

		if ( ! $echo ) {
			return $output;
		}

		echo $output;
	}
}
