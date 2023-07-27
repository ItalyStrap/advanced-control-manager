<?php

/**
 * Inline_Asset_Factory API
 *
 * Print inline asset.
 *
 * @link www.italystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Asset;

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Inline_Asset_Factory
 */
class Inline_Asset_Factory implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked 'wp_head' - 999999
     * @hooked 'wp_print_footer_scripts' - 999999
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        /**
         * With "PHP_INT_MAX - 100" the assets will be added as late as possible
         * but for some reason if you need to add your inline asset after this
         * with the "- 100" you can have some space to append custom code.
         * For example you can use "PHP_INT_MAX - 50" and you code will be appended
         * after this asset.
         */
        return [
            // 'hook_name'              => 'method_name',
            'wp_head'   => ['function_to_add'   => 'inline_css', 'priority'          => PHP_INT_MAX - 100],
            'wp_print_footer_scripts'   => ['function_to_add'   => 'inline_javascript', 'priority'          => PHP_INT_MAX - 100],
        ];
    }

    /**
     * Minify object
     *
     * @var Minify
     */
    protected $minify;

    /**
     * Constructor
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function __construct(Minify $minify)
    {
        $this->minify = $minify;
    }

    /**
     * Print inline script in footer after all and before shotdown hook.
     *
     * @todo Creare un sistema che appenda regolarmente dopo gli script
     *       e tenga presente delle dipendenze da jquery
     */
    public function inline_javascript()
    {

        $script = apply_filters('italystrap_custom_inline_script', Inline_Script::get());

        if (! $script) {
            return;
        }

        printf(
            '<script type="text/javascript" id="custom-inline-js">/*<![CDATA[*/%s/*]]>*/</script>',
            // strip_tags( $this->minify->js( $script ) )
            $this->minify->js($script)
        );
    }

    /**
     * Print inline script in footer after all and before shotdown hook.
     *
     * @todo Creare un sistema che appenda regolarmente dopo gli script
     *       e tenga presente delle dipendenze da jquery
     */
    public function inline_json()
    {

        $script = apply_filters('italystrap_custom_inline_script', Inline_Script::get());

        if (! $script) {
            return;
        }

        // "<script type='application/ld+json'>" . wp_json_encode( $script ) . '</script>' . "\n"

        printf(
            '<script type="text/javascript" id="custom-inline-js">/*<![CDATA[*/%s/*]]>*/</script>',
            // strip_tags( $this->minify->js( $script ) )
            $this->minify->js($script)
        );
    }

    /**
     * Print inline css.
     */
    public function inline_css()
    {

        $css = apply_filters('italystrap_custom_inline_style', Inline_Style::get());

        if (empty($css)) {
            return;
        }

        printf(
            '<style id="custom-inline-css">%s</style>',
            strip_tags($this->minify->css($css))
        );
    }
}
