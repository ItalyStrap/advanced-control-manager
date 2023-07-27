<?php

declare(strict_types=1);

namespace ItalyStrap;

use ItalyStrap\Core;

$wp_upload_dir = \wp_upload_dir();

$beta = ' {{BETA VERSION}}';

return [
    [
        'label'         => __('Jpeg quality', 'italystrap'),
        'desc'          => __(
            'Select the jpeg quality for images. Best value: 100, worse value: 75, default: 82',
            'italystrap'
        ),
        'id'            => 'jpeg_quality',
        'type'          => 'number',
        'class'         => 'jpeg_quality easy',
        'value'         => 82,
//      'validate'      => 'is_numeric|is_int',
        'sanitize'      => 'sanitize_text_field',
        'option_type'   => 'theme_mod',
    ],
    [
        'label'         => __('Lazy Loading for images', 'italystrap'),
        'desc'          => __('Activate Lazy Loading for images', 'italystrap'),
        'id'            => 'lazyload',
        'type'          => 'checkbox',
        'class'         => 'lazyload easy',
        'value'         => '',
        'sanitize'      => 'sanitize_text_field',
        'option_type'   => 'theme_mod',
    ],
    [
        'label'         => __('Custom Placeholder', 'italystrap'),
        'desc'          => sprintf(
            __('Insert here your custom placeholder for image lazyloading from your media file,
			this is the src attribute of the img tag.
			%1$sExample: %2$s %1$sDefault (transparent): %3$s %1$sFor more info: %4$s', 'italystrap'),
            '<br>',
            ' <code>' . $wp_upload_dir['url'] . '/my-placeholder.gif</code>',
            '<code>data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7</code>',
            esc_url('http://clubmate.fi/base64-encoded-1px-gifs-black-gray-and-transparent/')
        ),
        'id'            => 'lazyload-custom-placeholder',
        'type'          => 'text',
        'class'         => 'lazyload-custom-placeholder medium',
        'placeholder'   => '',
        'value'         => 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
        'sanitize'      => 'sanitize_text_field',
        'option_type'   => 'theme_mod',
    ],
//  [
//      'label'         => __( 'Additional hooks to lazy load the content', 'italystrap' ),
//      'desc'          => __( 'Insert additional event name (hooks) in the textarea below
//      Put one event per line, if you need to specify a priority separate it with <code>|</code>, the dafult is 10.
//      Default arguments accepted are 3', 'italystrap' ),
//      'id'            => 'lazyload_additional_event_name',
//      'type'          => 'textarea',
//      'class'         => 'easy',
//      'placeholder'   => 'some-event-name|priority',
//      'sanitize'      => 'sanitize_textarea_field',
//      'option_type'   => 'theme_mod',
//      'show_on'       => Core\is_beta(),
//  ],
    [
        'label'         => __('Lazy Loading for embedded video', 'italystrap') . $beta,
        'desc'          => __(
            'Activate Lazy Loading for embedded video.
This only works for youtube video. The with of the container will be the max with of
<code>content_width</code> set in your theme. You can use the <code>lazyload-video-wrap</code>
CSS class for adding your own style.',
            'italystrap'
        ),
        'id'            => 'lazyload_video',
        'type'          => 'checkbox',
        'class'         => 'lazyload_video easy',
        'value'         => '',
        'sanitize'      => 'sanitize_text_field',
        'option_type'   => 'theme_mod',
        'show_on'       => Core\is_beta(),
    ],

];
