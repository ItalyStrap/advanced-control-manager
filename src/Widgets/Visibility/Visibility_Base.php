<?php

/**
 * [Short Description (no period for file headers)]
 *
 * [Long Description.]
 *
 * @link www.italystrap.com
 * @since 2.3.0
 *
 * @package ItalyStrap\Widget
 */

namespace ItalyStrap\Widgets\Visibility;

/**
 * Class description
 */
abstract class Visibility_Base implements Visibility_Interface
{
    public static function strcasecmp_name($a, $b)
    {
        return strcasecmp($a->name, $b->name);
    }

    public static function maybe_get_split_term($old_term_id = '', $taxonomy = '')
    {
        $term_id = $old_term_id;

        if ('tag' == $taxonomy) {
            $taxonomy = 'post_tag';
        }

        if (function_exists('wp_get_split_term') && $new_term_id = wp_get_split_term($old_term_id, $taxonomy)) {
            $term_id = $new_term_id;
        }

        return $term_id;
    }
}
