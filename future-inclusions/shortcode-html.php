<?php
/**
 * [Short Description (no period for file headers)]
 *
 * [Long Description.]
 *
 * @link [URL]
 * @since [x.x.x (if available)]
 *
 * @package [Plugin/Theme/Etc]
 */


function html_tags( $atts, $content = null  ) {

    // $a = shortcode_atts( array(
    //     'foo' => 'something',
    //     'bar' => 'something else',
    // ), $atts );

    return '<' . $atts['tag'] . '>' . $content . '</' . $atts['tag'] . '>';
}
add_shortcode( 'html', 'ItalyStrap\Core\html_tags' );
