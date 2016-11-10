<?php
/**
 * Update API Class
 *
 * This class manage the updating of the Settings and the Widget API
 *
 * @link [URL]
 * @since 2.1.2
 *
 * @package ItalyStrap\Update
 */

namespace ItalyStrap\Update;

use ItalyStrap\Admin\Validation;
use ItalyStrap\Admin\Sanitization;

/**
 * Class description
 */
class Update implements Update_Interface{

	/**
	 * Validation object
	 *
	 * @var Validation
	 */
	private $validation = null;

	/**
	 * Sanitization object
	 *
	 * @var Sanitization
	 */
	private $sanitization;

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( Validation $validation, Sanitization $sanitization ) {
		$this->validation = $validation;
		$this->sanitization = $sanitization;
	}

	/**
	 * Update method
	 *
	 * @param  array $instance The array with value to save.
	 * @return array           The array validated and sanitized.
	 */
	public function update( array $instance = array(), array $fields = array() ) {

		foreach ( $fields as $field ) {

			if ( ! isset( $instance[ $field['id'] ] ) ) {
				$instance[ $field['id'] ] = '';
			}

			/**
			 * Validate fields if $field['validate'] is set
			 */
			if ( isset( $field['validate'] ) ) {

				if ( false === $this->validation->validate( $field['validate'], $instance[ $field['id'] ] ) ) {

					$instance[ $field['id'] ] = '';
				}
			}

			if ( isset( $field['sanitize'] ) ) {
				$instance[ $field['id'] ] = $this->sanitization->sanitize( $field['sanitize'], $instance[ $field['id'] ] );
			} else {
				$instance[ $field['id'] ] = strip_tags( $instance[ $field['id'] ] );
			}
		}

		return $instance;
	
	}
}
