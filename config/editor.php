<?php

/**
 * Array definition for editor default options
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
     * Arbitrary text or HTML advanced editor WYSIWYG.
     */
    'text'              => [
        'label'     => __('WP Editor', 'italystrap'),
        'desc'      => __('Arbitrary text or HTML advanced editor WYSIWYG.', 'italystrap'),
        'id'        => 'text',
        'type'      => 'editor',
        'class'     => 'widefat text',
        'default'   => '',
        // 'validate'   => 'numeric_comma',
        'capability' => current_user_can('unfiltered_html'),
        'sanitize'  => 'wp_kses_post|trim',
        'section'   => 'general',
    ],
];
