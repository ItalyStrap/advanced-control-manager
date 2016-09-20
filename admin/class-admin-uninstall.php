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
class Admin_Uninstall extends A_Admin{

	/**
	 * Initialize Class
	 *
	 * @param array  $options     Get the plugin options.
	 * @param object $fields_type The Fields object.
	 */
	public function __construct( array $options = array(), array $settings, array $args, I_Fields $fields_type ) {

		parent::__construct( $options, $settings, $args, $fields_type );

	}
}
