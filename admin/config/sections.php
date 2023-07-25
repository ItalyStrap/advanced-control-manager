<?php

/**
 * Settings file
 *
 * @package ItalyStrap
 */

declare(strict_types=1);

namespace ItalyStrap;

use ItalyStrap\Settings\Sections as S;

/**
 * Settings for the content area
 */
return [
    [
        S::TAB_TITLE        => __('General', 'italystrap'),
        S::ID               => 'general',
        S::TITLE            => __('General options page', 'italystrap'),
        S::DESC             => __('General setting for ItalyStrap', 'italystrap'),
        S::FIELDS           => require 'fields-general.php', // Mandatory
    ],
    [
        S::TAB_TITLE        => __('Widgets', 'italystrap'),
        S::ID               => 'widget',
        S::TITLE            => __('Options page for widgets', 'italystrap'),
        S::DESC             => __('Select the widgets you want to use.', 'italystrap'),
        S::FIELDS           => require 'fields-widget.php', // Mandatory
    ],
    [
        S::TAB_TITLE        => __('Shortcodes', 'italystrap'),
        S::ID               => 'shortcode',
        S::TITLE            => __('Options page for shortcodes', 'italystrap'),
        S::DESC             => __('Select the shortcodes you want to use.', 'italystrap'),
        S::FIELDS           => require 'fields-shortcode.php', // Mandatory
    ],
    [
        S::TAB_TITLE        => __('Style', 'italystrap'),
        S::ID               => 'style',
        S::TITLE            => __('Options page for style purpose', 'italystrap'),
        S::DESC             => __('This is the tab for changing the style of your site. Code entered here will be included in every page of the front-end of your site.', 'italystrap'),
        S::FIELDS           => require 'fields-style.php', // Mandatory
    ],
    [
        S::TAB_TITLE        => Core\is_beta() ? __('GA + GTM', 'italystrap') : __('GA', 'italystrap'),
        S::ID               => 'analytics',
        S::TITLE            => __('Options page for Google Analytics', 'italystrap'),
        S::DESC             => __('Here you can configure google analytics settings and activate the GA tracking code in your site.', 'italystrap'),
        S::FIELDS           => require 'fields-analytics.php', // Mandatory
    ],
    [
        S::TAB_TITLE        => __('Content', 'italystrap'),
        S::ID               => 'content',
        S::TITLE            => __('Options page for the content area', 'italystrap'),
        S::DESC             => __('Here you can configure the content area in your site.', 'italystrap'),
        S::FIELDS           => require 'fields-content.php', // Mandatory
    ],
    [
        S::TAB_TITLE        => __('Media', 'italystrap'),
        S::ID               => 'media',
        S::TITLE            => __('Settings page for media add-on functionality', 'italystrap'),
        S::DESC             => __('In this section you can customize the way your WordPress handles media.', 'italystrap'),
        S::FIELDS           => require 'fields-media.php', // Mandatory
    ],
];
