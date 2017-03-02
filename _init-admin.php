<?php
/**
 * ItalyStrap init admin file
 *
 * Init the plugin admin functionality
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

add_action( 'plugins_loaded', 'ItalyStrap\Core\plugin_on_activation' );
add_action( 'admin_init', 'ItalyStrap\Core\_notice_plugin_update' );

if ( ! empty( $options['widget_visibility'] ) ) {
	add_action( 'admin_init', array( 'ItalyStrap\Widget\Visibility\Visibility_Admin', 'init' ) );
}

/**
 * Add ID to post_type table
 */
if ( ! empty( $options['show-ids'] ) ) {
	require( 'hooks/simply-show-ids.php' );
	add_action( 'admin_init', '\ItalyStrap\Admin\ssid_add' );
}

/**
 * Add thumb to post_type table
 * @todo  maybe add, remove & customize admin table?
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$post_type_posts_columns
 */
if ( ! empty( $options['show-thumb'] ) ) {
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
$admin_settings = (array) require( ITALYSTRAP_PLUGIN_PATH . '/admin/config/options.php' );
$injector->defineParam( 'settings', $admin_settings );
// d( $admin_settings['general']['settings_fields'][0] );
// get_admin_default_settings( $admin_settings );

// echo "<pre>";
// print_r(get_theme_mods());
// echo "</pre>";

$fields_type = array( 'fields_type' => 'ItalyStrap\Fields\Fields' );

/**
 * Instantiate Admin Class
 *
 * @param array    $options     Plugin options
 * @param I_Fields $fields_type Fields object
 * @param array    $settings    Admin settings
 *
 * @var Admin
 */
$injector->define( 'ItalyStrap\Settings\Settings', $fields_type );
$admin = $injector->make( 'ItalyStrap\Settings\Settings' );
$admin->init();
// $admin->delete_option();
add_action( 'update_option', array( $admin, 'save' ), 10, 3 );

/**
 * Import Export
 */

/**
 * Import/Export configuration
 *
 * @var array
 */
$imp_exp_args = array(
	'capability'	=> 'manage_options',
	'name_action'	=> 'italystrap_action',
	'export_nonce'	=> 'italystrap_export_nonce',
	'import_nonce'	=> 'italystrap_import_nonce',
	'filename'		=> 'italystrap-plugin-settings-export',
	'import_file'	=> 'italystrap_import_file',
	'options_names'	=> array(
			$args['options_name'],
			// 'theme_mods_ItalyStrap',
			// 'theme_mods_' . get_option( 'stylesheet' ),
			// 'italystrap_widget_area',
			// 'theme_mods_twentyeleven',
	),
	'i18n'			=> array(),
);
$injector->defineParam( 'imp_exp_args', $imp_exp_args );

$injector->define( 'ItalyStrap\Import_Export\Import_Export', $fields_type );
/**
 * Import Export Object
 *
 * @var Import_Export
 */
$import_export = $injector->make( 'ItalyStrap\Import_Export\Import_Export' );
add_action( 'italystrap_after_settings_page', array( $import_export, 'get_view' ) );
add_action( 'admin_init', array( $import_export, 'export' ) );
add_action( 'admin_init', array( $import_export, 'import' ) );

/**
 * Instanziate the ItalyStrapAdminGallerySettings
 */
$injector->define( 'ItalyStrapAdminGallerySettings', $fields_type );
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
 * Option for jpeg_quality
 */
if ( ! empty( $options['jpeg_quality'] ) ) {
	add_filter( 'jpeg_quality', function ( $quality, $context ) use ( $options ) {

		$options['jpeg_quality'] = $options['jpeg_quality'] > 99 ? 100 : $options['jpeg_quality'];

		return absint( $options['jpeg_quality'] );
	}, 99, 2 );
}

/**
 * Instanziate the Register_Metaboxes.
 * Questo oggetto va lasciato fuori da condizionali perché
 * il tema nel file bootstrap mi esegue il metodo 'register_widget_areas_fields'
 *
 * @var Register_Metaboxes
 */
$register_metabox = $injector->make( 'ItalyStrap\Admin\Register_Metaboxes' );

if ( ! empty( $options['activate_custom_css'] ) ) {

	add_action( 'cmb2_admin_init', array( $register_metabox, 'register_style_fields_in_wp_editor' ) );
}

if ( ! empty( $options['widget_attributes'] ) ) {
	/**
	 * Init the Widget_Attributes
	 *
	 * @var Widget_Attributes
	 */
	$injector->define( 'ItalyStrap\Widget\Attributes\Attributes', $fields_type );
	$widget_attributes = $injector->make( 'ItalyStrap\Widget\Attributes\Attributes' );
	add_action( 'in_widget_form', array( $widget_attributes, 'input_fields' ), 10, 3 );
	add_filter( 'widget_update_callback', array( $widget_attributes, 'save_attributes' ), 10, 4 );
}
