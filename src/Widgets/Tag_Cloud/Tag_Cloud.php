<?php

/**
 * Tag_Cloud API
 *
 * This class add some CSS class to the tag_cloud widget.
 *
 * Initially forked from https://github.com/320press/wordpress-bootstrap
 *
 * @see  https://developer.wordpress.org/reference/functions/wp_tag_cloud/
 *
 * @link www.italystrap.com
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Widgets\Tag_Cloud;

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Tag_Cloud
 */
class Tag_Cloud implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            // 'hook_name'              => 'method_name',
            'widget_tag_cloud_args'         => 'widget_tag_cloud_args',
            'wp_generate_tag_cloud_data'    => 'tag_cloud_data',
        ];
    }

    /**
     * [$var description]
     */
    private array $defaults = ['smallest'  => 8, 'largest'   => 22, 'unit'      => 'pt', 'number'    => 45, 'format'    => 'flat', 'separator' => "\n", 'orderby'   => 'name', 'order'     => 'ASC', 'exclude'   => '', 'include'   => '', 'link'      => 'view', 'taxonomy'  => 'post_tag', 'post_type' => '', 'echo'      => true];

    /**
     * Filters the taxonomy used in the Tag Cloud widget.
     *
     * @see https://developer.wordpress.org/reference/hooks/widget_tag_cloud_args/
     *
     * @param  array $args Args used for the tag cloud widget.
     *
     * @return array       Return the new array
     */
    public function widget_tag_cloud_args($args)
    {

        $args['number'] = 20; // show less tags
        $args['largest'] = 0.7; // make largest and smallest the same - i don't like the varying font-size look
        $args['smallest'] = 0.7;
        $args['unit'] = 'em';

        return $args;
    }

    /**
     * Filters the tag cloud output.
     *
     * @param array $tags_data
     * @return array HTML output of the tag cloud.
     */
    public function tag_cloud_data(array $tags_data)
    {

        foreach ($tags_data as $k => $tag_data) {
            $tags_data[ $k ]['class'] = $tag_data['class'] . ' label label-default';
        }

        return $tags_data;
    }
}
