<?php

/**
 * Abstract class for admin panel
 *
 * This class add some functions for use in admin panel
 *
 * @link http://codex.wordpress.org/Adding_Administration_Menus
 * @link http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-4-on-theme-options--wp-24902
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
 *
 * @todo A quick WordPress template tag to create Google Analytics Event Tracking on links. Built on behalf of CFO Publishing.
 * @link https://gist.github.com/AramZS/8930496
 */
class Analytics implements Subscriber_Interface
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
            self::$position => ['function_to_add'   => 'render_analytics', 'priority'          => 999999],
        ];
    }

    /**
     * Plugin options settings.
     */
    private ?array $options = null;

    private static $position = '';

    /**
     * Init the constructor.
     *
     * @param array $argument Plugin options settings.
     */
    function __construct(array $options = [])
    {
        $this->options = $options;
        self::$position = $this->options['google_analytics_position'];
        // add_filter( 'body_class', array( $this, 'render_tag_manager' ), 10000, 2 );
    }

    // add_filter( 'body_class', 'render_tag_manager', 10000 );

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

        if (is_preview() && is_admin()) {
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

    /**
     * Get a safe string for custom variables or event tracking for Google Analytics.
     *
     * @param  string $string The string to escape.
     *
     * @return string         The string escaped
     */
    public function get_for_google_analytics($string)
    {
        $string = strip_tags($string);
        $string = remove_accents(html_entity_decode($string));
        $safe_string = esc_js($string);
        return $safe_string;
    }

    /**
     * Echo an event tracking code for Google Analytics.
     *
     * @param  string $category       [description].
     * @param  string $action         [description].
     * @param  string $label          [description].
     * @param  int    $value          [description].
     * @param  bool   $noninteraction [description].
     */
    public function the_event_tracking($category, $action, $label, $value = 1, $noninteraction = false)
    {
        if (! is_int($value)) {
            $value = 1;
        }
        if (! is_bool($noninteraction)) {
            $noninteraction = false;
        }
        $bool_string = ( $noninteraction ) ? 'true' : 'false';
        $s = sprintf(
            'onClick="_gaq.push([%1$s, %2$s, %3$s, %4$s, %5$s, %6$s]);"',
            "'_trackEvent'",
            "'" . get_for_google_analytics($category) . "'",
            "'" . get_for_google_analytics($action) . "'",
            "'" . get_for_google_analytics($label) . "'",
            $value,
            $bool_string
        );

        echo $s; // XSS ok.
    }

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_ga(array $parameters)
    {

        // if ( empty( $parameters[0] ) ) {
        //  return '';
        // }

        $count_params = count($parameters);

        $output = 'ga(';

        /**
         * This filters the array with html attributes.
         *
         * @var array
         */
        // $parameters = (array) apply_filters( "italystrap_{$context}_attr", $parameters, $context, $args );

        $i = 0;

        foreach ($parameters as $key => $parameter) {
            if (empty($parameter)) {
                continue;
            }

            if (is_array($parameter)) {
                $output .= json_encode($parameter, JSON_THROW_ON_ERROR);
            } else {
                $output .= sprintf(
                    '"%s"%s',
                    esc_js($parameter),
                    $count_params - 1 === $i ? '' : ','
                );
            }

            $i++;
        }

        $output .= ');';

        /**
         * This filters the output of the html attributes.
         *
         * @var string
         */
        // $output = apply_filters( "italystrap_attr_{$context}_output", $output, $parameters, $context, $args );

        return $output;
    }

    /**
     * Maybe anonimize IP
     *
     * @return string        [description]
     */
    public function maybe_anonimize_ip()
    {

        if (empty($this->options['google_analytics_anonymizeIp'])) {
            return '';
        }

        $parameters = ['command'       => 'set', 'fields'        => 'anonymizeIp', 'fields_object' => 'true'];

        return $this->get_ga($parameters);
    }

    /**
     * Init ga
     *
     * @return string        [description]
     */
    public function ga_commands_queue()
    {

        $parameters = [['command'       => 'create', 'fields'        => esc_js($this->options['google_analytics_id']), 'fields_object' => 'auto'], ['command'       => 'send', 'fields'        => 'pageview']];

        $parameters = apply_filters('italystrap_ga_commands_queue', $parameters, $this->options);

        $output = '';

        foreach ($parameters as $array) {
            $output .= $this->get_ga($array);
        }

        $output .= $this->maybe_anonimize_ip();

        return apply_filters('italystrap_ga_commands_queue_output', $output, $this);
    }

    /**
     * Standard analytics script
     *
     * @link https://developers.google.com/analytics/devguides/collection/analyticsjs/
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function standard_script($get_ga)
    {

        return sprintf(
            '<script>(function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,"script","https://www.google-analytics.com/analytics.js","ga");%s</script>',
            $get_ga
        );
    }

    /**
     * The alternative async tracking snippet below adds support for preloading,
     * which will provide a small performance boost on modern browsers,
     * but can degrade to synchronous loading and execution on IE 9 and
     * older mobile browsers that do not recognize the async script attribute.
     * Only use this tracking snippet if your visitors primarily use
     * modern browsers to access your site.
     *
     * @link https://developers.google.com/analytics/devguides/collection/analyticsjs/
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function alternative_script($get_ga)
    {

        return sprintf(
            '<script async src="https://www.google-analytics.com/analytics.js"></script><script>window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;%s</script>',
            $get_ga
        );
    }

    /**
     * Insert your ID in Option Theme admin panel
     * Print code only if value exist
     *
     * @todo google event tracking time
     * @link https://programma-affiliazione.amazon.it/gp/associates/promo/a20140921?rw_useCurrentProtocol=1
     *
     * @link https://developers.google.com/analytics/devguides/collection/analyticsjs/
     *
     * @return string Return google analytics code
     */
    public function render_analytics()
    {

        if (is_customize_preview() || is_preview() || is_admin()) {
            return;
        }

        if (empty($this->options['google_analytics_id'])) {
            return;
        }

        // $output = $this->alternative_script( $this->ga_commands_queue() );
        $output = $this->standard_script($this->ga_commands_queue());

        echo $output; // XSS ok.
    }
}
