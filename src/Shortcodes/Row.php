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

        $this->default = array(
            'id'            => '',
            'class'         => '',
            'style'         => '',
        );

        $this->shortcode_ui = array(

            /** Label for your shortcode user interface. This part is required. */
            'label'         => __('Row container settings', 'italystrap'),

            /** Icon or an image attachment for shortcode. Optional. src or dashicons-$icon.  */
            'listItemImage' => 'dashicons-flag',

            /** Shortcode Attributes */
            'attrs'         => array(

                /**
                * Each attribute that accepts user input will have its own array defined like this
                * Our shortcode accepts two parameters or attributes, title and URL
                * Lets first define the UI for title field.
                */

                // array(

                //  /** This label will appear in user interface */
                //  'label'         => __( 'CSS ID', 'italystrap' ),

                //  /** This is the actual attr used in the code used for shortcode */
                //  'attr'          => 'id',

                //  /** Define input type. Supported types are text, checkbox, textarea, radio, select, email, url, number, and date. */
                //  'type'          => 'text',

                //  * Add a helpful description for users
                //  'description'   => __( 'Enter a CSS ID for the element, this must be unique. (Optional)', 'italystrap' ),
                // ),

                // /** Now we will define UI for the URL field */

                // array(
                //  'label'         => __( 'CSS class', 'italystrap' ),
                //  'attr'          => 'class',
                //  'type'          => 'text',
                //  'description'   => __( 'Enter CSS classes for the element. (Optional)', 'italystrap' ),
                // ),

                /** Finally we will define the UI for Color Selection */
                // array(
                //  'label' => 'Color',
                //  'attr'  => 'color',

                //  /** We will use select field instead of text */
                //  'type'  => 'select',
                //      'options' => array(
                //          'blue'      => 'Blue',
                //          'orange'    => 'Orange',
                //          'green'     => 'Green',
                //      ),
                // ),

            ),

            /** You can select which post types will show shortcode UI */
            // 'post_type'      => array( 'post', 'page' ),
        );
    }

    /**
     * Render the output
     *
     * @param  array  $attr    The attribute for the shortcode.
     * @param  array  $content The content for the shortcode.
     *
     * @return string          The output of the shortcode.
     */
    public function render($attr = array(), $content = "")
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
