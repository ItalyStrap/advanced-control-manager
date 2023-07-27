<?php

/**
 * Array definition for Post_Title default options
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
     * Custom link for the widget title.
     */
    'class'             => [
        'label'     => __('Button css class', 'italystrap'),
        'desc'      => __('Customize the Button style with your custom css class. Example text-primary', 'italystrap'),
        'id'        => 'class',
        'type'      => 'text',
        'class'     => 'widefat class',
        'default'   => '',
        // 'validate'   => 'ctype_alpha',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Custom link for the widget title.
     */
    'id'                => [
        'label'     => __('Button css id', 'italystrap'),
        'desc'      => __('Useful if you want to use it with javascript. It has to be unique.', 'italystrap'),
        'id'        => 'id',
        'type'      => 'text',
        'class'     => 'widefat id',
        'default'   => '',
        // 'validate'   => 'ctype_alpha',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Custom link for the widget title.
     */
    'content'               => [
        'label'     => __('Button content', 'italystrap'),
        'desc'      => __('You can insert some content content the title.', 'italystrap'),
        'id'        => 'content',
        'type'      => 'text',
        'class'     => 'widefat content',
        'default'   => '',
        // 'validate'   => 'ctype_alpha',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
];
