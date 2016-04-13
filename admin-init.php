<?php
/**
 * ItalyStrap init file
 *
 * Init the plugin front-end functionality
 *
 * @link www.italystrap.com
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Admin;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

if ( ! is_admin() ) {
	return;
}

$injector = new \Auryn\Injector;

/**
 * Get the plugin options
 *
 * @var array
 */
$options = (array) get_option( 'italystrap_settings' );

/**
 * Define options parmeter
 */
$injector->defineParam( 'options', $options );

/**
 * Instantiate Init Class
 *
 * @var Init
 */
$init = $injector->make( 'ItalyStrap\Core\Init' );

/**
 * Register widget
 */
add_action( 'widgets_init', array( $init, 'widgets_init' ) );

/**
 * Istantiate this class only if is admin
 */

/**
 * Add ID to post_type table
 */
if ( isset( $options['show-ids'] ) ) {
	require( 'hooks/simply-show-ids.php' );
	add_action( 'admin_init', '\ItalyStrap\Admin\ssid_add' );
}

/**
 * Define admin settings parmeter
 */
$admin_settings = (array) require( ITALYSTRAP_PLUGIN_PATH . '/admin/settings/settings-admin-page.php' );
$injector->defineParam( 'settings', $admin_settings );
// d( $admin_settings['general']['settings_fields'][0] );
// get_admin_default_settings( $admin_settings );

/**
 * Instanziate the Fields
 *
 * @var Fields
 */
// $fields = $injector->make( 'ItalyStrap\Admin\Fields' );
// $injector->share( $fields );

/**
 * Instantiate Admin Class
 *
 * @param array    $options     Plugin options
 * @param I_Fields $fields_type Fields object
 * @param array    $settings    Admin settings
 *
 * @var Admin
 */
$injector->define( 'ItalyStrap\Admin\Admin', ['fields_type' => 'ItalyStrap\Admin\Fields'] );
$admin = $injector->make( 'ItalyStrap\Admin\Admin' );
$admin->init();

/**
 * Instanziate the ItalyStrapAdminGallerySettings
 *
 * @var ItalyStrapAdminGallerySettings
 */
$gallery_settings = $injector->make( 'ItalyStrapAdminGallerySettings' );

/**
 * Da testare
 *
 * Example: new \ItalyStrap\Admin\Gallery_Settings;
 * $gallery_settings = new Gallery_Settings;
 * add_action( 'admin_init', array( $gallery_settings, 'admin_init' ) );
 */

/**
 * Instanziate the ItalyStrapAdminMediaSettings
 *
 * @var ItalyStrapAdminMediaSettings
 */
$image_size_media = $injector->make( 'ItalyStrapAdminMediaSettings' );
add_filter( 'image_size_names_choose', array( $image_size_media, 'get_image_sizes' ), 999 );

/**
 * Instanziate the Register_Metaboxes
 *
 * @var Register_Metaboxes
 */
$register_metabox = $injector->make( 'ItalyStrap\Admin\Register_Metaboxes' );
add_action( 'cmb2_admin_init', array( $register_metabox, 'register_script_settings' ) );
