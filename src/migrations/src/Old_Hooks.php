<?php
/**
 * Old Hooks API
 *
 * This class converts the old hooks used with the new hooks 
 *
 * @link [URL]
 * @since [x.x.x (if available)]
 *
 * @package [Plugin/Theme/Etc]
 */

namespace ItalyStrap\Migrations;

/**
 * Old_Hooks
 */
class Old_Hooks {

	/**
	 * Hooks from version <4.0.0
	 *
	 * @var array
	 */
	private $hooks = array(
		'body_open'					=> '',
		'wrapper_open'				=> '',
		'header_open'				=> '',
		'header_closed'				=> '',
		'nav_open'					=> '',
		'before_wp_nav_menu'		=> '',
		'after_wp_nav_menu'			=> '',
		'nav_closed'				=> '',
		'content_open'				=> '',
		'content_container_open'	=> '',
		'content_col_open'			=> 'italystrap_before_loop',
		'content_col_closed'		=> 'italystrap_after_loop',
		'sidebar_col_open'			=> 'italystrap_before_sidebar_widget_area',
		'sidebar_col_closed'		=> 'italystrap_after_sidebar_widget_area',
		'content_container_closed'	=> '',
		'footer_open'				=> 'italystrap_before_footer',
		'footer_closed'				=> 'italystrap_after_footer',
		'wrapper_closed'			=> '',
		'body_closed'				=> '',
	);

	/**
	 * Conver old hooks
	 */
	public function convert() {

		if ( ! function_exists( 'do_action_deprecated' ) ) {
			return;
		}
	
		foreach ( $this->hooks as $old => $new ) {
			if ( empty( $new ) ) {
				continue;
			}
			add_action( $new, function () use ( $new, $old ) {
				_deprecated_hook(
					$old,
					'4.0.0',
					$new,
					sprintf(
						__( '%s is deprecated, use %s instead.', 'italystrap' ),
						$old,
						$new
					)
				);
				do_action( $old );
			} );
		}
	
	}
}
