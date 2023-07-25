<?php

/**
 * Custom script and style array configuration for metaboxes
 *
 * @package ItalyStrap
 */

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

$script_settings_metabox_object_types = apply_filters(
    'italystrap_script_settings_metabox_object_types',
    array( 'post', 'page' )
);

/**
 * @todo Aggiungere selezione di script o stili già registrati che
 *       si vuole aggiungere al post o pagine e anche globalmente
 *       global $wp_scripts, $wp_styles;
 */

return array(
    'id'            => $this->prefix . '-script-settings-metabox',
    'title'         => __('CSS settings', 'italystrap'),
    // 'title'          => __( 'CSS and JavaScript settings', 'italystrap' ),
    'object_types'  => $script_settings_metabox_object_types,
    'context'       => 'normal',
    'priority'      => 'high',
    // 'fields'     => $fields,
    'fields'        => array(
        'custom_css_settings'       => array(
            'name'              => __('Custom CSS', 'italystrap'),
            'desc'              => __('This code will be included verbatim in style tag before </head> tag of your page or post', 'italystrap'),
            'id'                => $this->_prefix . '_custom_css_settings',
            'type'              => 'textarea_code',
            'options'           => [
                'disable_codemirror' => true
            ],
            'attributes'        => [
                'placeholder' => 'body{background-color:#f2f2f2}',
            ],
        ),
        'custom_body_class_settings' => array(
            'name'              => __('Body Classes', 'italystrap'),
            'desc'              => __('These class names will be added to the body_class() function (provided your theme uses these functions), separate each one by comma.', 'italystrap'),
            'id'                => $this->_prefix . '_custom_body_class_settings',
            'type'              => 'text',
            'attributes'        => array(
                'placeholder' => 'class1,class2,class3,otherclass'
            ),
        ),
        'custom_post_class_settings' => array(
            'name'              => __('Post Classes', 'italystrap'),
            'desc'              => __('These class names will be added to the post_class() function (provided your theme uses these functions), separate each one by comma.', 'italystrap'),
            'id'                => $this->_prefix . '_custom_post_class_settings',
            'type'              => 'text',
            'attributes'        => array(
                'placeholder' => 'class1,class2,class3,otherclass'
            ),
        ),
    ),
);
