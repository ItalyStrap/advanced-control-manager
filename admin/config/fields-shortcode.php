<?php

declare(strict_types=1);

namespace ItalyStrap;

use ItalyStrap\Core;

$beta = ' {{BETA VERSION}}';

return [
    [
        'label'         => __('Do ShortCode in Widget Text', 'italystrap'),
        'desc'          => __('This get you the possibility to insert any shortcode into a widget text.', 'italystrap'),
        'id'            => 'do_shortcode_widget_text',
        'type'          => 'checkbox',
        'class'         => 'do_shortcode_widget_text easy',
        'default'       => '',
        'sanitize'      => 'sanitize_text_field',
    ],
    [
        'label'         => __('Shortcode for Media Carousel', 'italystrap'),
        'desc'          => __('This will activate a Bootstrap media Carousel inside built-in WordPress gallery shortcode with a ton of options, make shure you have a Twitter Bootstrap CSS in your site.', 'italystrap'),
        'id'            => 'media_carousel_shortcode',
        'type'          => 'checkbox',
        'class'         => 'media_carousel_shortcode medium',
        'default'       => '',
        'sanitize'      => 'sanitize_text_field',
    ],
    [
        'label'         => __('Shortcode for custom loop of posts , pages and custom post types', 'italystrap') . $beta,
        'desc'          => __('This shortcode allow you to create a custom loop of posts/page/CPT with a lot of options like "Recent Posts" or "Posts more comented" or "Related posts" ecc and place it in your sidebars. (This plugin does not provide any style, you have to add it in the style.css of your theme).', 'italystrap'),
        'id'            => 'shortcode_posts',
        'type'          => 'checkbox',
        'class'         => 'shortcode_posts medium',
        'sanitize'      => 'sanitize_text_field',
        'show_on'       => Core\is_beta(),
    ],
    [
        'label'         => __('Shortcode for display post, page or CPT title', 'italystrap'),
        'desc'          => __('This shortcode allow you to display the title of the post you want to display, If you want to display the title in a different page you have to insert the ID of the post you want to display.', 'italystrap'),
        'id'            => 'shortcode_post_title',
        'type'          => 'checkbox',
        'class'         => 'shortcode_post_title medium',
        'sanitize'      => 'sanitize_text_field',
    ],
];
