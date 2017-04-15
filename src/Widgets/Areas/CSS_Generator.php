<?php
/**
 * CSS Generator API: CSS Generator class
 *
 *
 * @package ItalyStrap\Widgets\Areas
 * @since 2.5.0
 */

namespace ItalyStrap\Widgets\Areas;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Widget Areas Class
 */
class CSS_Generator implements Subscriber_Interface {

	/**
	 * Returns an array of hooks that this subscriber wants to register with
	 * the WordPress plugin API.
	 *
	 * @hooked 'widgets_init' - 10
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {

		return array(
			// 'hook_name'				=> 'method_name',
			'widgets_init'			=> 'register_sidebars',
			'init'					=> array(
				'function_to_add'		=> 'register_post_type',
				'priority'				=> 20,
			),
		);
	}

	/**
	 * Is valid value
	 *
	 * @param  string|int|empty $value The value to check.
	 * @return bool                    Return true if is a valid value
	 */
	protected function is_valid_value( $value ) {

		if ( empty( $value ) ) {
			return false;
		}

		if ( '#' === $value ) {
			return false;
		}

		return true;
	}

	/**
	 * PArse CSS declarations
	 *
	 * @param  array $declarations The declarations of the selector.
	 * @return string              The parse declarations
	 */
	protected function parse_declaration( array $declarations = array() ) {

		$output = '';

		foreach ( $declarations as $property => $value ) {

			if ( ! $this->is_valid_value( $value ) ) {
				continue;
			}

			if ( is_numeric( $value ) ) {

				$value = sprintf(
					'url(%s)',
					wp_get_attachment_image_url( $value, '' ) // 4.4.0
				);
				$output .= 'background-size:cover;';
			}

			$output .= $property . ':' . $value . ';';
		}

		return $output;
	}

	/**
	 * Generate the rule set for the given seletor
	 *
	 * @param  string $selector     The name of the HTML selector.
	 * @param  array  $declarations The declarations for the given selector.
	 *
	 * @return string               Return the generated rule-set.
	 */
	protected function generate_rule( $selector, array $declarations = array() ) {

		if ( empty( $get_declarations = $this->parse_declaration( $declarations ) ) ) {
			return null;
		}

		return esc_attr(
			sprintf(
				'.%s{%s}',
				$selector,
				$get_declarations
			)
		);
	}

	/**
	 * Do style
	 *
	 * @param  array $style The given style for the widget area
	 */
	public function style( array $style = array() ) {

		if ( ! $rules = $this->generate_rule( $style['id'], $style['style'] ) ) {
			return;
		}

		printf(
			'<style scoped>%s</style>',
			$rules
		);	
	}
}
