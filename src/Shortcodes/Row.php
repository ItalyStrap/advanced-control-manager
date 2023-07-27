<?php

/**
 * Row API
 *
 * Row Base class
 *
 * @link www.italystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Shortcodes;

if (! defined('ITALYSTRAP_PLUGIN') or ! ITALYSTRAP_PLUGIN) {
    die();
}

/**
 * Row
 */
class Row extends Shortcode
{
    static $instance = 0;

    /**
     * [__construct description]
     *
     * @param [type] $argument [description].
     */
    public function __construct($argument = null)
    {
        self::$instance++;

        $this->default = ['id'            => '', 'class'         => '', 'style'         => ''];

        $this->shortcode_ui = [
            /** Label for your shortcode user interface. This part is required. */
            'label'         => __('Row container settings', 'italystrap'),
            /** Icon or an image attachment for shortcode. Optional. src or dashicons-$icon.  */
            'listItemImage' => 'dashicons-flag',
            /** Shortcode Attributes */
            'attrs'         => [],
        ];
    }

    /**
     * Render the output
     *
     * @param  array  $attr    The attribute for the shortcode.
     * @param  array  $content The content for the shortcode.
     *
     * @return string          The output of the shortcode.
     */
    public function render($attr = [], $content = "")
    {

        $this->attr = shortcode_atts($this->default, $attr, 'row');
        $this->content = $content;

        /**
         * Filters the default row shortcode output.
         *
         * If the filtered output isn't empty, it will be used instead of generating
         * the default row template.
         *
         * @since
         *
         * @see ItalyStrap\Shortcode\Shortcode()
         *
         * @param string $html     The row output. Default empty.
         * @param array  $attr     Attributes of the row shortcode.
         * @param int    $instance Unique numeric ID of this row shortcode instance.
         */
        $html = apply_filters('italystrap_shortcode_row', '', $this->attr, self::$instance);
        if ('' !== $html) {
            return $html;
        }

        $html = sprintf(
            '<div %1$s>%2$s</div>',
            \ItalyStrap\Core\get_attr('shortcode_row', $this->attr),
            do_shortcode($this->content)
        );

        return $html;
    }
}
