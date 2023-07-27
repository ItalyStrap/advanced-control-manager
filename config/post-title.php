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
    'html_tag'              => [
        'label'     => __('Post title html tag', 'italystrap'),
        'desc'      => __('You can change the tag wrapper for the post title, Default h2.', 'italystrap'),
        'id'        => 'html_tag',
        'type'      => 'text',
        'class'     => 'widefat html_tag',
        'default'   => 'h2',
        // 'validate'   => 'ctype_alpha',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Custom link for the widget title.
     */
    'class'             => [
        'label'     => __('Post title css class', 'italystrap'),
        'desc'      => __('Customize the post title style with your custom css class. Example text-primary', 'italystrap'),
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
        'label'     => __('Post title css id', 'italystrap'),
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
    'style'             => [
        'label'     => __('Post title custom css style', 'italystrap'),
        'desc'      => __('You can add some css style. Eg: style="color: #000000;"', 'italystrap'),
        'id'        => 'style',
        'type'      => 'text',
        'class'     => 'widefat style',
        'default'   => '',
        // 'validate'   => 'ctype_alpha',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Custom link for the widget title.
     */
    'post_id'               => [
        'label'     => __('Post title post_id', 'italystrap'),
        'desc'      => __('Do you want to display the title of a certain post? Then insert the post ID of that post.', 'italystrap'),
        'id'        => 'post_id',
        'type'      => 'number',
        'class'     => 'widefat post_id',
        'default'   => null,
        // 'validate'   => 'ctype_alpha',
        'sanitize'  => 'absint',
        'section'   => 'general',
    ],
    /**
     * Custom link for the widget title.
     */
    'before'                => [
        'label'     => __('Post title before', 'italystrap'),
        'desc'      => __('You can insert some content before the title.', 'italystrap'),
        'id'        => 'before',
        'type'      => 'text',
        'class'     => 'widefat before',
        'default'   => '',
        // 'validate'   => 'ctype_alpha',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Custom link for the widget title.
     */
    'after'             => [
        'label'     => __('Post title after', 'italystrap'),
        'desc'      => __('You can insert some content after the title.', 'italystrap'),
        'id'        => 'after',
        'type'      => 'text',
        'class'     => 'widefat after',
        'default'   => '',
        // 'validate'   => 'ctype_alpha',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
];
