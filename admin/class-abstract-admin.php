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

namespace ItalyStrap\Admin;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

/**
 * Class for admin area
 */
abstract class A_Admin implements I_Admin{

	/**
	 * Definition of variables containing the configuration
	 * to be applied to the various function calls wordpress
	 *
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * Get the current admin page name
	 *
	 * @var string
	 */
	protected $pagenow;

	/**
	 * Settings for plugin admin page
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 * The plugin name
	 *
	 * @var string
	 */
	protected $plugin_slug;

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
	protected $fields_type;

	/**
	 * 	The array with all sub pages if exist
	 *
	 * @var array
	 */
	protected $submenus = array();

	/**
	 * The fields preregistered in the config file.
	 *
	 * @var array
	 */
	protected $fields = array();

	/**
	 * Initialize Class
	 *
	 * @param array  $options     Get the plugin options.
	 * @param object $fields_type The Fields object.
	 */
	/**
	 * Initialize Class
	 *
	 * @param array    $options        Get the plugin options.
	 * @param array    $settings       The configuration array plugin fields.
	 * @param array    $args           The configuration array for plugin.
	 * @param array    $get_theme_mods The theme options.
	 * @param I_Fields $fields_type    The Fields object.
	 */
	public function __construct( array $options = array(), array $settings, array $args, array $get_theme_mods, I_Fields $fields_type ) {

		if ( isset( $_GET['page'] ) ) { // Input var okay.
			$this->pagenow = wp_unslash( $_GET['page'] ); // Input var okay.
		}

		$this->settings = $settings;

		$this->options = $options;

		$this->args = $args;

		$this->fields_type = $fields_type;

		$this->fields = $this->get_settings_fields();

		$this->get_theme_mods = $get_theme_mods;

	}

	/**
	 * Enqueue Style and Script
	 *
	 * @param  string $hook The admin page name (admin.php - tools.php ecc).
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 */
	abstract public function enqueue_admin_style_script( $hook );

	/**
	 * Add plugin primary page in admin panel
	 */
	public function add_menu_page() {

		if ( ! $this->args['menu_page'] ) {
			return;
		}

		add_menu_page(
			$this->args['menu_page']['page_title'],
			$this->args['menu_page']['menu_title'],
			$this->capability, // $this->args['menu_page']['capability'],
			$this->args['menu_page']['menu_slug'],
			array( $this, 'get_admin_view' ),
			$this->args['menu_page']['icon_url'],
			$this->args['menu_page']['position']
		);
	}


	/**
	 * Add sub menù pages for plugin's admin page
	 */
	public function add_sub_menu_page() {

		if ( ! $this->args['submenu_pages'] ) {
			return;
		}

		foreach ( (array) $this->args['submenu_pages'] as $submenu ) {

			add_submenu_page(
				$submenu['parent_slug'],
				$submenu['page_title'],
				$submenu['menu_title'],
				$this->capability, // $submenu['capability'],
				$submenu['menu_slug'],
				// $submenu['function_cb']
				array( $this, 'get_admin_view' )
			);

		}

	}

	/**
	 * The add_submenu_page callback
	 */
	public function get_admin_view() {

		if ( ! current_user_can( $this->capability ) ) {
			wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.' ) );
		}

		/**
		 * Require settings-page.php
		 */
		require( $this->args['admin_view_path'] . $this->pagenow . '.php' );

	}

	/**
	 * Add link in plugin activation panel
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	 * @param  array $links Array of link in wordpress dashboard.
	 * @return array        Array with my links
	 */
	public function plugin_action_links( $links ) {

		if ( ! isset( $this->args['plugin_action_links'] ) ) {
			return $links;
		}

		if ( ! is_array( $this->args['plugin_action_links'] ) ) {
			return $links;
		}

		foreach ( $this->args['plugin_action_links'] as $link ) {
			array_unshift( $links, $link );
		}

		return $links;
	}

	/**
	 * Add information to the plugin description in plugin.php page
	 *
	 * @param array  $plugin_meta An array of the plugin's metadata,
	 *                            including the version, author,
	 *                            author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data.
	 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
	 *                            'Inactive', 'Recently Activated', 'Upgrade',
	 *                            'Must-Use', 'Drop-ins', 'Search'.
	 * @return array              Return the new array
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {

		if ( ! isset( $this->args['basename'] ) ) {
			return $plugin_meta;
		}

		if ( $this->args['basename'] !== $plugin_file ) {
			return $plugin_meta;
		}

		if ( ! isset( $this->args['plugin_row_meta'] ) ) {
			return $plugin_meta;
		}

		if ( ! is_array( $this->args['plugin_row_meta'] ) ) {
			return $plugin_meta;
		}

		$plugin_meta = array_merge( $plugin_meta, $this->args['plugin_row_meta'] );

		return $plugin_meta;
	
	}

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
	public function do_settings_sections( $page ) {

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
	 * Add option
	 */
	public function add_option() {

		if ( false === get_option( $this->args['options_name'] ) ) {
			$default = $this->get_plugin_settings_array_default();
			add_option( $this->args['options_name'], $default );
			$this->set_theme_mods( $default );
		}

	}

	/**
	 * Delete option
	 */
	public function delete_option() {
	
		delete_option( $this->args['options_name'] );
		$this->remove_theme_mods( $this->get_plugin_settings_array_default() );
	
	}

