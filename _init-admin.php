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

use ItalyStrap\Config\ConfigFactory;
use ItalyStrap\Custom\Metaboxes\CMB2_Factory;
use ItalyStrap\Custom\Metaboxes\Register_Metaboxes;
use ItalyStrap\Editors\Terms;
use ItalyStrap\Image\Size;
use ItalyStrap\Import_Export\Import_Export;
use ItalyStrap\Settings\Page;
use ItalyStrap\Settings\Settings;
use ItalyStrap\Settings\SettingsBuilder;
use ItalyStrap\Settings\ViewPage;

if ( ! is_admin() ) {
	return;
}

$autoload_subscribers = array_merge( $autoload_subscribers, array(
		// 'option_name'			=> 'Class\Name',
		'media_carousel_shortcode'	=> \ItalyStrapAdminGallerySettings::class,
		CMB2_Factory::class,
	)
);

if ( ! isset( $pagenow ) ) {
	$pagenow = '';
}

/**
 * TinyMCE Editor in Category description
 */
if ( 'edit-tags.php' === $pagenow || 'term.php' === $pagenow ) {
	$autoload_subscribers['visual_editor_on_terms'] = Terms::class;
}

// $autoload_concretes = array_merge( $autoload_concretes, array(
// 		'ItalyStrap\Custom\Metaboxes\CMB2_Factory',
// 	)
// );

// foreach ( $autoload_subscribers as $option_name => $concrete ) {
// 	if ( empty( $options[ $option_name ] ) ) {
// 		continue;
// 	}
// 	$event_manager->add_subscriber( $injector->make( $concrete ) );
// }

// add_action( 'plugins_loaded', 'ItalyStrap\Core\plugin_on_activation' );
// add_action( 'admin_init', 'ItalyStrap\Core\_notice_plugin_update' );

if ( ! empty( $options['widget_visibility'] ) ) {
	\add_action( 'admin_init', [\ItalyStrap\Widgets\Visibility\Visibility_Admin::class, 'init'] );
}

/**
 * Add ID to post_type table
 */
if ( ! empty( $options['show-ids'] ) ) {
	require( 'hooks/simply-show-ids.php' );
	\add_action( 'admin_init', '\ItalyStrap\Admin\ssid_add' );
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
	\add_filter( 'manage_posts_columns', 'Italystrap\Admin\posts_columns', 5);
	\add_action( 'manage_posts_custom_column', 'Italystrap\Admin\posts_custom_columns', 5, 2);
	\add_filter( 'manage_pages_columns', 'Italystrap\Admin\posts_columns', 5);
	\add_action( 'manage_pages_custom_column', 'Italystrap\Admin\posts_custom_columns', 5, 2);
	\add_action( 'admin_head-edit.php', 'Italystrap\Admin\posts_columns_style' );
}

/**
 * Define admin settings parmeter and Instantiate Admin Settings Class
 */
// $admin_settings = (array) require( ITALYSTRAP_PLUGIN_PATH . '/admin/config/options.php' );
$injector->defineParam( 'settings', $admin_settings );
$event_manager->add_subscriber( $injector->make( Settings::class ) );

//$settings_builder = new SettingsBuilder();
//$settings_builder->build(
//	ConfigFactory::make( require 'admin/config/settings.php' ),
//	ITALYSTRAP_OPTIONS_NAME,
//	'italystrap',
//	ITALYSTRAP_BASENAME
//);
//
//$pages_obj2 = new Page(
//	ConfigFactory::make( require 'admin/config/page-dashboard.php' ),
//	new ViewPage()
//);
//$pages_obj2->boot();
//$settings_builder->getLinks()->forPages( $pages_obj2 );

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

/**
 * Import Export
 */
$import_export = $injector->make( Import_Export::class );
$event_manager->add_subscriber( $import_export );

/**
 * Instanziate the ItalyStrap\Image\Size
 *
 * @var Size
 */
$image_size_media = $injector->make( Size::class );
$event_manager->add_subscriber( $image_size_media );

/**
 * Option for jpeg_quality
 */
if ( ! empty( $options['jpeg_quality'] ) ) {
	\add_filter( 'jpeg_quality', function ( $quality, $context ) use ( $options ) {

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
$register_metabox = $injector->make( Register_Metaboxes::class );
$event_manager->add_subscriber( $register_metabox );
