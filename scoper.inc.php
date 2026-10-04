<?php

declare(strict_types=1);

/**
 * PHP-Scoper configuration.
 *
 * Prefixes every namespace shipped by the plugin, its own code and its vendor packages,
 * so the bundled ItalyStrap libraries never collide with the copies loaded by the
 * ItalyStrap theme or by other plugins.
 *
 * Run through bin/build-scoped.sh, not directly.
 */

$excludesDir = __DIR__ . '/tools/php-scoper/vendor/sniccowp/php-scoper-wordpress-excludes/generated/';

$readExcludes = static function (string $file) use ($excludesDir): array {
    return json_decode((string) file_get_contents($excludesDir . $file), true, 512, JSON_THROW_ON_ERROR);
};

return [
    'prefix' => 'ItalyStrapAcm',

    // CMB2 is a global library with its own version negotiation between copies,
    // so it is shipped untouched.
    // functions/default-constants.php declares global helpers called unqualified from
    // namespaced code, so it stays global too.
    'exclude-files' => array_merge(
        array_map(
            'realpath',
            iterator_to_array(
                (new \Isolated\Symfony\Component\Finder\Finder())
                    ->files()
                    ->in(getcwd() . '/vendor/cmb2/cmb2')
                    ->name('*.php'),
                false
            )
        ),
        [realpath(getcwd() . '/functions/default-constants.php')],
        // View templates that start with HTML instead of `<?php` would get a namespace
        // statement in the middle of the output, so they are copied as they are.
        array_values(array_filter(
            array_map(
                'realpath',
                iterator_to_array(
                    (new \Isolated\Symfony\Component\Finder\Finder())
                        ->files()
                        ->in(getcwd())
                        ->exclude('vendor')
                        ->name('*.php'),
                    false
                )
            ),
            static fn (string $file): bool => strncmp((string) file_get_contents($file, false, null, 0, 5), '<?php', 5) !== 0
        ))
    ),

    // Theme 4 UI namespace: its event class names are hooks owned by the theme.
    'exclude-namespaces' => ['ItalyStrap\UI'],

    'exclude-classes' => array_merge(
        $readExcludes('exclude-wordpress-classes.json'),
        $readExcludes('exclude-wordpress-interfaces.json'),
        ['/^CMB2/']
    ),

    'exclude-functions' => array_merge(
        $readExcludes('exclude-wordpress-functions.json'),
        ['/^cmb2_/', 'new_cmb2_box', 'italystrap_set_default_constant', 'italystrap_define_constants']
    ),

    'exclude-constants' => array_merge(
        $readExcludes('exclude-wordpress-constants.json'),
        ['/^ITALYSTRAP_/', '/^CMB2_/']
    ),

    // PHP-Scoper only rewrites real symbols. Class and function names written as strings
    // ('ItalyStrap\\Core\\fn', autoloader prefixes, callables) are prefixed here.
    'patchers' => [
        // italystrap/edd sets undeclared properties, deprecated since PHP 8.2.
        static function (string $filePath, string $prefix, string $contents): string {
            if (strpos($filePath, '/vendor/italystrap/edd/') === false) {
                return $contents;
            }

            return (string) preg_replace(
                '#^([ \t]*)((?:final |abstract )?class \w+)#m',
                "$1#[\\AllowDynamicProperties]\n$1$2",
                $contents
            );
        },
        static function (string $filePath, string $prefix, string $contents): string {
            if (substr($filePath, -4) !== '.php') {
                return $contents;
            }

            return (string) preg_replace(
                // Theme 4 event names (ItalyStrap\\UI\\Components\\...) are hooks owned by the theme.
                '#\'\\\\?ItalyStrap\\\\(?!\\\\?UI\\\\)#',
                '\'' . $prefix . '\\\\ItalyStrap\\\\',
                $contents
            );
        },
    ],

    'expose-global-constants' => true,
    'expose-global-classes' => false,
    'expose-global-functions' => false,
];