	/**
	 * Init settings for admin area
	 */
	public function settings_init() {

		// If the theme options doesn't exist, create them.
		$this->add_option();

		foreach ( $this->settings as $key => $setting ) {

			add_settings_section(
				$setting['id'],
				$setting['title'],
				array( $this, $setting['callback'] ),
				$setting['page']
			);

			foreach ( $setting['settings_fields'] as $key2 => $field ) {

				if ( isset( $field['show_on'] ) && false === $field['show_on'] ) {
					continue;
				}

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

		$this->register_setting();

	}

	/**
	 * Register settings.
	 * This allow you to override this method.
	 */
	public function register_setting() {
	
		register_setting(
			$this->args['options_group'],
			$this->args['options_name'],
			array( $this, 'update' )
		);
	
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function get_settings_fields() {

		foreach ( (array) $this->settings as $settings_value ) {
			foreach ( $settings_value['settings_fields'] as $fields_key => $fields_value ) {
				$fields[ $fields_value['id'] ] = $fields_value['args'];
			}
		}

		return $fields;
	
	}

	/**
	 * Sanitize the input data
	 *
	 * @param  array $input The input array.
	 * @return array        Return the array sanitized
	 */
	public function update( $input ) {

		$this->validation = new Validation;
		$this->sanitization = new Sanitization;

		foreach ( $this->fields as $field ) {

			if ( ! isset( $input[ $field['id'] ] ) ) {
				$input[ $field['id'] ] = '';
			}

			/**
			 * Validate fields if $field['validate'] is set
			 */
			if ( isset( $field['validate'] ) ) {

				if ( false === $this->validation->validate( $field['validate'], $input[ $field['id'] ] ) ) {

					$input[ $field['id'] ] = '';
				}
			}

			if ( isset( $field['sanitize'] ) ) {
				$input[ $field['id'] ] = $this->sanitization->sanitize( $field['sanitize'], $input[ $field['id'] ] );
			} else {
				$input[ $field['id'] ] = strip_tags( $input[ $field['id'] ] );
			}
		}

		return $input;

	}

	/**
	 * Render section CB
	 *
	 * @param  array $args The arguments for section CB.
	 */
	public function render_section_cb( $args ) {

		echo isset( $args['callback'][0]->settings[ $args['id'] ]['desc'] ) ? $args['callback'][0]->settings[ $args['id'] ]['desc'] : ''; // XSS ok.

	}

	/**
	 * Get the field type
	 *
	 * @param  array $args Array with arguments.
	 */
	public function get_field_type( $args ) {

		/**
		 * If field is requesting to be conditionally shown
		 */
		if ( ! $this->fields_type->should_show( $args ) ) {
			return;
		}

		/**
		 * Prefix method
		 *
		 * @var string
		 */
		$field_method = 'field_type_' . str_replace( '-', '_', $args['type'] );

		$args['value'] = ( isset( $this->options[ $args['id'] ] ) ) ? $this->options[ $args['id'] ] : '' ;

		/* Set field id and name  */
		$args['_id'] = $args['_name'] = $this->args['options_name'] . '[' . $args['id'] . ']';

		/**
		 * Run method
		 */
		if ( method_exists( $this->fields_type, $field_method ) ) {

			echo $this->fields_type->$field_method( $args ); // XSS ok.

		} else {

			echo $this->fields_type->field_type_text( $args ); // XSS ok.

		}
	}

	/**
	 * Get admin settings default value in an array
	 *
	 * @param  array $settings The array with settings
	 * @return array           The new array with default options
	 */
	public function get_plugin_settings_array_default() {

		$default_settings = array();

		foreach ( (array) $this->fields as $key => $setting ) {
			$default_settings[ $key ] = $setting['default'];
		}

		return $default_settings;

	}

	/**
	 * Set theme mods
	 *
	 * @param  array $value The options array with value.
	 */
	public function set_theme_mods( array $value = array() ) {
	
		foreach ( (array) $this->fields as $key => $field ) {
			if ( isset( $field['option_type'] ) && 'theme_mod' === $field['option_type'] ) {
				set_theme_mod( $key, $value[ $key ] );
			}
		}
	
	}

	/**
	 * Remove theme mods
	 *
	 * @param  array $value The options array with value.
	 */
	public function remove_theme_mods( array $value = array() ) {
	
		foreach ( (array) $this->fields as $key => $field ) {
			if ( isset( $field['option_type'] ) && 'theme_mod' === $field['option_type'] ) {
				remove_theme_mod( $key );
			}
		}
	
	}

	/**
	 * Save options in theme_mod
	 *
	 * @param  string $option    The name of the option.
	 * @param  array  $old_value The old options.
	 * @param  array  $value     The new options.
	 *
	 * @return string            The name of the option.
	 */
	public function save( $option, $old_value, $value ) {

		if ( ! isset( $this->args['options_name'] ) ) {
			return $option;
		}

		if ( $option !== $this->args['options_name'] ) {
			return $option;
		}

		/**
		 * Prendo il valore che verrà salvato $value
		 *
		 * Guardo in $this->settings quali sono gli argomenti che dovranno
		 * essere salvati in theme_mod (o option)
		 *
		 * Salvo eventuali argomenti come theme_mod
		 *
		 * ? unset eventuali valori savati in theme_mod da
		 * quelli che verranno salvati in option per evitare duplicazione?
		 *
		 * 
		 */
		echo "<pre>";
		print_r($value);
		echo "</pre>";
		// echo "<pre>";
		// print_r($this->fields);
		// echo "</pre>";
		$this->set_theme_mods( $value );
		// foreach ( (array) $this->fields as $key => $field ) {
		// 	if ( 'theme_mod' === $field['option_type'] ) {
		// 		set_theme_mod( $key, $value[ $key ] );
		// 	}
		// }
		// echo "<pre>";
		// print_r($value);
		// echo "</pre>";
		// echo "<pre>";
		// print_r($this->settings);
		// echo "</pre>";
		echo "<pre>";
		print_r(get_theme_mods());
		print_r($this->get_theme_mods);
		echo "</pre>";

		wp_die();

		return $option;
	
	}
}
