<?php

/**
 * Widget_Cache API
 *
 * Cache for Widget instances
 *
 * Class in ALPHA testing
 *
 * @link www.italystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Widgets;

/**
 * Widget_Cache
 */
class Widget_Cache
{
    /**
     * Get cached widget.
     *
     * @return bool true   If the widget is cached otherwise false
     */
    public function get_cached_widget()
    {

        $cache = wp_cache_get(apply_filters("italystrap_cached_{$this->id}", $this->id), 'widget');

        if (! is_array($cache)) {
            $cache = [];
        }

        if (isset($cache[ $this->id ])) {
            echo $cache[ $this->id ]; // XSS ok.
            return true;
        }

        return false;
    }

    /**
     * Cache the widget.
     *
     * @param  array  $args     The argumento of widget class.
     * @param  string $content The content of the widget to display.
     * @return string          The content that was cached
     */
    public function cache_widget($args, $content)
    {

        wp_cache_set(apply_filters("italystrap_cached_{$this->id}", $this->id), [$this->id => $content], 'widget');

        return $content;
    }

    /**
     * Flush the cache.
     */
    public function flush_widget_cache()
    {

        wp_cache_delete(apply_filters("italystrap_cached_{$this->id}", $this->id), 'widget');
    }
}
