<?php

/**
 * Array definition for carousel default options
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
     * Text below the image.
     */
    'text'              => [
        'label'     => __('Text of the image', 'italystrap'),
        'desc'      => __('Enter the text of the image.', 'italystrap'),
        'id'        => 'text',
        'type'      => 'textarea',
        'class'     => 'widefat',
        'default'   => false,
        // 'validate'   => 'alpha_dash',
        'sanitize'  => 'sanitize_text_field',
    ],
];
