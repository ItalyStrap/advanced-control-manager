<?php

/**
 * Array definition for Posts default options
 *
 * @package ItalyStrap
 */

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

$config_posts = require(ITALYSTRAP_PLUGIN_PATH . 'config/posts.php');

foreach (
    [
        'show_cats',
        'show_tags',
        // 'cats', // Se disattivo queste ci sono problemi di visualizzazione del loop, eventualmente mettere una classe css hidden al field.
        // 'tags', // Se disattivo queste ci sono problemi di visualizzazione del loop, eventualmente mettere una classe css hidden al field.
        'related_by_tags',
        'related_by_cats',
        'exclude_current_post',
        'from_current_user',
        'most_viewed',
        'post_id',
    ] as $key => $value
) {
    unset($config_posts[ $value ]);
}

$config_grouped_posts = [
    /**
     * This is the container of the list of taxonomies, you can add any CSS class for styling it. (Example: <code>row</code>)
     */
    'tax_container_class'           => ['label'     => __('Taxonomies Container', 'italystrap'), 'desc'      => __('This is the container of the list of taxonomies, you can add any CSS class for styling it. (Example: <code>row</code>)', 'italystrap'), 'id'        => 'tax_container_class', 'type'      => 'text', 'class'     => 'widefat tax_container_class', 'class-p'   => 'tax_container_class', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
    /**
     * This is the container of the single taxonomy, you can add any CSS class for styling it. (Example: <code>col-md-6</code>)
     */
    'tax_class'                     => ['label'     => __('Taxonomy Container', 'italystrap'), 'desc'      => __('This is the container of the single taxonomy, you can add any CSS class for styling it. (Example: <code>col-md-6</code>)', 'italystrap'), 'id'        => 'tax_class', 'type'      => 'text', 'class'     => 'widefat tax_class', 'class-p'   => 'tax_class', 'default'   => '', 'validate'  => 'alpha_dash', 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
    /**
     * Insert comma/space-separated string of term ids to include. (Example: 1,2,3 or 1 2 3)
     */
    'include'               => [
        'label'     => __('Taxonomies ID', 'italystrap'),
        'desc'      => __('Insert comma/space-separated string of term ids to include. (Example: 1,2,3 or 1 2 3)', 'italystrap'),
        'id'        => 'include',
        'type'      => 'text',
        'class'     => 'widefat include',
        'default'   => '',
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'filter',
    ],
    /**
     * Insert comma/space-separated string of term ids to exclude. If $include is non-empty, $exclude is ignored. (Example: 1,2,3 or 1 2 3)
     */
    'exclude'               => [
        'label'     => __('Taxonomies ID to exclude', 'italystrap'),
        'desc'      => __('Insert comma/space-separated string of term ids to exclude. If $include is non-empty, $exclude is ignored. (Example: 1,2,3 or 1 2 3)', 'italystrap'),
        'id'        => 'exclude',
        'type'      => 'text',
        'class'     => 'widefat exclude',
        'default'   => '',
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'filter',
    ],
];

/**
 * Definition array() with all the options connected to the
 * module which must be called by an include (setoptions).
 */

return wp_parse_args($config_posts, $config_grouped_posts);
