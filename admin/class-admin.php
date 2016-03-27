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
class Admin implements I_Admin{

	/**
	 * Definition of variables containing the configuration
	 * to be applied to the various function calls wordpress
	 *
	 * @var string
	 */
	protected $capability      = 'manage_options';

	/**
	 * Get the current admin page
	 * This is only for loading ItalyStrap custom script in his settings pages
	 *
	 * @var string
	 */
	private $page;

	/**
	 * Settings for plugin admin page
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * The plugin name
	 *
	 * @var string
	 */
	private $plugin_slug;

	/**
	 * The plugin options
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * The type of fields to create
	 *
	 * @var object
	 */
	private $fields_type;

	/**
	 * Initialize Class
	 *
	 * @param array  $options     Get the plugin options.
	 * @param object $fields_type The Fields object.
	 */
	public function __construct( array $options = array(), I_Fields $fields_type ) {

		if ( isset( $_GET['page'] ) ) { // Input var okay.
			$this->page = esc_attr( wp_unslash( $_GET['page'] ) ); // Input var okay.
		}

		$this->settings = (array) require( '/settings/settings-admin-page.php' );

		$this->options = $options;

		$this->fields_type = $fields_type;

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

		if ( 'italystrap-options' === $this->page ) {

			wp_enqueue_script( 'admin', plugins_url( 'js/src/admin.js', __FILE__ ), array( 'jquery-ui-tabs' ) );
			wp_enqueue_style( 'admin', plugins_url( 'css/admin.min.css', __FILE__ ) );
		}

		if ( 'italystrap-dashboard' === $this->page || 'italystrap-documentation' === $this->page ) {

			wp_enqueue_style( 'bootstrap', plugins_url( 'css/bootstrap.min.css', __FILE__ ) );
			wp_enqueue_style( 'style', plugins_url( 'css/style.css', __FILE__ ) );
			wp_register_style( 'openSans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300' );
			wp_enqueue_style( 'openSans' );
		}
	}

	/**
	 * Add page for ItalyStrap admin page
	 */
	public function add_menu_page() {

		add_menu_page(
			__( 'ItalyStrap Dashboard', 'italystrap' ),
			'italystrap',
			$this->capability,
			'italystrap-dashboard',
			array( $this, 'dashboard' ),
			'dashicons-performance',
			null,
			null
		);
	}

