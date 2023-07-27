<?php

/**
 * Array definition for Image widget and shortcode
 *
 * @package ItalyStrap
 */

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

/**
 * Get menus
 *
 * @var array
 */
$menus = wp_get_nav_menus();
$registered_menu = ['0' => __('&mdash; Select &mdash;')];
foreach ($menus as $menu) {
    $registered_menu[ $menu->term_id ] = $menu->name;
}

/**
 * Definition array() with all the options connected to the
 * module which must be called by an include (setoptions).
 */
return [
    /**
     * Enter a font icon css class. Example:<code>fa fa-wordpress</code>. This works only if you have font icons loaded with your theme. If you use an icon the image will not be loaded.
     */
    'nav_menu'              => [
        'label'     => __('Select Menu', 'italystrap'),
        'desc'      => __('.', 'italystrap'),
        'id'        => 'nav_menu',
        'type'      => 'select',
        'class'     => 'widefat nav_menu',
        // 'class-p'    => 'hidden',
        'default'   => false,
        'options'   => $registered_menu,
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
];
