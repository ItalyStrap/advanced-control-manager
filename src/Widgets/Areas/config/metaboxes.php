<?php

/**
 * Configuration file for Widget Areas
 *
 * [Long Description.]
 *
 * @link http://italystrap.com
 * @since 2.5.1
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Widgets\Areas;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

$position = apply_filters('italystrap_theme_positions', []);

return ['id'            => $this->prefix . '-widget-areas-metabox', 'title'         => __('Widget Area settings', 'italystrap'), 'object_types'  => ['sidebar'], 'context'       => 'normal', 'priority'      => 'low', 'fields'        => [
    'action'                    => ['name'              => __('Display on', 'italystrap'), 'desc'              => __('Select the position to display this widget area.', 'italystrap'), 'id'                => $this->_prefix . '_action', 'type'              => 'select', 'show_option_none'  => true, 'default'           => '', 'sanitize'          => 'sanitize_text_field', 'options'           => $position, 'show_on'           => (bool) ! empty($position)],
    'priority'                  => ['name'              => __('Priority', 'italystrap'), 'desc'              => __('Type the priority you want to display this widget area, it must be a number, by default the priority is 10, you can choose a number between 1 and 99999, this is useful when you want to display more than one widget area in the same position but in different order.', 'italystrap'), 'id'                => $this->_prefix . '_priority', 'type'              => 'text', 'default'           => 10, 'sanitize'          => 'absint', 'show_on'           => (bool) ! empty($position)],
    'container_width'           => ['name'              => __('Width', 'italystrap'), 'desc'              => __('Select the width of this widget area.', 'italystrap'), 'id'                => $this->_prefix . '_container_width', 'type'              => 'select', 'show_option_none'  => sprintf(
        __('Default width set in %s', 'italystrap'),
        ''
    ), 'default'           => 'container', 'sanitize'          => 'sanitize_text_field', 'options'           => apply_filters('italystrap_theme_width', []), 'attributes'        => ['placeholder' => '']],
    'background_color'          => ['name'      => __('Background color', 'italystrap'), 'desc'      => __('Choose the color for the background of the widget area.', 'italystrap'), 'id'        => $this->_prefix . '_background_color', 'type'      => 'colorpicker', 'default'   => '', 'sanitize'  => 'sanitize_hex_color'],
    'background_image'          => [
        'name'      => __('Add background image', 'italystrap'),
        'desc'      => __('Upload an image.', 'itallystrap'),
        'id'        => $this->_prefix . '_background_image',
        'type'      => 'file',
        'default'   => null,
        'sanitize'  => 'sanitize_text_field',
        // Optional:
        'options'   => ['url'   => false],
        'text'      => ['add_upload_file_text' => __('Select image', 'italystrap')],
    ],
    'background_overlay_color'          => ['name'      => __('Background overlay color', 'italystrap'), 'desc'      => __('Choose the color for the background overlay of the widget area.', 'italystrap'), 'id'        => $this->_prefix . '_background_overlay_color', 'type'      => 'colorpicker', 'default'   => '', 'sanitize'  => 'sanitize_hex_color'],
    /**
     * numeric CMB2 fields
     * @link https://gist.github.com/jtsternberg/c09f5deb7d818d0d170b
     */
    'background_overlay_color_opacity'          => ['name'      => __('Background overlay color opacity', 'italystrap'), 'desc'      => __('Choose the background overlay opacity of the widget area.', 'italystrap'), 'id'        => $this->_prefix . '_background_overlay_color_opacity', 'type'      => 'text', 'attributes' => ['type'      => 'number', 'pattern'   => '\d*'], 'default'   => '', 'sanitize'  => 'sanitize_text_field'],
    'widget_area_class'         => ['name'      => __('Widget Area Class', 'italystrap'), 'desc'      => __('Custom CSS class selector for the Widget Area container.', 'itallystrap'), 'id'        => $this->_prefix . '_widget_area_class', 'type'      => 'text', 'default'   => '', 'sanitize'  => 'sanitize_text_field'],
    'widget_before_after'       => ['name'      => __('Before/After Widget', 'italystrap'), 'desc'      => __('This is the HTML tag for the Widgets added in this Widget Area/Sidebar. Default: div', 'itallystrap'), 'id'        => $this->_prefix . '_widget_before_after', 'type'      => 'text', 'default'   => 'div', 'sanitize'  => 'sanitize_text_field'],
    'widget_title_before_after' => ['name'      => __('Before/After Widget Title', 'italystrap'), 'desc'      => __('This is the HTML tag for the Widget Title added in this Widget Area/Sidebar. Default: h3', 'itallystrap'), 'id'        => $this->_prefix . '_widget_title_before_after', 'type'      => 'text', 'default'   => 'h3', 'sanitize'  => 'sanitize_text_field'],
    'widget_title_class'        => ['name'      => __('Widget Title Class', 'italystrap'), 'desc'      => __('Custom CSS class selector for the Widget Title.', 'itallystrap'), 'id'        => $this->_prefix . '_widget_title_class', 'type'      => 'text', 'default'   => '', 'sanitize'  => 'sanitize_text_field'],
]];
