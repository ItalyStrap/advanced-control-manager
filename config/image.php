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
 * Instance of list of image sizes
 *
 * @var ItalyStrapAdminMediaSettings
 */
// $image_size_media = new ItalyStrapAdminMediaSettings;
$image_size_media = new \ItalyStrap\Image\Size();
$image_size_media_array = $image_size_media->get_image_sizes();

/**
 * Definition array() with all the options connected to the
 * module which must be called by an include (setoptions).
 */
return [
    /**
     * Enter a font icon css class. Example:<code>fa fa-wordpress</code>. This works only if you have font icons loaded with your theme. If you use an icon the image will not be loaded.
     */
    'icon'              => [
        'label'     => __('Font icon', 'italystrap'),
        'desc'      => __('Enter a font icon css class. Example:<code>fa fa-wordpress</code>. This works only if you have font icons loaded with your theme. If you use an icon the image will not be loaded.', 'italystrap'),
        'id'        => 'icon',
        'type'      => 'text',
        'class'     => 'widefat icon',
        // 'class-p'    => 'hidden',
        'default'   => false,
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'icon',
    ],
    /**
     * Enter the media or post type ID or select an image above.
     */
    'id'                => [
        'label'     => __('Enter Images ID', 'italystrap'),
        'desc'      => __('Enter the media or post type ID or select an image above.', 'italystrap'),
        'id'        => 'id',
        'type'      => 'media',
        'class'     => 'widefat ids',
        // 'class-p'    => 'hidden',
        'default'   => false,
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'image',
    ],
    /**
     * Enter a title. this will be visualized below the image. (This is not the widget title).
     */
    'image_title'           => [
        'label'     => __('Title of the image', 'italystrap'),
        'desc'      => __('Enter a title. this will be visualized below the image. (This is not the widget title).', 'italystrap'),
        'id'        => 'image_title',
        'type'      => 'text',
        'class'     => 'widefat image_title',
        'default'   => '',
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Define tag for the title. Default: h4. (This is not the widget title).
     */
    'image_title_tag'           => [
        'label'     => __('Title tag', 'italystrap'),
        'desc'      => __('Define tag for the title. Default: h4. (This is not the widget title).', 'italystrap'),
        'id'        => 'image_title_tag',
        'type'      => 'text',
        'class'     => 'widefat',
        'default'   => 'h4',
        // 'validate'   => 'esc_attr',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Enter a CSS class for the title. Default: widget-image-title. (This is not the widget title).
     */
    'image_title_class'         => [
        'label'     => __('Title CSS class', 'italystrap'),
        'desc'      => __('Enter a CSS class for the title. Default: widget-image-title. (This is not the widget title).', 'italystrap'),
        'id'        => 'image_title_class',
        'type'      => 'text',
        'class'     => 'widefat',
        'default'   => 'widget-image-title',
        // 'validate'   => 'esc_attr',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'style',
    ],
    /**
     * Enter a caption for the image
     */
    'caption'           => [
        'label'     => __('Image caption', 'italystrap'),
        'desc'      => __('Enter a caption for the image', 'italystrap'),
        'id'        => 'caption',
        'type'      => 'textarea',
        'class'     => 'widefat caption',
        'default'   => '',
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'image',
    ],
    /**
     * Enter a description for the image. You can also insert a shortcode.
     */
    'description'       => [
        'label'     => __('Image description', 'italystrap'),
        'desc'      => __('Enter a description for the image. You can also insert a <code>[shortcode]</code> or <code>HTML tags</code>. To use shortcodes you also have to check "Doo Shortcode" below,', 'italystrap'),
        'id'        => 'description',
        'type'      => 'textarea',
        'class'     => 'widefat description',
        'default'   => '',
        // 'validate'   => 'numeric_comma',
        'capability' => current_user_can('unfiltered_html'),
        'sanitize'  => 'wp_kses_post|trim',
        'section'   => 'general',
    ],
    /**
     * Automatically add paragraphs.
     */
    'wpautop'       => ['label'     => __('Add paragraphs', 'italystrap'), 'desc'      => __('Automatically add paragraphs.', 'italystrap'), 'id'        => 'wpautop', 'type'      => 'checkbox', 'class'     => 'wpautop', 'default'   => 0, 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
    /**
     * Do shortcode in image description.
     */
    'do_shortcode'      => ['label'     => __('Do Shortcode', 'italystrap'), 'desc'      => __('Do shortcode in image description.', 'italystrap'), 'id'        => 'do_shortcode', 'type'      => 'checkbox', 'class'     => 'do_shortcode', 'default'   => 0, 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
    /**
     * Size for image attachment. Accepted values: thumbnail, medium,
     * large, full or own custom name added in add_image_size function.
     * Default: full.
     *
     * @see wp_get_attachment_image_src() for further reference.
     */
    'size'              => ['label'     => __('Image size', 'italystrap'), 'desc'      => __('Size for image attachment. Accepted values: thumbnail, medium, large, full or own custom name added in add_image_size function. Default: full.', 'italystrap'), 'id'        => 'size', 'type'      => 'select', 'class'     => 'widefat', 'default'   => 'full', 'options'   => $image_size_media_array ?? '', 'sanitize'  => 'sanitize_text_field', 'section'   => 'image'],
    /**
     * Alignment for image.
     */
    'alignment'             => ['label'     => __('Alignment', 'italystrap'), 'desc'      => __('Alignment for image.', 'italystrap'), 'id'        => 'alignment', 'type'      => 'select', 'class'     => 'widefat', 'default'   => 'none', 'options'   => ['alignnone'     => __('None', 'italystrap'), 'aligncenter'   => __('Center', 'italystrap'), 'alignleft'     => __('Left', 'italystrap'), 'alignright'    => __('Right', 'italystrap')], 'sanitize'  => 'sanitize_text_field', 'section'   => 'style'],
    /**
     * Check if you want to add <code>&lt;figure&gt;&lt;/figure&gt;</code> tag for img container.
     */
    'add_figure_container'      => [
        'label'     => esc_html__('Add container "figure"', 'italystrap'),
        'desc'      => __('Check if you want to add <code>&lt;figure&gt;&lt;/figure&gt;</code> tag for img container.', 'italystrap'),
        'id'        => 'add_figure_container',
        'type'      => 'checkbox',
        'class'     => 'add_figure_container',
        'default'   => 1,
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'style',
    ],
    /**
     * Enter the Container CSS class (optional).
     */
    'container_css_class'       => [
        'label'     => __('Container CSS class', 'italystrap'),
        'desc'      => __('Enter the Container CSS class (optional).', 'italystrap'),
        'id'        => 'container_css_class',
        'type'      => 'text',
        'class'     => 'widefat container_css_class',
        'default'   => '',
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'style',
    ],
    /**
     * Enter the image css class (optional).
     */
    'image_css_class'       => [
        'label'     => __('Image CSS class', 'italystrap'),
        'desc'      => __('Enter the image css class (optional).', 'italystrap'),
        'id'        => 'image_css_class',
        'type'      => 'text',
        'class'     => 'widefat image_css_class',
        'default'   => '',
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'style',
    ],
    /**
     * Insert a URL for the image.
     */
    'link'      => [
        'label'     => __('Link URL', 'italystrap'),
        'desc'      => __('Insert a URL for the image.', 'italystrap'),
        'id'        => 'link',
        'type'      => 'text',
        'class'     => 'widefat link',
        'default'   => '',
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Check if you want to open in a new tab/windows.
     */
    'link_target_blank'     => [
        'label'     => __('Open in a new windows', 'italystrap'),
        'desc'      => __('Check if you want to open in a new tab/windows.', 'italystrap'),
        'id'        => 'link_target_blank',
        'type'      => 'checkbox',
        'class'     => 'link_target_blank',
        'default'   => '',
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
];
