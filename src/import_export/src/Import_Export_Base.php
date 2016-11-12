<?php
/**
 * Provide Import and Export of the settings of the plugin.
 *
 * This is a fork from
 * https://github.com/Mte90/WordPress-Plugin-Boilerplate-Powered of Mte90
 *
 * @since 2.2.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Import_Export;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

use ItalyStrap\Fields\Fields_Interface;

/**
 * Class description
 */
abstract class Import_Export_Base {

	/**
	 * Definition of variables containing the configuration
	 * to be applied to the various function calls wordpress
	 *
	 * @var string
	 */
	protected $capability = '';

	/**
	 * The arguments for this class
	 *
	 * @var array
	 */
	protected $args = array();

	/**
	 * Array with all plugin settings
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 * Init Class
	 *
	 * @param array            $imp_exp_args [description]
	 * @param Fields_Interface $fields_type  [description]
	 */
	function __construct( array $imp_exp_args = array(), Fields_Interface $fields_type ) {

		$this->args = $imp_exp_args;

		$this->capability = $imp_exp_args['capability'];

		$this->fields_type = $fields_type;

	}

	/**
	 * Is allowed
	 *
	 * @param  string $name The the name of the action: import or export.
	 * @return bool         Return true if the button Export/Import is pressed.
	 */
	public function is_allowed( $name ) {

		if ( ! $name ) {
			return false;
		}

		if ( ! current_user_can( $this->capability ) ) {
			return false;
		}

		if ( empty( $_POST[ $this->args['name_action'] ] ) || "{$name}_settings" !== $_POST[ $this->args['name_action'] ] ) { // WPCS: input var okay.
			return false;
		}

		// if ( ! wp_verify_nonce( $_POST[ $this->args[ "{$name}_nonce" ] ], $this->args['name_action'] ) ) { // WPCS: input var okay.
		// 	return false;
		// }

		if ( ! check_admin_referer( $this->args['name_action'], $this->args[ "{$name}_nonce" ] ) ) { // WPCS: input var okay.
			return false;
		}

		return true;

	}

	/**
	 * Get view.
	 */
	public function get_view() {

		require( 'view/italystrap-import-export.php' );

	}

	/**
	 * Do fields
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function do_fields( $value = '' ) {

		$args['export_settings'] = array(
			'name'		=> __( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'italystrap' ),
			'desc'		=> '',
			'id'		=> $this->args['name_action'],
			'_id'		=> $this->args['name_action'],
			'_name'		=> $this->args['name_action'],
			'type'		=> 'hidden',
			// 'class'		=> 'widefat italystrap_action',
			'default'	=> $value,
			'value'		=> $value,
		 );

		$args['import_file'] = array(
			'name'		=> __( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'italystrap' ),
			'desc'		=> '',
			'id'		=> $this->args['import_file'],
			'_id'		=> $this->args['import_file'],
			'_name'		=> $this->args['import_file'],
			'type'		=> 'file',
			// 'class'		=> 'widefat italystrap_action',
			// 'default'	=> $value,
			// 'value'		=> $value,
		 );

		$args['import_settings'] = array(
			'name'		=> '',
			'desc'		=> '',
			'id'		=> $this->args['name_action'],
			'_id'		=> $this->args['name_action'],
			'_name'		=> $this->args['name_action'],
			'type'		=> 'hidden',
			// 'class'		=> 'widefat italystrap_action',
			'default'	=> $value,
			'value'		=> $value,
		 );

		$default = array(
			'value'		=> $value,
		);

		$output = '';

		$output .= $this->fields_type->get_field_type( $args[ $value ], $default ); // XSS ok.
	
		echo $output;
	
	}
}
