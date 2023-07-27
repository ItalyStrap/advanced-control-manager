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

use function ItalyStrap\Core\is_italystrap_active;

if (! is_admin()) {
    return;
}

$autoload_subscribers = array_merge($autoload_subscribers, [
    // 'option_name'            => 'Class\Name',
    //      'media_carousel_shortcode'  => \ItalyStrapAdminGallerySettings::class,
    CMB2_Factory::class,
]);

if (! isset($pagenow)) {
    $pagenow = '';
}

/**
 * TinyMCE Editor in Category description
 */
if ('edit-tags.php' === $pagenow || 'term.php' === $pagenow) {
    $autoload_subscribers['visual_editor_on_terms'] = Terms::class;
}

// $autoload_concretes = array_merge( $autoload_concretes, array(
//      'ItalyStrap\Custom\Metaboxes\CMB2_Factory',
//  )
// );

// foreach ( $autoload_subscribers as $option_name => $concrete ) {
//  if ( empty( $options[ $option_name ] ) ) {
//      continue;
//  }
//  $event_manager->add_subscriber( $injector->make( $concrete ) );
// }

// add_action( 'plugins_loaded', 'ItalyStrap\Core\plugin_on_activation' );
// add_action( 'admin_init', 'ItalyStrap\Core\_notice_plugin_update' );

if (! empty($options['widget_visibility'])) {
    \add_action('admin_init', [\ItalyStrap\Widgets\Visibility\Visibility_Admin::class, 'init']);
}

/**
 * Add ID to post_type table
 */
if (! empty($options['show-ids'])) {
    require('hooks/simply-show-ids.php');
    \add_action('admin_init', '\ItalyStrap\Admin\ssid_add');
}

/**
 * Add thumb to post_type table
 * @todo  maybe add, remove & customize admin table?
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$post_type_posts_columns
 */
if (! empty($options['show-thumb'])) {
    if (isset($_GET['post_type']) && 'product' === $_GET['post_type']) {
        return;
    }
    require('hooks/simply-show-thumb.php');
    \add_filter('manage_posts_columns', 'Italystrap\Admin\posts_columns', 5);
    \add_action('manage_posts_custom_column', 'Italystrap\Admin\posts_custom_columns', 5, 2);
    \add_filter('manage_pages_columns', 'Italystrap\Admin\posts_columns', 5);
    \add_action('manage_pages_custom_column', 'Italystrap\Admin\posts_custom_columns', 5, 2);
    \add_action('admin_head-edit.php', 'Italystrap\Admin\posts_columns_style');
}

/**
 * Define admin settings parmeter and Instantiate Admin Settings Class
 */
// $admin_settings = (array) require( ITALYSTRAP_PLUGIN_PATH . '/admin/config/options.php' );
$injector->defineParam('settings', $admin_settings);

//$event_manager->add_subscriber( $injector->make( Settings::class ) );

$settings = new SettingsBuilder(
    ITALYSTRAP_OPTIONS_NAME,
    ITALYSTRAP_BASENAME,
    ITALYSTRAP_FILE
);

// Custom Dashboard page
$settings->addPage(
    require 'admin/config/page-dashboard.php'
);

$settings_config = ConfigFactory::make(require 'admin/config/settings.php');
// Settings page
$settings->addPage(
    $settings_config->get('page'),
    $settings_config->get('sections')
);

// Migration page
//$settings->addPage(
//    [
//      Page::PARENT        => 'italystrap-dashboard',
//      Page::PAGE_TITLE    => __('Migrations', 'italystrap'),
//      Page::MENU_TITLE    => __('Migrations', 'italystrap'),
//      Page::SLUG          => 'italystrap-migrations',
//      Page::VIEW          => ITALYSTRAP_PLUGIN_PATH . '/admin/view/italystrap-migrations.php',
//    'show_on'           => \strpos(get_option('template'), 'ItalyStrap') !== false,
//    ]
//);

$settings->addCustomPluginLink(
    'key-for-css',
    'https://italystrap.com',
    'ItalyStrap',
    [ 'target' => '_blank' ]
);

$settings->build();

/**
 * Import/Export configuration
 *
 * @var array
 */
$imp_exp_args = [
    'capability'   => 'manage_options',
    'name_action'  => 'italystrap_action',
    'export_nonce'  => 'italystrap_export_nonce',
    'import_nonce'    => 'italystrap_import_nonce',
    'filename'        => 'italystrap-plugin-settings-export',
    'import_file'   => 'italystrap_import_file',
    'options_names'    => [
        $args['options_name']],
    'i18n'          => []
];
$injector->defineParam('imp_exp_args', $imp_exp_args);

/**
 * Import Export
 */
$import_export = $injector->make(Import_Export::class);
$event_manager->add_subscriber($import_export);

/**
 * Instanziate the ItalyStrap\Image\Size
 *
 * @var Size
 */
$image_size_media = $injector->make(Size::class);
$event_manager->add_subscriber($image_size_media);

/**
 * Option for jpeg_quality
 */
if (! empty($options['jpeg_quality'])) {
    \add_filter('jpeg_quality', function ($quality, $context) use ($options) {

        $options['jpeg_quality'] = $options['jpeg_quality'] > 99 ? 100 : $options['jpeg_quality'];

        return absint($options['jpeg_quality']);
    }, 99, 2);
}

/**
 * Instantiate the Register_Metaboxes.
 * Questo oggetto va lasciato fuori da condizionali perché
 * il tema nel file bootstrap mi esegue il metodo 'register_widget_areas_fields'
 *
 * @var Register_Metaboxes
 */
$register_metabox = $injector->make(Register_Metaboxes::class);
$event_manager->add_subscriber($register_metabox);

/**
 * Add fields to widget areas
 * The widget areas is a CPT for adding custom widget registration in
 * specific position in the theme
 */

/**
 * @todo Maybe add also a version like <=4.0
 */
/** @var callable $callable */
$callable = [$register_metabox, 'register_widget_areas_fields'];
is_italystrap_active() && \add_action('cmb2_admin_init', $callable);
