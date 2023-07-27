<?php

/**
 * Widget API: Widget Visibility
 *
 * Forked from Jetpack module Widget conditions.
 *
 * @link www.italystrap.com
 * @since 2.3.0
 *
 * @package ItalyStrap\Widget
 */

namespace ItalyStrap\Widgets\Visibility;

/**
 * Hide or show widgets conditionally.
 */

class Visibility extends Visibility_Base
{
    static $passed_template_redirect = false;

    public static function init()
    {

        if (! in_array($GLOBALS['pagenow'], ['wp-login.php', 'wp-register.php'], true)) {
            add_filter('widget_display_callback', [self::class, 'filter_widget']);
            add_filter('sidebars_widgets', [self::class, 'sidebars_widgets']);
            add_action('template_redirect', [self::class, 'template_redirect']);
        }
    }

    /**
     * Filter the list of widgets for a sidebar so that active sidebars work as expected.
     *
     * @param array $widget_areas An array of widget areas and their widgets.
     * @return array The modified $widget_area array.
     */
    public static function sidebars_widgets($widget_areas)
    {
        $settings = [];

        foreach ($widget_areas as $widget_area => $widgets) {
            if (empty($widgets)) {
                continue;
            }

            if (! is_array($widgets)) {
                continue;
            }

            if ('wp_inactive_widgets' === $widget_area) {
                continue;
            }

            foreach ($widgets as $position => $widget_id) {
                // Find the conditions for this widget.
                if (preg_match('/^(.+?)-(\d+)$/', $widget_id, $matches)) {
                    $id_base = $matches[1];
                    $widget_number = intval($matches[2]);
                } else {
                    $id_base = $widget_id;
                    $widget_number = null;
                }

                if (! isset($settings[$id_base])) {
                    $settings[$id_base] = get_option('widget_' . $id_base);
                }

                // New multi widget (WP_Widget)
                if (! is_null($widget_number)) {
                    if (
                        isset($settings[ $id_base ][ $widget_number ])
                        && false === self::filter_widget($settings[ $id_base ][ $widget_number ])
                    ) {
                        unset($widget_areas[ $widget_area ][ $position ]);
                    }
                }

                // Old single widget
                elseif (! empty($settings[ $id_base ]) && false === self::filter_widget($settings[$id_base])) {
                    unset($widget_areas[$widget_area][$position]);
                }
            }
        }

        return $widget_areas;
    }

    public static function template_redirect()
    {
        self::$passed_template_redirect = true;
    }

    /**
     * Generates a condition key based on the rule array
     *
     * @param array $rule
     * @return string key used to retrieve the condition.
     */
    static function generate_condition_key($rule)
    {
        if (isset($rule['has_children'])) {
            return $rule['major'] . ":" . $rule['minor'] . ":" . $rule['has_children'];
        }
        return $rule['major'] . ":" . $rule['minor'];
    }

