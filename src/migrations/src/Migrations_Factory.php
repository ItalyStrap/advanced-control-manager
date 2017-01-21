<?php
/**
 * This class handle the migrations for the < 4.0.0 of the theme version.
 *
 * @link www.italystrap.it
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Migrations;

/**
 * Migrations_Factory
 */
class Migrations_Factory {

	/**
	 * [$var description]
	 *
	 * @var null
	 */
	private $rename = null;
	private $update = null;

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	public function __construct( Rename_Directory $rename, Update_File $update ) {
		$this->rename = $rename;
		$this->update = $update;
	}

	/**
	 * Execute the migrations
	 */
	public function run() {

		printf(
			'<h2>%s</h2><p>%s</p>',
			__( 'Migration page for ItalyStrap theme framework', 'italystrap' ),
			__( 'Before upgrading to new version of the ItalyStrap theme framework (4.x) you need to migrate the old settings and rename the old directory of the theme to the new format "italystrap", update the style.css in your child theme to point to the correct parent directory and update the option "template" to the new format name.<br>To do so you can use the below migrate button.<br>Make a backup of all files and database before migration and than click on "Migrate" button, this will run the migration functionality.', 'italystrap' )
		);

		if ( ! isset( $_POST['submit'] ) ) {
			return null;
		}

		$this->convert_data();
		$this->upgrade_parent();
	}

	/**
	 * Convert data.
	 */
	public function convert_data() {
	
		$settings_converter = new Settings_Converter();


		$old_data = (array) get_option( 'italystrap_theme_settings' );
		$pattern = array(
			// 'old'	=> 'new',
			'default_404'	=> 'default_404',
			'default_image'	=> 'default_image',
			'logo'			=> 'logo',
		);
		$settings_converter->data_to_theme_mod( $pattern, $old_data );


		$pattern = array(
			'favicon'		=> 'site_icon', // But this is an option
		);
		$settings_converter->data_to_option( $pattern, $old_data );


		$pattern = array(
			'analytics'		=> 'google_analytics_id', // Option to new options
		);
		$new_options = get_option( 'italystrap_settings' );

		$settings_converter->data_to_options( $pattern, $old_data, $new_options, 'italystrap_settings' );


		$old_data = (array) get_theme_mods();
		$old_data['display_navbar_logo_image'] = 'display_image';
		$pattern = array(
			// 'old'	=> 'new',
			'display_navbar_logo_image'	=> 'display_navbar_brand',
		);
		$settings_converter->data_to_theme_mod( $pattern, $old_data );
	}

	/**
	 * Upgrade for new parent version 4.0.0
	 */
	public function upgrade_parent() {

		$old_name = 'ItalyStrap';
		$new_name = 'italystrap';

		// $old_name = 'roots';
		// $new_name = 'ROOTS';

		// $old_name = 'ROOTS';
		// $new_name = 'roots';

		// $old_name = 'ROOTS';
		// $new_name = 'antani';

		// $old_name = 'antani';
		// $new_name = 'ROOTS';

		$template_path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR;
		$old_parent_dir = $template_path . $old_name;
		$new_parent_dir = $template_path . $new_name;

		/**
		 * Rename the old parent directory
		 */
		$this->rename->rename( $old_parent_dir, $new_parent_dir );

		/**
		 * Then update option 'template'
		 */
		if ( $old_name === get_option( 'template' ) ) {
			update_option( 'template', $new_name );
		}

		/**
		 * Then update option 'stylesheet' only if is installed only the parent.
		 */
		if ( $old_name === get_option( 'stylesheet' ) ) {
			update_option( 'stylesheet', $new_name );
		}

		/**
		 * Updates the stylesheet only in child theme.
		 */
		if (  $new_name !== get_option( 'stylesheet' )  ) {
			$child_path = $template_path . get_option( 'stylesheet' );

			/**
			 * Change the template string in child with the new parent dir name.
			 */
			$data = $this->update->get_content_file( $child_path . '/style.css' );
			$data = $this->update->replace_content_file( $old_name, $new_name, $data );
			$this->update->put_content_file( $child_path . '/style.css', $data );
		}

		printf(
			'<div class="%1$s"><p>Option "template": <strong>%2$s</strong></p><p>Options "stylesheet": <strong>%3$s</strong></p><p>The parent of <strong>%4$s</strong> now is <strong>%5$s</strong></p></div>',
			'notice notice-success',
			get_option( 'template' ),
			get_option( 'stylesheet' ),
			// wp_get_theme()->display( 'Theme Name' ),
			get_option( 'stylesheet' ),
			wp_get_theme()->display( 'Template' )
		);
	}
}