	/**
	 *	The dashboard callback
	 */
	public function dashboard() {

		if ( ! current_user_can( $this->capability ) ) {
			wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.' ) ); }

		/**
		 * Require dashboard-page.php
		 */
		require_once( ITALYSTRAP_PLUGIN_PATH . 'admin/admin-template/dashboard-page.php' );

	}


	/**
	 * Add sub menù page for ItalyStrap admin page
	 */
	public function add_sub_menu_page() {

		add_submenu_page(
			'italystrap-dashboard',
			__( 'Documentation', 'italystrap' ),
			__( 'Documentation', 'italystrap' ),
			$this->capability,
			'italystrap-documentation',
			array( $this, 'documentation' )
		);

		add_submenu_page(
			'italystrap-dashboard',
			__( 'Options', 'italystrap' ),
			__( 'Options', 'italystrap' ),
			$this->capability,
			'italystrap-options',
			array( $this, 'options' )
		);

	}


	/**
	 * The documentation call back
	 */
	public function documentation() {

		if ( ! current_user_can( $this->capability ) ) {
			wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.' ) ); }

		/**
		 * Require documentation-page.php
		 */
		require_once( ITALYSTRAP_PLUGIN_PATH . 'admin/admin-template/documentation-page.php' );

	}//end documentation()

	/**
	 * The options call back
	 */
	public function options() {

		if ( ! current_user_can( $this->capability ) ) {
			wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.' ) ); }

		/**
		 * Require options-page.php
		 */
		require_once( ITALYSTRAP_PLUGIN_PATH . 'admin/admin-template/options-page.php' );

	}//end options()

	/**
	 * Add link in plugin activation panel
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	 * @param  array $links Array of link in wordpress dashboard.
	 * @return array        Array with my links
	 */
	public function plugin_action_links( $links ) {

			array_unshift( $links, '<a href="admin.php?page=italystrap-documentation">' . __( 'Documentation','italystrap' ) . '</a>' );

			array_unshift( $links, '<a href="http://www.italystrap.it" target="_blank">ItalyStrap</a>' );

		return $links;
	}//end plugin_action_links()

	/**
	 * Prints out all settings sections added to a particular settings page
	 *
	 * Part of the Settings API. Use this in a settings page callback function
	 * to output all the sections and fields that were added to that $page with
	 * add_settings_section() and add_settings_field()
	 *
	 * @global $wp_settings_sections Storage array of all settings sections added to admin pages
	 * @global $wp_settings_fields Storage array of settings fields and info about their pages/sections
	 * @since 2.7.0
	 *
	 * @param string $page The slug name of the page whose settings sections you want to output.
	 */
	private function do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[ $page ] ) ) {
			return; }

		$count = 1;

		foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
			echo '<div id="tabs-' . $count . '" class="wrap">'; // XSS ok.
			if ( $section['title'] ) {
				echo "<h2>{$section['title']}</h2>\n"; // XSS ok.
			}

			if ( $section['callback'] ) {
				call_user_func( $section['callback'], $section ); }

			if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
				continue; }
			echo '<table class="form-table">';
			do_settings_fields( $page, $section['id'] );
			echo '</table>';
			echo '</div>';
			$count++;
		}
	}

	/**
	 * Create the nav tabs for section in admin plugin area
	 */
	public function create_nav_tab() {

		$count = 1;

		$out = '<ul>';

		foreach ( $this->settings as $key => $value ) {
			$out .= '<li><a href="#tabs-' . $count . '">' . $value['tab_title'] . '</a></li>';
			$count++;
		}

		$out .= '</ul>';
		echo $out; // XSS ok.
	}

	/**
	 * Init settings for admin area
	 */
	public function settings_init() {

		// If the theme options don't exist, create them.
		if ( false === get_option( 'italystrap_settings' ) ) {
			add_option( 'italystrap_settings' );
		}

		foreach ( $this->settings as $key => $setting ) {
			add_settings_section(
				$setting['id'],
				$setting['title'],
				array( $this, $setting['callback'] ),
				$setting['page']
			);
			foreach ( $setting['settings_fields'] as $key2 => $field ) {
				add_settings_field(
					$field['id'],
					$field['title'],
					array( $this, $field['callback'] ),
					$field['page'],
					$field['section'],
					$field['args']
				);
			}
		}

		register_setting(
			'italystrap_options_group',
			'italystrap_settings',
			array( $this, 'sanitization' )
		);

	}

	/**
	 * Sanitize the input data
	 *
	 * @param  array $input The input array.
	 * @return array        Return the array sanitized
	 */
	public function sanitization( $input ) {

		$new_input = (array) array_map( 'sanitize_text_field', $input );

		return $new_input;

	}

	/**
	 * Setting for section
	 *
	 * @param array $args The arguments for section callback.
	 */
	public function widget_section( $args ) {

		esc_attr_e( 'This section description for widget', 'italystrap' );
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

	/**
	 * Option for Image Lazy Load
	 *
	 * @param  array $args Arguments for this options.
	 */
	public function option_lazyload( $args ) {

	?>

		<input type='checkbox' name='italystrap_settings[lazyload]' <?php checked( isset( $this->options['lazyload'] ), 1 ); ?> value='1'>
		<label for="italystrap_settings[lazyload]"><?php esc_attr_e( 'Activate LazyLoad for images', 'italystrap' ); ?></label>

	<?php

	}

	/**
	 * Option for vCard Widget
	 *
	 * @param  array $args Arguments for this options.
	 */
	public function option_vcardwidget( $args ) {

	?>

		<input type='checkbox' name='italystrap_settings[vcardwidget]' <?php checked( isset( $this->options['vcardwidget'] ), 1 ); ?> value='1'>
		<label for="italystrap_settings[vcardwidget]"><?php esc_attr_e( 'Activate Vcard Widget for your local business (Experimental)', 'italystrap' ); ?></label>

	<?php

	}

	/**
	 * Option for Post Widget
	 *
	 * @param  array $args Arguments for this options.
	 */
	public function option_post_widget( $args ) {

	?>

		<input type='checkbox' name='italystrap_settings[post_widget]' <?php checked( isset( $this->options['post_widget'] ), 1 ); ?> value='1'>
		<label for="italystrap_settings[post_widget]"><?php esc_attr_e( 'Activate Post Widget for Custom posts loop', 'italystrap' ); ?></label>

	<?php

	}

	/**
	 * Option for Media Widget
	 *
	 * @param  array $args Arguments for this options.
	 */
	public function option_media_widget( $args ) {

	?>

		<input type='checkbox' name='italystrap_settings[media_widget]' <?php checked( isset( $this->options['media_widget'] ), 1 ); ?> value='1'>
		<label for="italystrap_settings[media_widget]"><?php esc_attr_e( 'Activate Bootstrap Carousel Media Widget', 'italystrap' ); ?></label>

	<?php

	}

	/**
	 * Textarea for custom CSS
	 *
	 * @param array $args Array with input arguments.
	 */
	public function option_custom_css( $args ) {

		$args['value'] = ( isset( $this->options['custom_css'] ) ) ? $this->options['custom_css'] : '' ;

		echo $this->fields_type->field_type_textarea( $args, $out = '' ); // XSS ok.

	}

	/**
	 * Textarea for custom JavaScript
	 *
	 * @param array $args Array with input arguments.
	 */
	public function option_custom_js( $args ) {

		$args['value'] = ( isset( $this->options['custom_js'] ) ) ? $this->options['custom_js'] : '' ;

		echo $this->fields_type->field_type_textarea( $args, $out = '' ); // XSS ok.

	}
}
