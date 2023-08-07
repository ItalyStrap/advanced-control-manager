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

(new class {
    private const PHP_MIN_VERSION = 'php_min_version';
    private const WP_MIN_VERSION = 'wp_min_version';
    private const PLUGIN_NAME = 'plugin_name';
    private const PLUGIN_VERSION = 'version';
    private string $pluginFile = __DIR__ . '/italystrap.php';

    public function __invoke(): bool
    {
        require __DIR__ . '/vendor/italystrap/platform-requirements-check/autoload.php';

        /**
         * @see \get_plugin_data() for headings
         */
        $plugin_data = \get_file_data($this->pluginFile, [
            self::WP_MIN_VERSION => 'Requires at least',
            self::PHP_MIN_VERSION => 'Requires PHP',
            self::PLUGIN_NAME => 'Plugin Name',
            self::PLUGIN_VERSION => 'Version',
        ]);

        $plugin_data = \array_map(static function ($element) {
            return \trim(\filter_var($element, \FILTER_SANITIZE_STRING));
        }, $plugin_data);

        $requirementsList = [
        new \ItalyStrap\PlatformRequirementsCheck\RangeVersionRequirement(
                'PHP',
                \PHP_VERSION,
                (string)$plugin_data[self::PHP_MIN_VERSION],
                '8.0.' . PHP_INT_MAX
            ),
        ];

        $theme = \wp_get_theme();
        $theme = $theme->parent() ?: $theme;
        $name = (string)$theme->get('Name');

        if ($name === 'ItalyStrap') {
            $requirementsList[] = new \ItalyStrap\PlatformRequirementsCheck\RangeVersionRequirement(
                $name,
                (string)$theme->get('Version'),
                '3.0.0',
                //  '3.0.4'
                //  '4.0.0-beta.8'
            );
        }

        $requirements = (new \ItalyStrap\PlatformRequirementsCheck\Requirements(...$requirementsList));

        if (!$requirements->check()) {
            \register_activation_hook(
                $this->pluginFile,
                function () use ($requirements): void {
                    \deactivate_plugins(\plugin_basename($this->pluginFile));
                    \wp_die(
                        \sprintf(
                            '<p>%1$s</p><p>%2$s</p>',
                            \esc_html__('The plugin has been deactivated.', 'italystrap'),
                            \implode('<br>', $requirements->errorMessages())
                        ),
                        \esc_html__('Plugin Activation Error', 'italystrap'),
                        [
                        'back_link' => true,
                        ]
                    );
                }
            );

            \add_action(
                'admin_notices',
                static function () use ($requirements): void {
                    ?>
                    <div class="notice notice-error">
                        <?php foreach ($requirements->errorMessages() as $message) : ?>
                        <p><?php \esc_html_e($message); ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php
                }
            );

            return false;
        }

        if (! defined('ITALYSTRAP_PLUGIN_VERSION')) {
            define('ITALYSTRAP_PLUGIN_VERSION', (string)$plugin_data[self::PLUGIN_VERSION]);
        }

        /**
         * Init plugin default constant
         */
        require(__DIR__ . '/functions/default-constants.php');
        italystrap_set_default_constant(__DIR__ . '/italystrap.php', 'ITALYSTRAP');

        $autoload_plugin_files = [
            '/vendor/autoload.php',
            '/vendor/cmb2/cmb2/init.php',
            '/functions/autoload.php',
        ];

        foreach ($autoload_plugin_files as $file) {
            require(__DIR__ . $file);
        }

        $injector = new Injector();
        $injector->share($injector);
        \add_filter('italystrap_injector', fn() => $injector);

        $args = (array)require(ITALYSTRAP_PLUGIN_PATH . 'admin/config/plugin.php');
        $injector->defineParam('args', $args);

        $admin_settings = (array)require(ITALYSTRAP_PLUGIN_PATH . 'admin/config/options.php');

        /**
         * Get the plugin and theme options
         */
        $theme_mods = (array)\get_theme_mods();
        $options = (array)\get_option(ITALYSTRAP_OPTIONS_NAME);

        //$options = wp_parse_args( $options, get_default_from_config( $admin_settings ) );
        $options = array_merge(get_default_from_config($admin_settings), $options);

        $prefix_coonfig = ['prefix' => 'italystrap', '_prefix' => '_italystrap', 'prefix_' => 'italystrap_'];

        /**
         * Define theme_mods  and options parmeter
         * @deprecated 2.5.0 Use the Config object instead.
         */
        $injector->defineParam('theme_mods', $theme_mods);
        $injector->defineParam('options', $options);

        /**
         * =======================
         * Autoload Shared Classes
         * ======================
         */
        $autoload_sharing = [Config::class, Excerpt::class, ViewACM::class];

        $is_debug = [
            'is_debug' => is_debug()
        ];

        /**=============================
         Autoload Classes definitions
        ============================*/
        $fields_type = ['fields_type' => Fields::class];

        $autoload_definitions = [
            Attributes::class => $fields_type,
            //  Settings::class                         => $fields_type,
            Import_Export::class => $fields_type,
            //  \ItalyStrapAdminGallerySettings::class  => $fields_type,
            Config::class => [':config' => \array_merge(
                $options,
                $theme_mods,
                $prefix_coonfig,
                $is_debug
            )],
            Translator::class => [':domain' => 'italystrap'],
            ImageSubscriber::class => [
                ':file' => new \SplFileObject(
                    ITALYSTRAP_PLUGIN_PATH . (is_debug() ? 'js/src/unveil.js' : 'js/unveil.min.js')
                ),
            ],
        ];

        /**======================
         Autoload Aliases Class
        =====================*/
        $autoload_aliases = [
            ConfigInterface::class => Config::class,
            Config_Interface::class => Config::class,
            EventDispatcherInterface::class => EventDispatcher::class,
            ViewACM_Interface::class => ViewACM::class,
            Translatable::class => Translator::class
        ];

        /**=============================
         Autoload Concrete Classes
         with option check
         @see _init & _init_admin
         =============================*/
        $autoload_subscribers = [
            'widget_areas' => Areas::class,
            'widget_attributes' => Attributes::class
        ];

        /**=============================
         Autoload Concrete Classes
         with option check
         @see _init & _init_admin
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
         * @var Manager $event_manager
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
        \add_action('init', function () {
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
            'sharing' => $autoload_sharing,
            'aliases' => $autoload_aliases,
            'definitions' => $autoload_definitions,
            'define_param' => [],
            'delegations' => [],
            'preparations' => [],
            // 'concretes'              => $autoload_concretes,
            // 'options_concretes'      => $autoload_options_concretes,
            'subscribers' => $autoload_subscribers,
        ];

        $italystrap_plugin = new \ItalyStrap\Plugin\Loader($injector, $event_manager, $app, $options);
        add_action('after_setup_theme', [$italystrap_plugin, 'load'], 10);

        \add_filter('italystrap_theme_updater_config', static function (array $edd_config) {
            $item_name = 'ItalyStrap Theme Framework';
            $theme_slug = \get_template();
            $theme = \wp_get_theme($theme_slug);

            $edd_config[] = [
                'config' => [
                    'item_name' => $item_name, // Name of theme
                    'theme_slug' => $theme_slug, // Theme slug
                    'version' => $theme->display('Version', false), // The current version of this theme
                    'author' => $theme->display('Author', false), // The author of this theme
                    'download_id' => '', // Optional, used for generating a license renewal link
                    'renew_url' => '', // Optional, allows for a custom license renewal link
                    'beta' => \get_theme_mod('beta', true), // Optional
                ],
                'strings' => [
                    'theme-license' => \sprintf(
                        \__('%s License', 'italystrap'),
                        $theme->display('Name', false)
                    ),
                ],
            ];

            return $edd_config;
        });

        /**
             This filter is used to load your php file right after ItalyStrap plugin is loaded.
             The purpose is to have al code in the same scope without using global
             with variables provided from this plugin.

             @example
             Usage example:

             1 - First of all you have to have the file/files with some code
                 that extending this plugins functionality in your plugin path.
             2 - Than you have to activate your plugin.
             3 - And then see the below example.

             add_filter( 'italystrap_require_plugin_files_path', 'add_your_files_path' );

             function add_your_files_path( array $arg ) {
                 return array_merge(
                              $arg,
                              array( plugin_dir_path( __FILE__ ) . 'my-dir/my-file.php' )
                 );
             }

             @example
             Another example:
             add_filter( 'italystrap_require_plugin_files_path', function ( $files_path ) {

                 $files_path[] = PLUGIN_PATH . 'bootstrap.php';

                 return $files_path;
             } );

             Important:
             Remeber that the file you want to load just after ItalyStrap plugin
             has not to be required/included from your plugin because
             you will get an error 'You can't redeclare...'.

             @since 2.0.0

             @deprecated 4.0.0 Do not use this hook
         */
        $plugin_files_path = (array)apply_filters('italystrap_require_plugin_files_path', []);

        if (!empty($plugin_files_path)) {
            foreach ($plugin_files_path as $key => $plugin_file_path) {
                if (!file_exists($plugin_file_path)) {
                    continue;
                }
                require($plugin_file_path);
            }
            /**
             * Fires once ItalyStrap Child plugin has loaded.
             * @since 2.0.0
             */
            do_action('italystrap_child_plugin_loaded', null);
        }

        /**
             Fires once ItalyStrap plugin has loaded.

             @since 2.0.0
        */
        do_action('italystrap_plugin_loaded', null);

        return true;
    }
})();
