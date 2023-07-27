<?php

declare(strict_types=1);

namespace ItalyStrap\Core;

use ItalyStrap\Empress\Injector;
use ItalyStrap\Config\Config;
use ItalyStrap\Config\Config_Interface;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Event\EventDispatcher;
use ItalyStrap\Event\EventDispatcherInterface;
use ItalyStrap\Event\Manager;
use ItalyStrap\Excerpt\Excerpt;
use ItalyStrap\Fields\Fields;
use ItalyStrap\I18N\Translatable;
use ItalyStrap\I18N\Translator;
use ItalyStrap\Import_Export\Import_Export;
use ItalyStrap\Lazyload\ImageSubscriber;
use ItalyStrap\Migrations\EventsMigration;
use ItalyStrap\Settings\Settings;
use ItalyStrap\View\ViewACM;
use ItalyStrap\View\ViewACM_Interface;
use ItalyStrap\Widgets\Areas\Areas;
use ItalyStrap\Widgets\Attributes\Attributes;
use ItalyStrap\Widgets\Widget_Factory;
use ItalyStrap\Shortcodes\Shortcode_Factory;
use ItalyStrap\Blocks\Block_Factory;

/**
 * Initialize the DIC
 *
 * @var Injector
 */
$injector = new Injector();
$injector->share($injector);
\add_filter('italystrap_injector', fn() => $injector);

$args = (array) require(ITALYSTRAP_PLUGIN_PATH . 'admin/config/plugin.php');
$injector->defineParam('args', $args);

$admin_settings = (array) require(ITALYSTRAP_PLUGIN_PATH . 'admin/config/options.php');

/**
 * Get the plugin and theme options
 */
$theme_mods = (array) \get_theme_mods();
$options = (array) \get_option(ITALYSTRAP_OPTIONS_NAME);

//$options = wp_parse_args( $options, get_default_from_config( $admin_settings ) );
$options = array_merge(get_default_from_config($admin_settings), $options);

$prefix_coonfig = ['prefix' => 'italystrap', '_prefix'  => '_italystrap', 'prefix_' => 'italystrap_'];

/**
 * Define theme_mods  and options parmeter
 * @deprecated 2.5.0 Use the Config object instead.
 */
$injector->defineParam('theme_mods', $theme_mods);
$injector->defineParam('options', $options);

/**=======================
 * Autoload Shared Classes
 *======================*/
$autoload_sharing = [Config::class, Excerpt::class, ViewACM::class];

$is_debug = [
    'is_debug'  => is_debug()
];

/**=============================
 * Autoload Classes definitions
 *============================*/
$fields_type = ['fields_type' => Fields::class];

$autoload_definitions = [
    Attributes::class                       => $fields_type,
    //  Settings::class                         => $fields_type,
    Import_Export::class                    => $fields_type,
    //  \ItalyStrapAdminGallerySettings::class  => $fields_type,
    Config::class                           => [':config' => \array_merge(
        $options,
        $theme_mods,
        $prefix_coonfig,
        $is_debug
    )],
    Translator::class                       => [':domain' => 'italystrap'],
    ImageSubscriber::class                          => [
        ':file'     => new \SplFileObject(
            ITALYSTRAP_PLUGIN_PATH . ( is_debug() ? 'js/src/unveil.js' : 'js/unveil.min.js' )
        ),
    ],
];

/**======================
 * Autoload Aliases Class
 *=====================*/
$autoload_aliases = [
    ConfigInterface::class => Config::class,
    Config_Interface::class   => Config::class,
    EventDispatcherInterface::class   => EventDispatcher::class,
    ViewACM_Interface::class => ViewACM::class,
    Translatable::class      => Translator::class
];

/**=============================
 * Autoload Concrete Classes
 * with option check
 * @see _init & _init_admin
 =============================*/
$autoload_subscribers = [
    'widget_areas'         => Areas::class,
    'widget_attributes'        => Attributes::class
];

/**=============================
 * Autoload Concrete Classes
 * with option check
 * @see _init & _init_admin
 =============================*/
// $autoload_concretes = array(
//  // 'ItalyStrap\Customizer\Customizer_Register',
// );

if (\defined('ITALYSTRAP_BETA')) {
    $autoload_subscribers[] = \ItalyStrap\Customizer\Customizer_Register::class;
}

/**
 * @todo Maybe add also a version like <=4.0
 */
is_italystrap_active() && $autoload_subscribers[] = EventsMigration::class;

foreach ($autoload_sharing as $class) {
    $injector->share($class);
}
foreach ($autoload_definitions as $class_name => $class_args) {
    $injector->define($class_name, $class_args);
}
foreach ($autoload_aliases as $interface => $implementation) {
    $injector->alias($interface, $implementation);
}

/**
 * The new events manager in ALPHA version.
 *
 * @var Manager
 */
$event_manager = $injector->make(Manager::class);
$events_manager = $event_manager; // Deprecated $events_manager.

/**
 * Register widgets, shortcodes and bloks
 */
$event_manager->add_subscriber(new Widget_Factory($options, $injector));
$event_manager->add_subscriber(new Shortcode_Factory($options, $injector));
if (is_dev()) {
    $event_manager->add_subscriber(new Block_Factory($options, $injector));
}

/**
 * Adjust priority to make sure this runs
 */
add_action('init', function () {
    /**
     * Load po file
     */
    \load_plugin_textdomain(
        'italystrap',
        false,
        dirname(ITALYSTRAP_BASENAME) . '/lang'
    );
}, 100);

$autoload_plugin_files_init = ['/_init.php', '/_init-admin.php'];

foreach ($autoload_plugin_files_init as $file) {
    require(__DIR__ . $file);
}

$app = [
    'sharing'               => $autoload_sharing,
    'aliases'               => $autoload_aliases,
    'definitions'           => $autoload_definitions,
    'define_param'          => [],
    'delegations'           => [],
    'preparations'          => [],
    // 'concretes'              => $autoload_concretes,
    // 'options_concretes'      => $autoload_options_concretes,
    'subscribers'           => $autoload_subscribers,
];

/**
 * ================================
 * Now load the plugin application
 *
 * @var \ItalyStrap\Plugin\Loader
 * =================================
 */
$italystrap_plugin = new \ItalyStrap\Plugin\Loader($injector, $event_manager, $app, $options);
add_action('after_setup_theme', [$italystrap_plugin, 'load'], 10);
