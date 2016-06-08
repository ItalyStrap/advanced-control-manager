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
 * Add thumb to post_type table
 * @todo  maybe add, remove & customize admin table?
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$post_type_posts_columns
 */
if ( isset( $options['show-thumb'] ) ) {
	if ( isset( $_GET['post_type'] ) && 'product' === $_GET['post_type'] ) {
		return;
	}
	require( 'hooks/simply-show-thumb.php' );
	add_filter( 'manage_posts_columns', 'Italystrap\Admin\posts_columns', 5);
	add_action( 'manage_posts_custom_column', 'Italystrap\Admin\posts_custom_columns', 5, 2);
	add_filter( 'manage_pages_columns', 'Italystrap\Admin\posts_columns', 5);
	add_action( 'manage_pages_custom_column', 'Italystrap\Admin\posts_custom_columns', 5, 2);
	add_action( 'admin_head-edit.php', 'Italystrap\Admin\posts_columns_style' );
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

/**
 * The array with all plugin options
 */
$options_arr[] = $options;
$options_arr[] = array();
$injector->defineParam( 'options_arr', $options_arr );

$imp_exp_args = array(
	'name_action'	=> 'italystrap_action',
	'export_nonce'	=> 'italystrap_export_nonce',
	'import_nonce'	=> 'italystrap_import_nonce',
	'filename'		=> 'italystrap-plugin-settings-export-',
	'import_file'	=> 'italystrap_import_file',
	);
$injector->defineParam( 'imp_exp_args', $imp_exp_args );

/**
 * Import Export functionality
 *
 * @var Import_Export
 */
$import_export = $injector->make( 'ItalyStrap\Admin\Import_Export' );
add_action( 'admin_init', array( $import_export, 'export' ) );
add_action( 'admin_init', array( $import_export, 'import' ) );

/**
 * Widget Logic Functionality for admin
 *
 * @var Widget_Logic_Admin
 */
// $widget_logic_admin = $injector->make( 'ItalyStrap\Widget\Widget_Logic_Admin' );

/**
 * Widget changes submitted by ajax method.
 */
// add_filter( 'widget_update_callback', array( $widget_logic_admin, 'widget_update_callback' ), 10, 4 );
/**
 * Before any HTML output save widget changes and add controls to each widget on the widget admin page.
 */
// add_action( 'sidebar_admin_setup', array( $widget_logic_admin, 'expand_control' ) );
/**
 * Add Widget Logic specific options on the widget admin page.
 */
// add_action( 'sidebar_admin_page', array( $widget_logic_admin, 'options_control' ) );
// 
