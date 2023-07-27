<?php

/**
 * LazyLoad Video API
 *
 * Class for lazyloading video embedded
 * @link https://webdesign.tutsplus.com/tutorials/how-to-lazy-load-embedded-youtube-videos--cms-26743
 * @link https://github.com/viktorbergehall/lazyframe/blob/master/src/lazyframe.js
 *
 * @link http://italystrap.com
 * @since 2.2.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Lazyload;

use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Asset\Inline_Script;
use ItalyStrap\Asset\Inline_Style;

/**
 * Class description
 */
class Video implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked embed_oembed_html - 10
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            // 'hook_name'                          => 'method_name',
            /**
             * This run on [embed] shortcode
             */
            'embed_oembed_html' => ['function_to_add'   => 'get_embed', 'accepted_args'     => 4],
            'oembed_result' => ['function_to_add'   => 'get_embed', 'accepted_args'     => 3],
            'wp_video_shortcode'    => ['function_to_add'   => 'get_video_shortcode', 'accepted_args'     => 5],
        ];
    }

    /**
     * A regex to search for youtube video
     *
     * @see WP_oEmbed::$providers in wp-includes/class-oembed.php for more regex
     * @see wp_video_shortcode() in wp-includes/media.php L#2428
     *
     * @regex null
     */
    // private $regex = '#https?://(www.)?youtube\.com/(?:v|embed)/([^/]+)#i';
    private string $regex = '#(?:v=|\/v\/|\.be\/)([a-zA-Z0-9_-]+)#i';

    /**
     * Constructor
     */
    function __construct()
    {
        Inline_Style::set($this->get_asset('style'));
        Inline_Script::set($this->get_asset('script'));
    }

    /**
     * Filters the default array of embed dimensions.
     *
     * @since 2.8.0
     *
     * @see wp-includes/embed.php
     *
     * @param array  $size An array of embed width and height values
     *                     in pixels (in that order).
     * @param string $url  The URL that should be embedded.
     */
    public function embed_defaults($size, $url)
    {
        return $size;
    }

    /**
     * Get embed
     *
     * @todo Problema con gli embed da link di siti WordPress, controllare $matches[1]
     *       Provare con questa url nell'editor https://css-tricks.com/moving-to-https-on-wordpress/
     *
     * @param  string $value [description]
     *
     * @return string        [description]
     */
    public function get_embed($cache, $url, $attr, $post_ID = null)
    {

        return $this->get_video_html($url, $cache);
    }

    /**
     * Get embed
     *
     * @todo Problema con gli embed da link di siti WordPress, controllare $matches[1]
     *       Provare con questa url nell'editor https://css-tricks.com/moving-to-https-on-wordpress/
     *
     * @param  string $output  Video shortcode HTML output.
     * @param  array  $atts    Array of video shortcode attributes.
     * @param  string $video   Video file.
     * @param  int    $post_id Post ID.
     * @param  string $library Media library used for the video shortcode.
     *
     * @return string          The video HTML output
     */
    public function get_video_shortcode($html, $atts, $video, $post_id, $library)
    {

        if (! isset($atts['src'])) {
            return $html;
        }

        return $this->get_video_html($atts['src'], $html);
    }

    /**
     * Get the video HTML
     *
     * @param  string $url The url of the video.
     *
     * @return string      Return the video HTML for lazy load
     */
    public function get_video_html($url, $html)
    {

        preg_match($this->regex, $url, $matches);

        if (! isset($matches[1])) {
            return $html;
        }

        $html = sprintf(
            '<div class="lazyload-video-wrap"><div class="lazyload-video youtube" data-embed="%s"><div class="play-button"></div></div></div>',
            $matches[1]
        );

        return $html;
    }

    /**
     * Get the style or script asset for the Video Lazy Load
     *
     * @return string Return the content of the file
     */
    public function get_asset($file_type)
    {
        ob_start();
        require('asset/video-' . $file_type . '.php');
        return ob_get_clean();
    }
}
