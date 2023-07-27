<?php

/**
 * Template for Image Widget and Shortcode
 * If you want to get more image info you ca use thoose functions:
 * wp_get_attachment_metadata( $this->args['id'] );
 * get_post( $this->args['id'] );
 * wp_prepare_attachment_for_js( $this->args['id'] );
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core;

$output = '';

if (! empty($this->args['add_figure_container'])) {
    $attr = ['class' => 'widget-image-figure ' . esc_attr($this->args['container_css_class'])];
    $output .= sprintf('<figure %s>', get_attr('widget_image_figure', $attr));
}

if (! empty($this->args['link'])) {
    $link_attr = ['href' => esc_url($this->args['link'])];

    if (! empty($this->args['link_target_blank'])) {
        $link_attr['target'] = '_blank';
    }

    $output .= sprintf('<a %s>', get_attr('widget_image_href', $link_attr));
}

/**
 * Get the image
 */
$output .= $this->get_attachment_image();
$output .= $this->get_the_icon();

if (! empty($this->args['link'])) {
    $output .= '</a>';
}

/**
 * Get the title
 */
$output .= $this->get_the_title();

if (! empty($this->args['add_figure_container'])) {
    $output .= $this->get_the_caption();
    $output .= $this->get_the_description();

    $output .= '</figure>';
} else {
    /**
     * Get the description
     */
    $output .= $this->get_the_description();
}

echo $output;
