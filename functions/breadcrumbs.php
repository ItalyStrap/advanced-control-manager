<?php

/**
 * Breadcrumbs function and hook
 *
 * @package ItalyStrap
 * @since   2.0.0
 */

namespace ItalyStrap\Core;

use ItalyStrap\HTML;

/**
 * Get the Breadcrumbs
 *
 * @param  array  $args The breadcrumbs arguments.
 *                      @see class Breadcrumbs for more info.
 * @return string       Return the breadcrumbs html.
 */
function get_breadcrumbs(array $args = [])
{

    /**
     * Default argument for method get_the_breadcrumbs()
     *
     * @var string
     * @uses wp_parse_args() Parsifica $args in entrata in un array e lo combina con l'array di default
     * @link http://codex.wordpress.org/it:Riferimento_funzioni/wp_parse_args
     */
    $deprecated = ['open_wrapper'          => '<ol class="breadcrumb"  itemscope itemtype="https://schema.org/BreadcrumbList">', 'closed_wrapper'        => '</ol>', 'before_element'        => '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">', 'before_element_active' => '<li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">', 'after_element'         => '</li>', 'wrapper_name'          => '<span itemprop="name">', 'close_wrapper_name'    => '</span>'];

    $args['bloginfo_name'] = \get_option('blogname');
    $args['home_url'] = \get_home_url(null, '/');
    $args['separator'] = false;

    // $args['show_on_front'] = false;

    /**
     * Back compat with the old home argument
     */
    if (isset($args['home']) && \ItalyStrap\HTML\is_HTML($args['home'])) {
        $args['home_icon'] = $args['home'];
        unset($args['home']);
    }

    $args = apply_filters('italystrap_breadcrumbs_args', $args);

    foreach ($deprecated as $key => $value) {
        if (isset($args[ $key ])) {
            _deprecated_argument(__FUNCTION__, '3.0', sprintf(__('The "%s" argument has been removed. See the new Breadcrumbs default for more information on https://github.com/ItalyStrap/breadcrumbs', 'italystrap'), $args[ $key ]));
        }
    }

    try {
        return apply_filters(
            'italystrap_get_the_breadcrumbs',
            \ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make('html', $args)->output(),
            $args
        );
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

/**
 * Print the Breadcrumbs
 *
 * @param  array  $args The breadcrumbs arguments.
 *                      @see class Breadcrumbs for more info.
 * @return string       Return the breadcrumbs html.
 */
function breadcrumbs(array $args = [])
{

    echo get_breadcrumbs($args);
}

/**
 * Do breadcrumbs
 *
 * @since 2.2.0
 *
 * @param  array  $args The breadcrumbs arguments.
 */
function do_breadcrumbs(array $args = [])
{

    breadcrumbs($args);
}
add_action('do_breadcrumbs', __NAMESPACE__ . '\do_breadcrumbs');
