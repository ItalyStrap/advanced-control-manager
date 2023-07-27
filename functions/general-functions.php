<?php

declare(strict_types=1);

namespace ItalyStrap\Core;

use Auryn\Injector;
use ItalyStrap\Lazyload\ImageSubscriber;

/**
 * Combine user attributes with known attributes and fill in defaults when needed.
 *
 * The pairs should be considered to be all of the attributes which are
 * supported by the caller and given as a list. The returned attributes will
 * only contain the attributes in the $pairs list.
 *
 * If the $atts list has unsupported attributes, then they will be ignored and
 * removed from the final returned list.
 *
 * @param array  $pairs     Entire list of supported attributes and their defaults.
 * @param array  $atts      User defined attributes in shortcode tag.
 * @param string $shortcode Optional. The name of the shortcode, provided for context to enable filtering.
 * @return array Combined and filtered attribute list.
 *@since 2.0.0
 *
 */
function shortcode_atts_multidimensional_array(array $pairs, array $atts, $shortcode = '')
{

    $atts = (array) $atts;

    $array = [];

    foreach ($pairs as $name => $default) {
        if (array_key_exists($name, $atts)) {
            $array[ $name ] = $atts[ $name ];
        } else {
            $array[ $name ] = ( ( ! empty($default['default']) ) ? $default['default'] : '' );
        }
    }

    /**
     * Filter a shortcode's default attributes.
     *
     * If the third parameter of the shortcode_atts() function is present then this filter is available.
     * The third parameter, $shortcode, is the name of the shortcode.
     *
     * @param array  $array       The output array of shortcode attributes.
     * @param array  $pairs     The supported attributes and their defaults.
     * @param array  $atts      The user defined shortcode attributes.
     * @param string $shortcode The shortcode name.
     */
    if ($shortcode) {
        $array = apply_filters("shortcode_atts_multidimensional_array_{$shortcode}", $array, $pairs, $atts, $shortcode);
    }

    return $array;
}

/**
 * Get options default
 *
 * @param  array $config The configuration array.
 *
 * @return array         Return the array with options default.
 */
function get_default_from_config(array $config = [])
{

    $options_default = [];

    foreach ($config as $value) {
        foreach ($value['settings_fields'] as $settings_fields_value) {
            $default = $settings_fields_value['args']['default'] ?? null;
            $options_default[ $settings_fields_value['id'] ] = $default;
        }
    }

    return $options_default;
}

/**
 * Read and return file content
 *
 * @link https://tommcfarlin.com/reading-files-with-php/
 *
 * @param  file $filename   The file for lazyloading.
 *
 * @throws Exception If the file doesn't exist.
 *
 * @return string $content  Return the content of the file
 */
function get_file_content($filename)
{

    // Check to see if the file exists at the specified path.
    if (! file_exists($filename)) {
        throw new \Exception(__('The file doesn\'t exist.', 'italystrap'));
    }

    // Open the file for reading.
    $file_resource = fopen($filename, 'r');

    /**
     * Read the entire contents of the file which is indicated by
     * the filesize argument
     */
    $content = fread($file_resource, filesize($filename));
    fclose($file_resource);

    return $content;
}

/**
 * Get an array with the taxonomies list
 *
 * @param  string $tax The name of taxonomy type.
 * @return array       Return an array with tax list
 */
function get_taxonomies_list_array($tax)
{

    /**
     * Array of taxonomies
     *
     * @todo Make object cache, see https://10up.github.io/Engineering-Best-Practices/php/#performance
     * @todo Add a default value
     * @var array
     */
    $tax_arrays = get_terms($tax);

    $get_taxonomies_list_array = [];

    if ($tax_arrays && ! is_wp_error($tax_arrays)) {
        foreach ($tax_arrays as $tax_array) {
            $get_taxonomies_list_array[ $tax_array->term_id ] = $tax_array->name;
        }
    }

    return $get_taxonomies_list_array;
}

/**
 * Get the size media registered
 *
 * @param  array $custom_size Custom size.
 * @return array              Return the array with all media size plus custom size
 */
function get_image_size_array($custom_size = [])
{

    static $image_size_media = null;

    if (! $image_size_media) {

        /**
         * Instance of list of image sizes
         *
         * @var \ItalyStrap\Image\Size
         */
        $image_size_media = new \ItalyStrap\Image\Size();
    }

    return (array) $image_size_media->get_image_sizes();
}

/**
 * Convert open square and closed square in < > and
 * allow you to put some HTML tag in title, widget and post.
 *
 * Function from HTML Widget Text plugins
 *
 * @author Jaimyn Mayer https://jabelone.com.au
 *
 * @package ItalyStrap
 * @since 2.0.0
 * @param  string $title The title.
 * @return string        The title converted
 */
function render_html_in_title_output($title)
{

    $tagopen = '{{'; // Our HTML opening tag replacement.
    $tagclose = '}}'; // Our HTML closing tag replacement.

    $title = str_replace($tagopen, '<', $title);
    $title = str_replace($tagclose, '>', $title);

    return $title;
}

