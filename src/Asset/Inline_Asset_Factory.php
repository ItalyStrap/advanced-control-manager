<?php
/**
 * Inline_Asset_Factory API
 *
 * Print inline asset.
 *
 * @link www.italystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Asset;

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Inline_Asset_Factory
 */
class Inline_Asset_Factory implements Subscriber_Interface {

	/**
	 * Returns an array of hooks that this subscriber wants to register with
	 * the WordPress plugin API.
	 *
	 * @hooked 'wp_head' - 999999
	 * @hooked 'wp_print_footer_scripts' - 999999
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {

		return array(
			// 'hook_name'              => 'method_name',
			'wp_head'	=> array(
				'function_to_add'	=> 'inline_css',
				'priority'			=> 999999,
			),
			'wp_print_footer_scripts'	=> array(
				'function_to_add'	=> 'inline_javascript',
				'priority'			=> 999999,
			),
		);
	}

	/**
	 * Print inline script in footer after all and before shotdown hook.
	 *
	 * @todo Creare un sistema che appenda regolarmente dopo gli script
	 *       e tenga presente delle dipendenze da jquery
	 */
	public function inline_javascript() {

		$script = apply_filters( 'italystrap_custom_inline_script', Inline_Script::get() );

		if ( ! $script ) {
			return;
		}

		printf(
			'<script type="text/javascript">/*<![CDATA[*/%s/*]]>*/</script>',
			$script
		);

	}

	/**
	 * Print inline css.
	 */
	public function inline_css() {

		$css = apply_filters( 'italystrap_custom_inline_style', Inline_Style::get() );

		if ( empty( $css ) ) {
			return;
		}

		printf(
			'<style type="text/css" id="custom-inline-css">%s</style>',
			wp_strip_all_tags( $css )
		);
	}
}
