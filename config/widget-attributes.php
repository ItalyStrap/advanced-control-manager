<?php

/**
 * Array definition for advanced text default options
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
     * This is the HTML container of the widget. Enter the CSS ID for this container. (Example: <code>col-md-12</code>)
     */
    'widget_css_id'             => [
        'label'     => __('Widget CSS ID', 'italystrap'),
        'desc'      => __('This is the HTML container of the widget. Enter the CSS ID for this container. (Example: <code>my-ID</code>)', 'italystrap'),
        'id'        => 'widget_css_id',
        'type'      => 'text',
        'class'     => 'widefat widget_css_id',
        'default'   => false,
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * This is the HTML container of the widget. Enter the CSS class or classes for this container. (Example: <code>col-md-12</code>)
     */
    'widget_css_class'              => [
        'label'     => __('Widget CSS class', 'italystrap'),
        'desc'      => __('This is the HTML container of the widget. Enter the CSS class or classes for this container. (Example: <code>col-md-12</code>)', 'italystrap'),
        'id'        => 'widget_css_class',
        'type'      => 'text',
        'class'     => 'widefat widget_css_class',
        'default'   => false,
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
];
