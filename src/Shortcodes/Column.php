<?php

/**
 * Column API
 *
 * Column Base class
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
 * Column
 */
class Column extends Shortcode
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
            'label'         => __('Column settings', 'italystrap'),
            /** Icon or an image attachment for shortcode. Optional. src or dashicons-$icon.  */
            'listItemImage' => 'dashicons-flag',
            /** Shortcode Attributes */
            'attrs'         => [
                /**
                 * Each attribute that accepts user input will have its own array defined like this
                 * Our shortcode accepts two parameters or attributes, title and URL
                 * Lets first define the UI for title field.
                 */
                [
                    /** This label will appear in user interface */
                    'label'         => __('CSS ID', 'italystrap'),
                    /** This is the actual attr used in the code used for shortcode */
                    'attr'          => 'id',
                    /** Define input type. Supported types are text, checkbox, textarea, radio, select, email, url, number, and date. */
                    'type'          => 'text',
                    /** Add a helpful description for users */
                    'description'   => __('Enter a CSS ID for the element, this must be unique. (Optional)', 'italystrap'),
                ],
                /** Now we will define UI for the URL field */
                ['label'         => __('CSS class', 'italystrap'), 'attr'          => 'class', 'type'          => 'text', 'description'   => __('Enter CSS classes for the element. (Optional)', 'italystrap')],
            ],
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

        $this->attr = shortcode_atts($this->default, $attr, 'column');
        $this->content = $content;

        /**
         * Filters the default column shortcode output.
         *
         * If the filtered output isn't empty, it will be used instead of generating
         * the default column template.
         *
         * @since
         *
         * @see ItalyStrap\Shortcode\Shortcode()
         *
         * @param string $html     The column output. Default empty.
         * @param array  $attr     Attributes of the column shortcode.
         * @param int    $instance Unique numeric ID of this column shortcode instance.
         */
        $html = apply_filters('italystrap_shortcode_column', '', $this->attr, self::$instance);
        if ('' !== $html) {
            return $html;
        }

        $html = sprintf(
            '<div %1$s>%2$s</div>',
            \ItalyStrap\Core\get_attr('shortcode_column', $this->attr),
            do_shortcode($this->content)
        );

        return $html;
    }
}
