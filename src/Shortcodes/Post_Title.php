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

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

/**
 * Post_Title
 */
class Post_Title extends Shortcode
{
    static $instance = 0;

    /**
     * [__construct description]
     *
     * @param [type] $argument [description].
     */
    public function __construct()
    {

        self::$instance++;

        $shortcode_ui_default = [
            /** This label will appear in user interface */
            'label'         => '',
            /** This is the actual attr used in the code used for shortcode */
            'attr'          => '',
            /** Define input type. Supported types are text, checkbox, textarea, radio, select, email, url, number, and date. */
            'type'          => 'text',
            /** Add a helpful description for users */
            'description'   => '',
        ];

        $this->config = require(ITALYSTRAP_PLUGIN_PATH . 'config/post-title.php');

        $this->shortcode_ui = [
            /** Label for your shortcode user interface. This part is required. */
            'label'         => __('Post Title', 'italystrap'),
            /** Icon or an image attachment for shortcode. Optional. src or dashicons-$icon.  */
            'listItemImage' => 'dashicons-flag',
            /** You can select which post types will show shortcode UI */
            // 'post_type'      => array( 'post', 'page' ),
            /** Shortcode Attributes */
            'attrs'         => [],
        ];

        $new_attr = [];

        foreach ($this->config as $key => $value) {
            if (isset($value['show_on']) && ! $value['show_on']) {
                continue;
            }

            // $default = isset( $value['default'] ) && '0' !== $value['default'] ? $value['default'] : '';
            $default = $value['default'] ?? '';

            $new_attr = ['label'         => $value['label'], 'description'   => $value['desc'], 'attr'          => $key, 'type'          => $value['type']];

            if (isset($value['default']) && false != $value['default']) {
                /**
                 * Il problema:
                 * Per settare la checkbox l'unico valore accettato è 'true'
                 * con le virgolette e a causa di questo non comprende tutti
                 * gli altri casi tipo, true (senza virgolette), 1, '1', 'y', 'yes'
                 *
                 * Inoltre se viene tolta la spunta non setta il valore almeno su false
                 *
                 * 'value' va settata solo se dafault è true altrimenti stampa
                 * tutti i parametri nello shortcode e questo diventa un problema
                 * per shortcode con molt parametri.
                 *
                 * Altro problema potrebbe essere che togliendo la spunta
                 * o il valore da una input e questo viene tolto anche dallo shortcode
                 * quando viene renderizzato nel front-end viene utilizzato
                 * comunque il valore di default impostato in config quindi
                 * se non si vuole renderizzare quel parametro deve essere presente
                 * e deve essere impostato su un valore false.
                 *
                 * Sostituisco la checkbox con una select per risolvere il problema
                 * indicato sopra ma solo per i valori di default imostati su true
                 * in modo da mantenere il valore anche nello shortcode.
                 */
                if ('checkbox' === $value['type']) {
                    $new_attr['type'] = 'select';
                    $new_attr['options'] = ['1' => 'True', '0' => 'False'];
                    $new_attr['value'] = $value['default'];
                    // $new_attr['value'] = '1';
                    // $new_attr['value'] = 'true';
                    // $new_attr['default'] = $value['default'];
                }
                // else {
                //  $new_attr['value'] = $value['default'];
                //  // $new_attr['default'] = $value['default'];
                // }
            }

            /**
             * Al momento mettu una fallback su text siccome le multiselect non
             * esistono in shortcake
             */
            if ('multiple_select' === $value['type'] || 'taxonomy_multiple_select' === $value['type']) {
                $new_attr['type'] = 'text';
            }

            if ('media' === $value['type']) {
                $new_attr['type'] = 'attachment';
                $new_attr['libraryType'] = ['image'];
                $new_attr['addButton'] = esc_html__('Select Image', 'italystrap');
                $new_attr['frameTitle'] = esc_html__('Select Image', 'italystrap');
            }

            if (isset($value['options'])) {
                $new_attr['options'] = $value['options'];
            }

            $this->default[ $key ] = $default;

            $this->shortcode_ui['attrs'][] = $new_attr;
        }
    }

    /**
     * Dispay the widget content
     *
     * @param  array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param  array $instance The settings for the particular instance of the widget.
     * @return string           Return the output
     */
    public function shortcode_render(array $attr = [], $content = "")
    {

        $title = get_the_title($attr['post_id']);

        if (empty($title)) {
            return '';
        }

        $post_title_attr = ['class' => $attr['class'], 'id'    => $attr['id'], 'style' => $attr['style']];

        $tag_before = sprintf(
            '<%s%s>',
            esc_attr($attr['html_tag']),
            \ItalyStrap\Core\get_attr('post_title', $post_title_attr)
        );

        $tag_after = '</' . esc_attr($attr['html_tag']) . '>';

        return $tag_before . esc_html($attr['before']) . $title . esc_html($attr['after']) . $tag_after;
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

        $attr = shortcode_atts($this->default, $attr, 'post_title');

        return $this->shortcode_render($attr, $content);
    }
}
