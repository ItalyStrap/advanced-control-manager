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

		if ( ! isset( $_POST['submit'] ) ) {
			return null;
		}

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

		$child_path = $template_path . get_option( 'stylesheet' );

		/**
		 * Change the template string in child with the new parent dir name.
		 */
		$data = $this->update->get_content_file( $child_path . '/style.css' );
		$data = $this->update->replace_content_file( $old_name, $new_name, $data );
		$this->update->put_content_file( $child_path . '/style.css', $data );

		/**
		 * Then update option 'template'
		 */
		if ( $new_name !== get_option( 'template' ) ) {
			update_option( 'template', $new_name );
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
