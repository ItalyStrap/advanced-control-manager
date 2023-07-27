<?php

/**
 * Settings for Facebook Page API
 *
 * Settings and default value for Facebook Page API, Widget and shortcode.
 *
 * @link italystrap.com
 * @since 2.2.1
 *
 * @package ItalyStrap
 */

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

/**
 * Definition array() with all the options connected to the
 * module which must be called by an include (setoptions).
 */
return [
    /**
     * Page Name or Page URL.
     */
    'href'              => [
        'label'     => __('Page Name or Page URL', 'italystrap'),
        'desc'      => __('Enter your FB page name or the url of your FB page.', 'italystrap'),
        'id'        => 'href',
        'type'      => 'text',
        'class'     => 'widefat href',
        // 'class-p'    => 'href',
        'default'   => '',
        'placeholder'   => 'your-page',
        // 'validate'   => 'alpha_dash',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Max width of widget.
     */
    'width'             => [
        'label'     => __('Max width of widget', 'italystrap'),
        'desc'      => __('Enter the max width of the FB page.', 'italystrap'),
        'id'        => 'width',
        'type'      => 'number',
        'class'     => 'widefat width',
        // 'class-p'    => 'width',
        'default'   => '380',
        'validate'  => 'is_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Height of widget.
     */
    'height'                => [
        'label'     => __('Height of widget', 'italystrap'),
        'desc'      => __('Enter the max height of the FB page.', 'italystrap'),
        'id'        => 'height',
        'type'      => 'number',
        'class'     => 'widefat height',
        // 'class-p'    => 'height',
        'default'   => '214',
        'validate'  => 'is_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Tab to be visualized.
     */
    'tabs'              => [
        'label'     => __('Tab to be visualized', 'italystrap'),
        'desc'      => __('Select the tab you want to be visualized in your FB widget. Use ctrl + click for multiple selection.', 'italystrap'),
        'id'        => 'tabs',
        'type'      => 'multiple_select',
        'class'     => 'widefat tabs',
        // 'class-p'    => 'tabs',
        'default'   => 'timeline',
        'options'   => ['timeline'      => __('Timeline (Default)', 'italystrap'), 'events'        => __('Events', 'italystrap'), 'messages'      => __('Messages', 'italystrap')],
        // 'validate'   => 'is_numeric',
        'sanitize'  => 'sanitize_select_multiple',
        'section'   => 'general',
    ],
    /**
     * Hide Cover.
     */
    'hide-cover'        => [
        'label'     => __('Hide Cover', 'italystrap'),
        'desc'      => __('Hide the cover of your FB page.', 'italystrap'),
        'id'        => 'hide-cover',
        'type'      => 'select',
        'class'     => 'widefat hide-cover',
        // 'class-p'    => 'hide-cover',
        'default'   => 'false',
        'options'   => ['true'      => __('True', 'italystrap'), 'false'     => __('False', 'italystrap')],
        // 'validate'   => 'is_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Show facepile.
     */
    'show-facepile'     => [
        'label'     => __('Show facepile', 'italystrap'),
        'desc'      => __('Show the profile avatar when click on like button.', 'italystrap'),
        'id'        => 'show-facepile',
        'type'      => 'select',
        'class'     => 'widefat show-facepile',
        // 'class-p'    => 'show-facepile',
        'default'   => 'true',
        'options'   => ['true'      => __('True', 'italystrap'), 'false'     => __('False', 'italystrap')],
        // 'validate'   => 'is_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Hide CTA.
     */
    'hide-cta'      => [
        'label'     => __('Hide CTA', 'italystrap'),
        'desc'      => __('Hide the CTA button if available.', 'italystrap'),
        'id'        => 'hide-cta',
        'type'      => 'select',
        'class'     => 'widefat hide-cta',
        // 'class-p'    => 'hide-cta',
        'default'   => 'false',
        'options'   => ['true'      => __('True', 'italystrap'), 'false'     => __('False', 'italystrap')],
        // 'validate'   => 'is_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Small header.
     */
    'small-header'      => [
        'label'     => __('Small header', 'italystrap'),
        'desc'      => __('Use the small header.', 'italystrap'),
        'id'        => 'small-header',
        'type'      => 'select',
        'class'     => 'widefat small-header',
        // 'class-p'    => 'small-header',
        'default'   => 'false',
        'options'   => ['true'      => __('True', 'italystrap'), 'false'     => __('False', 'italystrap')],
        // 'validate'   => 'is_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Adapt container width.
     */
    'adapt-container-width'     => [
        'label'     => __('Adapt container width', 'italystrap'),
        'desc'      => __('Try to adapt to the width of the container.', 'italystrap'),
        'id'        => 'adapt-container-width',
        'type'      => 'select',
        'class'     => 'widefat adapt-container-width',
        // 'class-p'    => 'adapt-container-width',
        'default'   => 'true',
        'options'   => ['true'      => __('True', 'italystrap'), 'false'     => __('False', 'italystrap')],
        // 'validate'   => 'is_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Align of content.
     */
    'align'     => [
        'label'     => __('Align of content', 'italystrap'),
        'desc'      => __('Select the align of the widget.', 'italystrap'),
        'id'        => 'align',
        'type'      => 'select',
        'class'     => 'widefat align',
        // 'class-p'    => 'align',
        'default'   => 'none',
        'options'   => ['none'      => __('None', 'italystrap'), 'left'      => __('Left', 'italystrap'), 'center'    => __('Center', 'italystrap'), 'right'     => __('Right', 'italystrap')],
        // 'validate'   => 'is_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
];
