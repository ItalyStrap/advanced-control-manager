<?php

/**
 * Handle the Tag Manager Script
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Google;

if (! defined('ITALYSTRAP_PLUGIN') or ! ITALYSTRAP_PLUGIN) {
    die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Generate script
 */
class Tag_Manager implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked wp_footer - 99999
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            // 'hook_name'                          => 'method_name',
            'wp_head'   => ['function_to_add'   => 'render', 'priority'          => 1],
            'italystrap_before' => ['function_to_add'   => 'render', 'priority'          => 1],
        ];
    }

    /**
     * Plugin options settings.
     */
    private ?array $options = null;

    private static string $position = '';

    /**
     * Init the constructor.
     *
     * @param array $argument Plugin options settings.
     */
    function __construct(array $options = [])
    {
        $this->options = $options;
        // add_filter( 'body_class', array( $this, 'render_tag_manager' ), 10000, 2 );
    }

    /**
     * Render
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function render()
    {

        if (is_customize_preview() || is_preview() || is_admin()) {
            return;
        }

        if (empty($this->options['google_tag_manager_id'])) {
            return;
        }

        $current_filter = current_filter() === 'wp_head';
        $file_name = $current_filter
            ? 'head'
            : 'body';

        echo $this->get_view($file_name);
    }

    /**
     * Get the tag manager script
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_view($file_name = '')
    {

        $id = $this->options['google_tag_manager_id'];

        ob_start();
        require('views/tag-manager-' . $file_name . '.php');
        return ob_get_clean();
    }

    /**
     * Google tag manager
     * @link http://www.tagmanageritalia.it/come-installare-google-tag-manager-tramite-wordpress/
     * Filters the list of CSS body classes for the current post or page.
     *
     * @since 2.2.2
     *
     * @param array $classes An array of body classes.
     * @param array $class   An array of additional classes added to the body.
     */
    public function render_tag_manager($classes, $class)
    {

        if (is_customize_preview() || is_preview() || is_admin()) {
            return;
        }

        if (empty($this->options['google_tag_manager_id'])) {
            return;
        }

        $snippet = sprintf(
            '<noscript><iframe src="//www.googletagmanager.com/ns.html?id=%s"height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({"gtm.start":new Date().getTime(),event:"gtm.js"});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!="dataLayer"?"&l="+l:"";j.async=true;j.src="//www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);})(window,document,"script","dataLayer","%s");</script>',
            esc_js($this->options['google_tag_manager_id'])
        );

        $classes[] = sprintf(
            '">%s<br style="display:none',
            $snippet
        );

        return $classes;
    }
}