    /**
     * Determine whether the widget should be displayed based on conditions set by the user.
     *
     * @param array $instance The widget settings.
     * @return array Settings to display or bool false to hide.
     */
    public static function filter_widget($instance)
    {
        global $wp_query;

        if (empty($instance['conditions']) || empty($instance['conditions']['rules'])) {
            return $instance;
        }

        // Store the results of all in-page condition lookups so that multiple widgets with
        // the same visibility conditions don't result in duplicate DB queries.
        static $condition_result_cache = [];

        $condition_result = false;

        foreach ($instance['conditions']['rules'] as $rule) {
            $condition_key = self::generate_condition_key($rule);

            if (isset($condition_result_cache[ $condition_key ])) {
                $condition_result = $condition_result_cache[ $condition_key ];
            } else {
                switch ($rule['major']) {
                    case 'date':
                        switch ($rule['minor']) {
                            case '':
                                $condition_result = is_date();
                                break;
                            case 'month':
                                $condition_result = is_month();
                                break;
                            case 'day':
                                $condition_result = is_day();
                                break;
                            case 'year':
                                $condition_result = is_year();
                                break;
                        }
                        break;
                    case 'page':
                        // Previously hardcoded post type options.
                        if ('post' == $rule['minor']) {
                            $rule['minor'] = 'post_type-post';
                        } elseif (! $rule['minor']) {
                            $rule['minor'] = 'post_type-page';
                        }

                        switch ($rule['minor']) {
                            case '404':
                                $condition_result = is_404();
                                break;
                            case 'search':
                                $condition_result = is_search();
                                break;
                            case 'archive':
                                $condition_result = is_archive();
                                break;
                            case 'posts':
                                $condition_result = $wp_query->is_posts_page;
                                break;
                            case 'home':
                                $condition_result = is_home();
                                break;
                            case 'front':
                                if (current_theme_supports('infinite-scroll')) {
                                    $condition_result = is_front_page();
                                } else {
                                    $condition_result = is_front_page() && !is_paged();
                                }
                                break;
                            default:
                                if (substr($rule['minor'], 0, 10) == 'post_type-') {
                                    $condition_result = is_singular(substr($rule['minor'], 10));
                                } elseif ($rule['minor'] == get_option('page_for_posts')) {
                                    // If $rule['minor'] is a page ID which is also the posts page
                                    $condition_result = $wp_query->is_posts_page;
                                } else {
                                    // $rule['minor'] is a page ID
                                    $condition_result = is_page() && ( $rule['minor'] == get_the_ID() );

                                    // Check if $rule['minor'] is parent of page ID
                                    if (! $condition_result && isset($rule['has_children']) && $rule['has_children']) {
                                        $condition_result = wp_get_post_parent_id(get_the_ID()) == $rule['minor'];
                                    }
                                }
                                break;
                        }
                        break;
                    case 'tag':
                        if (! $rule['minor'] && is_tag()) {
                            $condition_result = true;
                        } else {
                            $rule['minor'] = self::maybe_get_split_term($rule['minor'], $rule['major']);
                            if (is_singular() && $rule['minor'] && has_tag($rule['minor'])) {
                                $condition_result = true;
                            } else {
                                $tag = get_tag($rule['minor']);
                                if ($tag && ! is_wp_error($tag) && is_tag($tag->slug)) {
                                    $condition_result = true;
                                }
                            }
                        }
                        break;
                    case 'category':
                        if (! $rule['minor'] && is_category()) {
                            $condition_result = true;
                        } else {
                            $rule['minor'] = self::maybe_get_split_term($rule['minor'], $rule['major']);
                            if (is_category($rule['minor'])) {
                                $condition_result = true;
                            } elseif (is_singular() && $rule['minor'] && in_array('category', get_post_taxonomies()) &&  has_category($rule['minor'])) {
                                $condition_result = true;
                            }
                        }
                        break;
                    case 'loggedin':
                        $condition_result = is_user_logged_in();
                        if ('loggedin' !== $rule['minor']) {
                            $condition_result = ! $condition_result;
                        }
                        break;
                    case 'author':
                        $post = get_post();
                        if (! $rule['minor'] && is_author()) {
                            $condition_result = true;
                        } elseif ($rule['minor'] && is_author($rule['minor'])) {
                            $condition_result = true;
                        } elseif (is_singular() && $rule['minor'] && $rule['minor'] == $post->post_author) {
                            $condition_result = true;
                        }
                        break;
                    case 'role':
                        if (is_user_logged_in()) {
                            $current_user = wp_get_current_user();

                            $user_roles = $current_user->roles;

                            if (in_array($rule['minor'], $user_roles)) {
                                $condition_result = true;
                            } else {
                                $condition_result = false;
                            }
                        } else {
                            $condition_result = false;
                        }
                        break;
                    case 'post_type':
                        if (substr($rule['minor'], 0, 10) == 'post_type-') {
                            $condition_result = is_singular(substr($rule['minor'], 10));
                        } elseif (substr($rule['minor'], 0, 18) == 'post_type_archive-') {
                            $condition_result = is_post_type_archive(substr($rule['minor'], 18));
                        }
                        break;
                    case 'taxonomy':
                        $term = explode('_tax_', $rule['minor']); // $term[0] = taxonomy name; $term[1] = term id
                        if (isset($term[0]) && isset($term[1])) {
                            $term[1] = self::maybe_get_split_term($term[1], $term[0]);
                        }
                        if (isset($term[1]) && is_tax($term[0], $term[1])) {
                            $condition_result = true;
                        } elseif (isset($term[1]) && is_singular() && $term[1] && has_term($term[1], $term[0])) {
                            $condition_result = true;
                        } elseif (is_singular() && $post_id = get_the_ID()) {
                            $terms = get_the_terms($post_id, $rule['minor']); // Does post have terms in taxonomy?
                            if ($terms && ! is_wp_error($terms)) {
                                $condition_result = true;
                            }
                        }
                        break;
                }

                if ($condition_result || self::$passed_template_redirect) {
                    // Some of the conditions will return false when checked before the template_redirect
                    // action has been called, like is_page(). Only store positive lookup results, which
                    // won't be false positives, before template_redirect, and everything after.
                    $condition_result_cache[ $condition_key ] = $condition_result;
                }
            }

            if ($condition_result) {
                break;
            }
        }

        if (( 'show' == $instance['conditions']['action'] && ! $condition_result ) || ( 'hide' == $instance['conditions']['action'] && $condition_result )) {
            return false;
        }

        return $instance;
    }
}
