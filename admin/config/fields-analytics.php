<?php

declare(strict_types=1);

namespace ItalyStrap;

use ItalyStrap\Core;

$beta = ' {{BETA VERSION}}';

return [
    [
        'label'         => __('Activate Google analitycs script', 'italystrap'),
        'desc'          => __('This will add the Google analytics script in every page of your site before the <code>&lt;/body&gt;</code> tag. The snippet will be appended to <code>wp_footer</code> hook.', 'italystrap'),
        'id'            => 'activate_analytics',
        'type'          => 'checkbox',
        'class'         => 'activate_analytics easy',
        'value'         => '',
        'sanitize'      => 'sanitize_text_field',
    ],
    [
        'label'         => __('Google Tag Manager ID', 'italystrap'),
        'desc'          => __('Available only for ItalyStrap theme framework. Insert your google tag manager ID', 'italystrap') . $beta,
        'id'            => 'google_tag_manager_id',
        'type'          => 'text',
        'class'         => 'google_tag_manager_id easy',
        'value'         => '',
        'sanitize'      => 'sanitize_text_field',
        'show_on'               => Core\is_beta() && Core\is_italystrap_active(),
    ],
    [
        'label'         => __('Google Analytics ID', 'italystrap'),
        'desc'          => __('Insert your google analytics ID', 'italystrap'),
        'id'            => 'google_analytics_id',
        'type'          => 'text',
        'class'         => 'google_analytics_id easy',
        'value'         => '',
        'sanitize'      => 'sanitize_text_field',
    ],
    [
        'label'         => __('Anonymize IP', 'italystrap'),
        'desc'          => __('Do you want to anonymize Google Analytics IP?', 'italystrap'),
        'id'            => 'google_analytics_anonymizeIp',
        'type'          => 'checkbox',
        'class'         => 'google_analytics_anonymizeIp easy',
        'value'         => '',
        'sanitize'      => 'sanitize_text_field',
    ],
    [
        'label'         => __('Google Analytics position', 'italystrap'),
        'desc'          => __('Select the position for Google Analytics. Default: <code>wp_footer</code>. Note: If you verify your site in search console you have to set <code>wp_head</code>.', 'italystrap'),
        'id'            => 'google_analytics_position',
        'type'          => 'select',
        'options'       => [
            'wp_footer' => __('Before the &lt;/body&gt; closed', 'italystrap'),
            'wp_head'   => __('Before the &lt;/head&gt; closed', 'italystrap'),
        ],
        'class'         => 'google_analytics_position easy',
        'value'         => 'wp_footer',
        'sanitize'      => 'sanitize_text_field',
    ],
];
