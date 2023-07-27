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

    $new_commands_queue = [['command'       => 'create', 'fields'        => esc_js($settings['google_analytics_id']), 'fields_object' => ['cookieDomain'          => 'italystrap.com', 'siteSpeedSampleRate'   => 100]], ['command'       => 'require', 'fields'        => 'linkid', 'fields_object' => 'linkid.js'], ['command'       => 'require', 'fields'        => 'displayfeatures'], ['command'       => 'send', 'fields'        => 'pageview']];

    return $new_commands_queue;
}, 10, 2);
