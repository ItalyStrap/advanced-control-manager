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

		if ( empty( $_POST[ $this->args['name_action'] ] ) || "{ $name }_settings" !== $_POST[ $this->args['name_action'] ] ) { // WPCS: input var okay.
			return false;
		}

		if ( ! wp_verify_nonce( $_POST[ $this->args[ "{$name}_nonce" ] ], $this->args[ "{ $name }_nonce" ] ) ) { // WPCS: input var okay.
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
}
