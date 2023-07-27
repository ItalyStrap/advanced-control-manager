<?php

declare(strict_types=1);

namespace ItalyStrap;

return [
    [
        'label'         => __('Activate excerpt more mods', 'italystrap'),
        'desc'          => __('Activate the excerpt more mods for this theme. This functionality will be saved to the <code>theme_mods();</code>. It also works for the Widget Post if you select "Use global readmore" in the widget itself.', 'italystrap'),
        'id'            => 'activate_excerpt_more_mods',
        'type'          => 'checkbox',
        'class'         => 'activate_excerpt_more_mods easy',
        'default'       => '',
        'sanitize'      => 'sanitize_text_field',
        'option_type'   => 'theme_mod',
    ],
    [
        'label'         => __('Read more link text', 'italystrap'),
        'desc'          => __('Customize the read more link pattern. Default: <code>&hellip;Read more</code>', 'italystrap'),
        'id'            => 'read_more_link_text',
        'type'          => 'text',
        'class'         => 'read_more_link_text easy',
        'default'       => __('&hellip;Read more', 'italystrap'),
//      'validate'      => 'ctype_alpha',
        'sanitize'      => 'sanitize_text_field',
        'translate'     => true,
        'option_type'   => 'theme_mod',
    ],
    [
        'label'         => __('Read more CSS class', 'italystrap'),
        'desc'          => __('Customize the style of the read more link with your custom CSS class. Default: <code>read-more</code>. Example: <code>read-more btn btn-primary btn-lg</code>.', 'italystrap'),
        'id'            => 'read_more_class',
        'type'          => 'text',
        'class'         => 'read_more_class easy',
        'default'       => 'read-more',
//      'validate'      => 'ctype_alpha',
        'sanitize'      => 'sanitize_text_field',
        'option_type'   => 'theme_mod',
    ],
    [
        'label'         => __('Auto-generated Excerpt length', 'italystrap'),
        'desc'          => __('The default length for auto-generated excerpts is 55 words. It works only if the excerpt field is empty.', 'italystrap'),
        'id'            => 'excerpt_length',
        'type'          => 'number',
        'class'         => 'excerpt_length easy',
        'default'       => '55',
//      'validate'      => 'is_numeric|is_int',
        'sanitize'      => 'sanitize_text_field',
        'option_type'   => 'theme_mod',
    ],
    [
        'label'         => __('Auto-generated Excerpt ends with punctuation', 'italystrap'),
        'desc'          => __('Auto-generated Excerpt ends with punctuation (by default . or ? or !). It works only if the excerpt field is empty.', 'italystrap'),
        'id'            => 'end_with_punctuation',
        'type'          => 'checkbox',
        'class'         => 'end_with_punctuation easy',
        'default'       => '',
        'sanitize'      => 'sanitize_text_field',
        'option_type'   => 'theme_mod',
    ],
    [
        'label'         => __('Read more position', 'italystrap'),
        'desc'          => __('Select the position to display read more link. With <code>Append</code> the read more link will be appended just after the text inside the HTML paragraph, with <code>After</code> it will be added after and outside the last HTML paragraph on a new line, this is useful if you add a button style to the "Read more".', 'italystrap'),
        'id'            => 'read_more_position',
        'type'          => 'select',
        'options'       => [
            'append'    => __('Append to the excerpt', 'italystrap'),
            'after'     => __('After the excerpt', 'italystrap'),
        ],
        'class'         => 'read_more_position easy',
        'default'       => 'append',
        'sanitize'      => 'sanitize_text_field',
    ],
];
