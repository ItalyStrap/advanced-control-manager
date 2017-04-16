<?php
/**
 * Class for admin panel
 *
 * This class add some functions for use in admin panel
 *
 * @link http://codex.wordpress.org/Adding_Administration_Menus
 * @link http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-4-on-theme-options--wp-24902
 *
 * @since 1.0.0
 *
 * @package ItalyStrap\Settings
 */

namespace ItalyStrap\Settings;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Class for admin area
 */
class Settings extends Settings_Base implements Subscriber_Interface {

	/**
	 * Returns an array of hooks that this subscriber wants to register with
	 * the WordPress plugin API.
	 *
	 * @hooked update_option - 10
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {

		return array(
			// 'hook_name'							=> 'method_name',
			'update_option'	=> array(
				'function_to_add'	=> 'save',
				'accepted_args'		=> 3,
			),
			'plugins_loaded'	=> 'init',
		);
	}

	/**
	 * Initialize admin area with those hooks
	 */
	public function init() {

		/**
		 * Add Admin menù page
		 */
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );

		/**
		 * Add Admin sub menù page
		 */
		add_action( 'admin_menu', array( $this, 'add_sub_menu_page' ) );

		add_action( 'admin_init', array( $this, 'settings_init' ) );

		/**
		 * Load script for ItalyStrap\Admin
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_style_script' ) );

		/**
		 * Add link in plugin activation panel
		 */
		add_filter( 'plugin_action_links_' . ITALYSTRAP_BASENAME, array( $this, 'plugin_action_links' ) );

		add_filter( 'plugin_row_meta' , array( $this, 'plugin_row_meta' ), 10, 4 );

		add_action( 'italystrap_after_settings_form', array( $this, 'get_aside' ) );

	}

	/**
	 * Get Aside for settings page
	 */
	public function get_aside() {
	
		require( $this->args['admin_view_path'] . 'italystrap-aside.php' );
	
	}

	/**
	 * Add style for ItalyStrap admin page
	 *
	 * @param  string $hook The admin page name (admin.php - tools.php ecc).
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 */
	public function enqueue_admin_style_script( $hook ) {

		if ( 'italystrap-settings' === $this->pagenow ) {

			wp_enqueue_script(
				$this->pagenow,
				plugins_url( 'js/' . $this->pagenow . '.min.js', __FILE__ ),
				array( 'jquery-ui-tabs', 'jquery-form' ),
				false,
				false,
				true
			);

			wp_enqueue_style(
				$this->pagenow,
				plugins_url( 'css/' . $this->pagenow . '.css', __FILE__ )
			);
		}
	}
}
