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
 * @package ItalyStrap
 */

namespace ItalyStrap\Admin;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Class for admin area
 */
class Admin extends A_Admin{

	/**
	 * Initialize Class
	 *
	 * @param array  $options     Get the plugin options.
	 * @param object $fields_type The Fields object.
	 */
	public function __construct( array $options = array(), array $settings, array $args, I_Fields $fields_type ) {

		parent::__construct( $options, $settings, $args, $fields_type );

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

	}

	/**
	 * Add style for ItalyStrap admin page
	 *
	 * @param  string $hook The admin page name (admin.php - tools.php ecc).
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 */
	public function enqueue_admin_style_script( $hook ) {

		if ( 'italystrap-options' === $this->pagenow ) {

			wp_enqueue_script( 'admin', plugins_url( 'js/src/admin.js', __FILE__ ), array( 'jquery-ui-tabs' ) );
			wp_enqueue_style( 'admin', plugins_url( 'css/admin.min.css', __FILE__ ) );
		}

		if ( 'italystrap-dashboard' === $this->pagenow || 'italystrap-documentation' === $this->pagenow ) {

			wp_enqueue_style( 'bootstrap', plugins_url( 'css/bootstrap.min.css', __FILE__ ) );
			wp_enqueue_style( 'style', plugins_url( 'css/style.css', __FILE__ ) );
			wp_register_style( 'openSans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300' );
			wp_enqueue_style( 'openSans' );
		}
	}

	/**
	 * Setting for section
	 *
	 * @param array $args The arguments for section callback.
	 */
	public function general_section( $args ) {

		$this->render_section_cb( $args );
	}

	/**
	 * Setting for section
	 *
	 * @param array $args The arguments for section callback.
	 */
	public function widget_section( $args ) {

		$this->render_section_cb( $args );
	}

	/**
	 * Setting for section
	 *
	 * @param array $args The arguments for section callback.
	 */
	public function shortcode_section( $args ) {

		$this->render_section_cb( $args );
	}

	/**
	 * Setting for section
	 */
	public function script_section() {

		esc_attr_e( 'Code entered here will be included in every page of the front-end of your site.', 'italystrap' );
	}

	/**
	 * Setting for section
	 */
	public function lazyload_section() {

		esc_attr_e( 'This section description for LazyLoad', 'italystrap' );
	}
}
