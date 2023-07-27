<?php

/**
 * The template used for displaying the title.
 *
 * This file is still in BETA version.
 *
 * @package ItalyStrap
 * @since 1.0.0
 * @since 4.0.0 Code refactoring.
 */

namespace ItalyStrap;

if (! $this->config['show_title']) {
    return;
}

$args = ['before'    => sprintf(
    '<%1$s class="entry-title">%2$s<span itemprop="name">',
    esc_attr($this->config['entry_title']),
    empty($this->config['add_permalink_wrapper']) ? '' : sprintf(
        '<a itemprop="url" href="%1$s" title="%2$s" rel="bookmark">',
        esc_url(get_permalink()),
        the_title_attribute('echo=0')
    )
), 'after'     => sprintf(
    '</span>%2$s</%1$s>',
    esc_attr($this->config['entry_title']),
    empty($this->config['add_permalink_wrapper']) ? '' : '</a>'
)];
the_title($args['before'], $args['after']);
