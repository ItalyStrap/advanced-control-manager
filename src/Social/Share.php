<?php

/**
 * Class API for Social Sharing Button
 *
 * @since 2.1.0
 *
 * @version 1.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Social;

use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Asset\Inline_Script;
use ItalyStrap\Asset\Inline_Style;
use ItalyStrap\HTML;

/**
 * The Share Class
 *
 * @todo This class need more improvements
 */
class Share implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked the_content - 9999
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            // 'hook_name'                          => 'method_name',
            'the_content'   => ['function_to_add'   => 'render', 'priority'          => 9999, 'accepted_args'     => 1],
        ];
    }

    /**
     * [$var description]
     *
     * @var null
     */
    private $social_url = [];

    private array $options = [];

    /**
     * [__construct description]
     *
     * @param [type] $argument [description].
     */
    function __construct(array $options = [])
    {

        $option = [];
        $this->options = $options;

        $wpseo_social = get_option('wpseo_social');
        $this->via = ! empty($option['twitter_site']) ? '&via=' . $option['twitter_site'] : '' ;

        // add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 999999 );
        add_action('wp_footer', [$this, 'style_in_footer'], 999999);

        /**
         * Append css in static variable and print in front-end footer
         */
        // Inline_Style::set( \ItalyStrap\Core\get_file_content( ITALYSTRAP_PLUGIN_PATH . 'css/social.css' ) );
        Inline_Style::set($this->style());
    }

    /**
     * Set CSS style
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function style()
    {

        $rules = ['facebook'  => ['background-color'  => '#465f9e', 'border-color'      => '#465f9e', 'color'             => '#fff'], 'twitter'   => ['background-color'  => '#1DA1F2', 'border-color'      => '#1DA1F2', 'color'             => '#fff'], 'google-plus'   => ['background-color'  => '#DB4437', 'border-color'      => '#DB4437', 'color'             => '#fff'], 'linkedin'  => ['background-color'  => '#0077B5', 'border-color'      => '#0077B5', 'color'             => '#fff'], 'pinterest' => ['background-color'  => '#B4091C', 'border-color'      => '#B4091C', 'color'             => '#fff']];

        $style = '';

        foreach ($rules as $key => $value) {
            $style .= sprintf(
                '.%s{%s}',
                $key,
                $this->get_props($value)
            );
        }

        return $style;
    }

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_props(array $props = [])
    {

        $output = '';

        foreach ($props as $prop => $prop_value) {
            $output .= sprintf(
                '%s:%s;',
                $prop,
                $prop_value
            );
        }
        return $output;
    }

    /**
     * Enqueue scripts
     *
     * @param string $handle Script name
     * @param string $src Script url
     * @param array $deps (optional) Array of script names on which this script depends
     * @param string|bool $ver (optional) Script version (used for cache busting), set to null to disable
     * @param bool $in_footer (optional) Whether to enqueue the script before </head> or before </body>
     */
    function enqueue_scripts()
    {
        wp_enqueue_style('fontello', ITALYSTRAP_PLUGIN_URL . 'css/social.css', null, null, null);
    }

    /**
     * Load Font Awesome in footer
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function style_in_footer()
    {

        echo '<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">';
        // echo '<script>jQuery(document).ready(function($){ $("#google-plus").replaceWith("gplus");});</script>';
    }


    /**
     * Set social url
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function set_social_url()
    {
        global $post;

        $get_permalink = get_permalink();
        $get_the_title = esc_attr($post->post_title);
        // $get_the_content = get_the_content();
        // $get_the_excerpt = get_the_excerpt();
        $get_the_excerpt = '';
        // $get_the_excerpt = $this->content;
        $thumb_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_id()));

        $this->social_url = [
            'facebook'  => sprintf(
                '//www.facebook.com/sharer.php?u=%s&p[title]=%s',
                $get_permalink,
                $get_the_title
            ),
            'twitter'   => sprintf(
                '//twitter.com/share?url=%s&text=%s%s',
                $get_permalink,
                $get_the_title,
                $this->via
            ),
            'google-plus'       => sprintf(
                '//plus.google.com/share?url=%s',
                $get_permalink
            ),
            /**
             * https://developer.linkedin.com/docs/share-on-linkedin#
             */
            'linkedin'  => sprintf(
                '//www.linkedin.com/shareArticle?mini=true&url=%s&title=%s',
                $get_permalink,
                $get_the_title
            ),
            'pinterest' => sprintf(
                '//pinterest.com/pin/create/button/?url=%s&media=%s&description=%s',
                $get_permalink,
                $thumb_url,
                $get_the_title
            ),
        ];
    }

    /**
     * Render Sharing button
     *
     * Alcune idee https://www.google.it/search?site=&source=hp&q=twitter+url+share&oq=twitter+url+share&gs_l=hp.3.0.0i19l3j0i22i10i30i19j0i22i30i19l6.1898.11624.0.13381.18.18.0.0.0.0.2152.4618.0j13j0j1j1j9-1.16.0....0...1c.1.64.hp..2.15.4160.0.93FpzCXhTCY
     * https://gist.github.com/chrisjlee/5196139
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_social_button()
    {

        $this->set_social_url();

        $output = '<ul class="social-button list-inline">';

        $link_attr = [
            // Not use href because will not be escaped right.
            'class'     => '%2$s btn btn-default btn-xs',
            'target'    => 'popup',
            'onclick'   => 'window.open("%1$s","popup","width=600,height=600"); return false;',
            'rel'       => 'nofollow',
            'title'     => __('Share on %2$s', 'italystrap'),
        ];

        foreach ($this->social_url as $key => $url) {
            $format = HTML\get_attr($key, $link_attr);

            $output .= sprintf(
                '<li><a href="%1$s" ' . $format . '><span class="fa fa-%2$s" aria-hidden="true"></span> <span id="%2$s">%3$s</span></a></li>',
                $url,
                $key,
                str_replace('-', ' ', $key)
            );
        }

        $output .= '</ul>';

        return $output;
    }

    /**
     * Add social share
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function render($content)
    {

        if (! is_singular()) {
            return $content;
        }

        /**
         * Forced to be an array because if during save option none are selected
         * the option is an empty string
         */
        $social_button_on_post_types = (array) $this->options['social_button_on_post_types'];

        $display_social_share_button = (array) get_post_meta(get_the_ID(), '_italystrap_template_settings', true);


        /**
         * If is not the actual post type selected bail out
         */
        if (
            ! in_array(get_post_type(), $social_button_on_post_types, true)
            || in_array('hide_social', $display_social_share_button)
        ) {
            return $content;
        }

        $this->content = $content;

        $positions = ['before'    => '%2$s%1$s', 'after'     => '%1$s%2$s', 'both'      => '%2$s%1$s%2$s'];

        return sprintf(
            $positions[ $this->options['social_button_position'] ],
            $content,
            $this->get_social_button()
        );
    }
}
