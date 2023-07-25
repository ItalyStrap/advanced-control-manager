<?php

declare(strict_types=1);

namespace ItalyStrap;

return [
    [
        'label'         => __('Activate Custom CSS', 'italystrap'),
        'desc'          => __('This will add new fields in the wp editor for adding custom CSS, ID and class attribute to post/page and also it let you use the new functionality below.', 'italystrap'),
        'id'            => 'activate_custom_css',
        'type'          => 'checkbox',
        'class'         => 'activate_custom_css medium',
        'value'         => '',
        'sanitize'      => 'sanitize_text_field',
        'option_type'   => 'theme_mod',
    ],
    [
        'label'         => __('Custom CSS', 'italystrap'),
        'desc'          => __('Enter your custom CSS, this styles will be included verbatim in <style> tags in the <code>&lt;head&gt;</code> element of your html. The code will appear before styles that were registered individually and after your styles enqueued with the WordPress API.', 'italystrap'),
        'id'            => 'custom_css',
        'type'          => 'textarea',
        'class'         => 'custom_css medium',
        'rows'          => 20,
        'cols'          => 100,
        'placeholder'   => '.my_css{color:#fff;}',
        'value'         => '',
        'sanitize'      => 'strip_tags|trim', // Default: strip_tags
        'option_type'   => 'theme_mod',
    ],
    [
        'label'         => __('Body Class', 'italystrap'),
        'desc'          => __('This will add a CSS class to <code>body_class</code> filter in every page.', 'italystrap'),
        'id'            => 'body_class',
        'type'          => 'text',
        'class'         => 'body_class medium',
        'placeholder'   => '',
        'value'         => '',
        'sanitize'      => 'sanitize_text_field',
        'option_type'   => 'theme_mod',
    ],
    [
        'label'         => __('Post Class', 'italystrap'),
        'desc'          => __('This will add a CSS class to <code>post_class</code> filter in every page.', 'italystrap'),
        'id'            => 'post_class',
        'type'          => 'text',
        'class'         => 'post_class medium',
        'placeholder'   => '',
        'value'         => '',
        'sanitize'      => 'sanitize_text_field',
        'option_type'   => 'theme_mod',
    ],
];
