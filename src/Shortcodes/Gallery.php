<?php

/**
 * Shortcode_Carousel initially forked from Agnosia Bootstrap Carousel by AuSoft
 *
 * This class adds Carousell functionality to [gallery] shortcode and it loads only when needed.
 *
 * @since 1.1.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Shortcodes;

use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Carousel\Bootstrap;

/**
 * Add Bootstrap Carousel to gallery shortcode
 */
class Gallery implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked plugins_loaded - 10
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            // 'hook_name'                          => 'method_name',
            'wp_loaded' => 'add_carousel_to_gallery_shortcode',
        ];
    }

    /**
     * Modify the gallery shortcode with Bootstrap Carousel functionality.
     *
     * @param  string        $output   The gallery output. Default empty.
     * @param  array|string  $atts     Attributes of the gallery shortcode.
     * @param  int           $instance Unique numeric ID of this gallery shortcode instance.
     * @return string                  Return the new Bootstrap carousel
     */
    public function gallery_shortcode($output, $atts, $instance = null)
    {

        /**
         * In case the shortcode has no attributes $atts will be a string
         */
        if (! is_array($atts)) {
            return $output;
        }

        /**
         * If type is not set return the output.
         */
        if (! isset($atts['type'])) {
            return $output;
        }

        if ('carousel' === $atts['type']) {
            $carousel_bootstrap = new Bootstrap($atts);
            return $carousel_bootstrap->__get('output');
        }

        return $output;
    }

    /**
     * Add 'Bootstrap Carousel' to $gallery_type array
     *
     * @param  array $gallery_types The array with gallery type.
     * @return array                Return the new array
     */
    public function gallery_types($gallery_types)
    {

        $gallery_types['carousel'] = __('Bootstrap Carousel', 'italystrap');
        return $gallery_types;
    }



    /**
     * Add type Carousel to built-in gallery shortcode
     */
    public function add_carousel_to_gallery_shortcode()
    {

        /**
         * Istantiate Shortcode_Carousel only if [gallery] shortcode exist
         *
         * @link http://wordpress.stackexchange.com/questions/103549/wp-deregister-register-and-enqueue-dequeue
         */
        $post = get_post();
        $gallery = false;

        if (isset($post->post_content) && has_shortcode($post->post_content, 'gallery')) {
            $gallery = true; // A http://dannyvankooten.com/3935/only-load-contact-form-7-scripts-when-needed/ .
        }

        if (! $gallery) {
            // $shortcode_carousel = new Gallery();
            add_filter('post_gallery', [$this, 'gallery_shortcode'], 10, 3);
            add_filter('jetpack_gallery_types', [$this, 'gallery_types']);
            // add_filter( 'ItalyStrap_gallery_types', array( $this, 'gallery_types' ), 999 );
        }
    }
}
