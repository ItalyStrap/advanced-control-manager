<?php
/**
 * Provide Import and Export of the settings of the plugin.
 *
 * This is a fork from https://github.com/Mte90/WordPress-Plugin-Boilerplate-Powered of Mte90
 * @see  admin/includes/impexp.php file
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
 * Import Export Class
 */
class Import_Export {

	/**
	 * Definition of variables containing the configuration
	 * to be applied to the various function calls wordpress
	 *
	 * @var string
	 */
	private $capability = 'manage_options';

	/**
	 * The plugin options
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * The arguments for this class
	 *
	 * @var array
	 */
	private $args = array();

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( array $options_arr = array(), array $imp_exp_args = array() ) {

		$this->options = $options_arr;

		$this->args = $imp_exp_args;

	}

	/**
	 * Process a settings export from config
	 *
	 * @since    2.0.0
	 */
	public function export() {

		if ( empty( $_POST[ $this->args['name_action'] ] ) || 'export_settings' !== $_POST[ $this->args['name_action'] ] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST[ $this->args['export_nonce'] ], $this->args['export_nonce'] ) ) {
			return;
		}

		if ( ! current_user_can( $this->capability ) ) {
			return;
		}

		// $settings[0] = $this->options;

		ignore_user_abort( true );

		nocache_headers();

		// date_default_timezone_set('UTC');
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $this->args['filename'] . date( 'm-d-Y' ) . '.json' );
		header( 'Expires: 0' );

		if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
			echo json_encode( $this->options, JSON_PRETTY_PRINT );
		} else {
			echo json_encode( $this->options );
		}

		exit;
	}

	/**
	 * Process a settings import from a json file
	 *
	 * @since    2.0.0
	 */
	public function import() {

		if ( empty( $_POST[ $this->args['name_action'] ] ) || 'import_settings' != $_POST[ $this->args['name_action'] ] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST[ $this->args['import_nonce'] ],  $this->args['import_nonce']  ) ) {
			return;
		}

		if ( ! current_user_can( $this->capability ) ) {
			return;
		}

		/**
		 * Get the extension of import file
		 *
		 * @link http://stackoverflow.com/a/19831453 Strict standards: Only variables should be passed by reference
		 * @var string
		 */
		$file_name = $_FILES[ $this->args['import_file'] ]['name'];
		$exploded = explode( '.', $file_name );
		$extension = end( $exploded );

		/**
		 * If it is not json than die
		 */
		if ( $extension !== 'json' ) {
			wp_die( __( 'Please upload a valid .json file', 'italy-cookie-choices' ), __( 'No valid json file', 'italy-cookie-choices' ), array( 'back_link' => true ) );
		}

		/**
		 * If the file is empty than die
		 */
		if ( $_FILES['icc_import_file']['size'] === 0 ) {
			wp_die( __( 'The json file can\'t be empty', 'italy-cookie-choices' ), __( 'Empty file', 'italy-cookie-choices' ), array( 'back_link' => true ) );
		}

		$import_file = $_FILES[ $this->args['import_file'] ]['tmp_name'];

		/**
		 * If $import_file is empty or null than die
		 */
		if ( empty( $import_file ) ) {
			wp_die( __( 'Please upload a file to import', 'italy-cookie-choices' ), __( 'No file import', 'italy-cookie-choices' ), array( 'back_link' => true ) );
		}

		/**
		 * Retrieve the settings from the file and convert the json object to an array.
		 *
		 * @var array
		 */
		$settings = ( array ) json_decode( file_get_contents( $import_file ) );

		update_option( 'italy_cookie_choices', get_object_vars( $settings[0] ) );

		wp_safe_redirect( admin_url( 'options-general.php?page=' . 'italy-cookie-choices' ) );
		exit;
	}
}
