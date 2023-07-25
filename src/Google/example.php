<?php

/**
 * Example code for Analytics
 *
 * @link italystrap.com
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

/**
 * Aggiungo i comandi ga che prima erano presenti nel file header.
 *
 * This will create:
 * ga("create","UA-xxxxxxxx-xx",{"cookieDomain":"italystrap.com","siteSpeedSampleRate":100});
 * ga("require","linkid","linkid.js");
 * ga("require","displayfeatures");
 * ga("send","pageview");
 */
add_filter('italystrap_ga_commands_queue', function ($parameters, $settings) {

    $new_commands_queue = array(
            array(
                'command'       => 'create',
                'fields'        => esc_js($settings['google_analytics_id']),
                'fields_object' => array(
                     'cookieDomain'          => 'italystrap.com',
                     'siteSpeedSampleRate'   => 100,
                 ),
            ),
            array(
                'command'       => 'require',
                'fields'        => 'linkid',
                'fields_object' => 'linkid.js',
            ),
            array(
                'command'       => 'require',
                'fields'        => 'displayfeatures',
            ),
            array(
                'command'       => 'send',
                'fields'        => 'pageview',
            ),
    );

    return $new_commands_queue;
}, 10, 2);
