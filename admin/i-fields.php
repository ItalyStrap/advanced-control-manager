<?php
/**
 * Interface for Fields
 *
 * This is the interface for fields class
 *
 * @link [URL]
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Admin;

interface I_Fields {

	/**
	 * Handles outputting an 'input' element
	 *
	 * @link http://html5doctor.com/html5-forms-input-types/
	 *
	 * @since  2.0.0
	 * @param  array $attr Override arguments.
	 * @param  array $key Override arguments.
	 * @return string     Form input element
	 */
	public function input( $attr = array(), $key = array() );
}
