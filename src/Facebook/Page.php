<?php

/**
 * Facebook Page Widget API
 *
 * This class add a Facebook page with many settings.
 *
 * @see https://developers.facebook.com/docs/plugins/page-plugin/
 *
 * @link italystrap.com
 * @since 2.2.1
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Facebook;

use ItalyStrap\Core;

/**
 * Facebook Page
 */
class Page
{
    /**
     * [$var description]
     *
     * @var null
     */
    private $var = null;

    /**
     * [__construct description]
     *
     * @param [type] $argument [description].
     */
    function __construct($argument = null)
    {
        // Code...
    }
    // add_action( 'wp_footer', array( $facebook_page, 'script_2' ), 99 );
    // add_action( 'italystrap_sidebar', array( $facebook_page, 'output' ) );

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function script()
    {

        return '(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id; js.async = true;
			js.src = "//connect.facebook.net/" + sfpp_script_vars.language + "/sdk.js#xfbml=1&version=v2.5&appId=" + sfpp_script_vars.appId;
			fjs.parentNode.insertBefore(js, fjs);
		}(document, "script", "facebook-jssdk"));';
    }

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function script_2()
    {

        $data = ['xfbml'     => '1', 'version'   => 'v2.8'];

        // if ( empty( $instance['appId'] ) ) {
        //  $data['appId'] = $instance['appId'];
        // }

        echo sprintf(
            '<div id="fb-root"></div><script>(function(d, s, id){var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/%1$s/sdk.js#%2$s";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script>',
            str_replace('-', '_', get_bloginfo('language')),
            http_build_query($data)
        );
    }

    /**
     * Function description
     *
     * @param  array $instance [description].
     *
     * @return string        [description]
     */
    public function render(array $instance = [])
    {

        if (empty($instance['href'])) {
            return sprintf(
                '<p>%s</p>',
                __('You have to add the name of yor Facebook page in the settings.', 'italystrap')
            );
        }

        // $instance = wp_parse_args( $instance, array(
        //  'href'                  => 'https://www.facebook.com/facebook',
        //  'width'                 => '380',
        //  'height'                => '214',
        //  'tabs'                  => array( 'timeline', 'events', 'messages' ),
        //  'hide-cover'            => 'false',
        //  'show-facepile'         => 'true',
        //  'hide-cta'              => 'false',
        //  'small-header'          => 'false',
        //  'adapt-container-width' => 'true',
        //  'align'                 => 'none',
        // ) );

        // wp_parse_args( $args, $defaults );

        $attr = ['class'                         => 'fb-page', 'data-href'                     =>
            false !== strpos($instance['href'], 'facebook.com')
            ? esc_url($instance['href'])
            : 'https://facebook.com/' . esc_attr($instance['href']), 'data-width'                    => absint($instance['width']), 'data-height'                   => absint($instance['height']), 'data-tabs'                     => implode(',', (array) $instance['tabs']), 'data-hide-cover'               => esc_attr($instance['hide-cover']), 'data-show-facepile'            => esc_attr($instance['show-facepile']), 'data-hide-cta'                 => esc_attr($instance['hide-cta']), 'data-small-header'             => esc_attr($instance['small-header']), 'data-adapt-container-width'    => esc_attr($instance['adapt-container-width'])];

        $output = sprintf(
            '<div%1$s><div%2$s></div></div>',
            ' style="text-align:' . esc_attr($instance['align']) . ';max-width: ' . absint($instance['width']) . 'px;height: ' . absint($instance['height']) . 'px;"',
            Core\get_attr('facebook_page', $attr, false, null)
        );

        return $output;
    }

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function output()
    {

        echo $this->render([]);
    }
}
