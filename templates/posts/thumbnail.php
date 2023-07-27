<?php

/**
 * The template for displaying thumbnail
 *
 * @package ItalyStrap
 * @since 1.0.0
 */

namespace ItalyStrap\Core;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

if (empty($this->config['show_thumbnail'])) {
    return;
}

/**
 * Thumbnail size
 *
 * @var string
 */
$thumb_size = $this->config['thumb_size'];

if (0 === $this->query->current_post && $this->config['show_first_thumbnail_bigger']) {
    $thumb_size = $this->config['first_thumb_size'];
}

$post_ID = $this->query->post->ID;

$get_thumb_attr = ['itemprop'  => 'image', 'class'     => sprintf(
    'attachment-%1$s size-%1$s %2$s',
    $thumb_size,
    $this->config['image_class']
)];

if (has_post_thumbnail($post_ID)) : ?>
    <figure class="entry-image">
        <?php
        $get_thumb_attr['alt'] = trim(strip_tags(get_post_meta(get_post_thumbnail_id($post_ID), '_wp_attachment_image_alt', true)));

        printf(
            '%1$s%2$s%3$s',
            empty($this->config['add_permalink_wrapper']) ? '' : sprintf(
                '<a href="%s" rel="bookmark">',
                get_the_permalink()
            ),
            get_the_post_thumbnail($post_ID, $thumb_size, $get_thumb_attr),
            empty($this->config['add_permalink_wrapper']) ? '' : '</a>'
        ); ?>
    </figure>
<?php elseif ($this->config['thumb_id']) :?>
    <figure class="entry-image">
        <?php
        $get_thumb_attr['alt'] = trim(strip_tags(get_post_meta($this->config['thumb_id'], '_wp_attachment_image_alt', true)));
        $the_post_thumbnail = wp_get_attachment_image($this->config['thumb_id'], $thumb_size, false, $get_thumb_attr);

        printf(
            '%1$s%2$s%3$s',
            empty($this->config['add_permalink_wrapper']) ? '' : sprintf(
                '<a href="%s" rel="bookmark">',
                get_the_permalink()
            ),
            apply_filters('italystrap_widget_the_post_thumbnail', $the_post_thumbnail),
            empty($this->config['add_permalink_wrapper']) ? '' : '</a>'
        ); ?>
    </figure>
<?php endif; ?>