/**
 * Return the instance of Mobile Detect
 * Use this function with apply_filter and then add_filter
 * Example:
 * Add this snippet where you want use the object
 * $detect = '';
 * $detect = apply_filters( 'mobile_detect', $detect );
 *
 * Then use add_filter to append object
 * add_filter( 'mobile_detect', 'ItalyStrap\Core\new_mobile_detect' );
 *
 * @see class-carousel.php for more information
 * @param  null $mobile_detect An empty variable.
 * @return object              Instance of Mobiloe detect
 */
function new_mobile_detect($mobile_detect)
{

    $mobile_detect = new \Detection\MobileDetect();
    /**
     * Istantiate Mobile_Detect class for responive use
     *
     * @todo Passare l'istanza dentro la classe http://stackoverflow.com/a/10634148
     * @var obj
     */
    return $mobile_detect;
}

/**
 * Kill the emojis
 *
 * @author Ryan Hellyer
 * @link https://geek.hellyer.kiwi/
 *
 * @since 2.0.0
 * @version 1.5.1 (Version of the original plugin)
 */
function kill_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'kill_emojis_tinymce');
}

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @author Ryan Hellyer
 * @link https://geek.hellyer.kiwi/
 *
 * @since 2.0.0
 * @version 1.5.1 (Version of the original plugin)
 *
 * @param    array  $plugins
 * @return   array           Difference betwen the two arrays
 */
function kill_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, ['wpemoji']);
    } else {
        return [];
    }
}

/**
 * Register widget
 * Test function from LucaTume
 * @todo Make some test.
 *
 * @param  object $widget [description].
 */
// function register_widget( $widget ) {
//  global $wp_widget_factory;

//  $wp_widget_factory->widgets[ get_class( $widget ) ] = $widget;
// }

/**
 * Remove widget title
 *
 * @hooked 'widget_title' - 999
 *
 * @author Stephen Cronin <http://www.scratch99.com/>
 *
 * @param  string      $widget_title The widget title.
 *
 * @return string/null               The widget title or null.
 */
function remove_widget_title($widget_title)
{

    if (substr($widget_title, 0, 2) === '!!') {
        return;
    }

    return $widget_title;
}

if (! function_exists('ItalyStrap\Core\get_attr')) {

    /**
     * Build list of attributes into a string and apply contextual filter on string.
     *
     * The contextual filter is of the form `italystrap_attr_{context}_output`.
     *
     * @since 4.0.0
     *
     * @see In general-function on the plugin.
     *
     * @param  string $context    The context, to build filter name.
     * @param  array  $attributes Optional. Extra attributes to merge with defaults.
     * @param  bool   $echo       True for echoing or false for returning the value.
     *                            Default false.
     * @param  null   $args       Optional. Extra arguments in case is needed.
     *
     * @return string String of HTML attributes and values.
     */
    function get_attr($context, array $attr = [], $echo = false, $args = null)
    {

        if (! $echo) {
            return \ItalyStrap\HTML\get_attr($context, $attr, false, $args);
        }

        echo \ItalyStrap\HTML\get_attr($context, $attr, false, $args);
    }
}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * @uses locate_template( $template_names );
 *
 * This allow to override the template used in plugin.
 *
 * @since 2.0.0
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @return string The template filename if one is located.
 */
function get_template($template_names)
{

    $located = '';

    $located = locate_template($template_names);

    if ('' === $located) {
        return ITALYSTRAP_PLUGIN_PATH . $template_names;
    }

    return $located;
}

/**
 * Retrieves the attachment ID from the file URL
 *
 * @link https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 *
 * @param  string $image_url The src of the image.
 *
 * @return int               Return the ID of the image
 */
function get_image_id_from_url($image_url)
{

    $attachment = wp_cache_get('get_image_id' . $image_url);

    if (false === $attachment) {
        global $wpdb;
        $attachment = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT ID
				FROM $wpdb->posts
				WHERE guid='%s';",
                $image_url
            )
        );
        wp_cache_set('get_image_id' . $image_url, $attachment);
    }

    $attachment[0] ??= null;

    return absint($attachment[0]);
}

/**
 * Get global
 *
 * @param string $name
 * @return string
 */
function get_global($name = '')
{
    global ${$name};
    return ${$name};
}

/**
 * Return img tag lazyloaded
 * @param string $content Text content to be processed
 * @return string          Text content processed
 * @throws \Auryn\InjectionException
 */
function get_apply_lazyload(string $content): string
{
    /** @var Injector $injector */
    if (! $injector = \apply_filters('italystrap_injector', false)) {
        return $content;
    }

    /** @var ImageSubscriber $lazy */
    $lazy = $injector->make(ImageSubscriber::class);
    return $lazy->replaceSrcImageWithSrcPlaceholders($content);
}

/**
 * Echo img tag lazyloaded
 * @param string $content Text content to be processed
 * @return void Text content processed
 * @throws \Auryn\InjectionException
 */
function apply_lazyload(string $content): void
{
    echo get_apply_lazyload($content);
}
