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
     * Ids for the images to use.
     */
    'ids'               => [
        'label'     => __('Images ID', 'italystrap'),
        'desc'      => __('Enter the media or post type ID.', 'italystrap'),
        'id'        => 'ids',
        'type'      => 'media_list',
        'class'     => 'widefat ids',
        'default'   => false,
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
];
