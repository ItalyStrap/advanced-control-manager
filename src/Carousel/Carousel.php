<?php

/**
 * ItalyStrap Carousel initially forked from Agnosia Bootstrap Carousel by AuSoft
 *
 * Display a Bootstrap Carousel based on selected images and their titles and
 * descriptions. You need to include the Bootstrap CSS and Javascript files on
 * your own; otherwise the class will not work.
 *
 * @see https://www.smashingmagazine.com/2016/07/ten-requirements-for-making-home-page-carousels-work-for-end-users/ 10 requirements for better carousel
 *
 * @todo http://www.kennethcachia.com/background-check/index.html Analizza il colore di sfondo per dare un colore di contrasto agli andicatori e freccie.
 *
 * @link www.italystrap.com
 * @since 2.0.0
 *
 * @version 1.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Carousel;

use ItalyStrap\Asset\Inline_Script;
use ItalyStrap\Core;

/**
 * The Carousel Bootsrap class
 */
abstract class Carousel
{
    /**
     * The attribute for Carousel.
     *
     * @var array
     */
    public $args = [];

    /**
     * Array of WordPress $post objects.
     *
     * @var array
     */
    public $posts = [];

    /**
     * The width of the container
     *
     * @var string
     */
    public $container_style = '';

    /**
     * The height of the item
     *
     * @var string
     */
    public $item_style = '';

    /**
     * The carousel output
     *
     * @var string
     */
    public $output = '';

    /**
     * Initialize the contstructor
     *
     * @param array $args The carousel attribute.
     */
    function __construct($args)
    {

        $this->args = $this->get_attributes($args);

        if (! $this->can_i_run()) {
            return '';
        }

        do_action('italystrap_carousel_before_init');
        $this->container_style = $this->get_container_style();
        $this->item_style      = $this->get_item_style();
        $this->posts           = $this->get_posts();
        $this->output          = $this->get_output();
        do_action('italystrap_carousel_init');

        /**
         * Append javascript in static variable and print in front-end footer
         */
        Inline_Script::set($this->get_javascript());
    }

    /**
     * Magic methods __get
     *
     * @param  string $property The $property argument is the name of the property being interacted with.
     * @return string           Return the $property argument.
     */
    public function __get($property)
    {

        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * Magic methods __set
     *
     * @param string $property The $property argument is the name of the property being interacted with.
     * @param mixed  $value    The __set() method's $value argument specifies the value the $name'ed property should be set to.
     */
    public function __set($property, $value)
    {

        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    /**
     * Get shortcode attributes.
     *
     * @param  array $args The carousel attribute.
     * @return array Mixed array of shortcode attributes.
     */
    public function get_attributes($args)
    {

        /**
         * Define data by given attributes.
         *
         * @todo Usare il file options-media-carousel.php
         */
        $args = \ItalyStrap\Core\shortcode_atts_multidimensional_array(require(ITALYSTRAP_PLUGIN_PATH . 'config/media-carousel.php'), $args, 'gallery');
        // $args = shortcode_atts( require( ITALYSTRAP_PLUGIN_PATH . 'config/carousel.php' ), $args, 'gallery' );
        $args = apply_filters('italystrap_carousel_attributes', $args);

        return $args;
    }

    /**
     * Check if the received data can make a valid carousel.
     *
     * @return boolean
     */
    public function can_i_run()
    {
        return ! empty($this->args['ids']) && isset($this->args['type']) && 'carousel' === $this->args['type'];
    }

    /**
     * Obtain posts array by given IDs.
     *
     * @return array Array of WordPress $post objects.
     */
    public function get_posts()
    {

        $posts = [];

        $post_type_ids = (array) $this->make_array($this->args['ids'], $this->args['orderby']);

        if (is_array($post_type_ids) and ! empty($post_type_ids[0])) {
            foreach ($post_type_ids as $post_type_id) {
                $posts[] = get_post(intval($post_type_id), ARRAY_A);
            }
        }

        $posts = apply_filters('italystrap_carousel_posts', $posts, $this->args);

        return $posts;
    }

    /**
     * Define width of carousel container.
     *
     * @return string HTML result.
     */
    public function get_container_style()
    {

        $container_style = '';

        if ($this->args['width']) {
            $container_style = 'style="width:' . esc_attr($this->args['width']) . ';"';
        }

        $container_style = apply_filters('italystrap_carousel_container_style', $container_style, $this->args);

        return $container_style;
    }

    /**
     * Define height of carousel item.
     *
     * @return string HTML result.
     */
    public function get_item_style()
    {

        $item_style = '';

        if ($this->args['height']) {
            $item_style = 'style="height:' . esc_attr($this->args['height']) . ';"' ;
        }

        $item_style = apply_filters('italystrap_carousel_item_style', $item_style, $this->attributtes);

        return $item_style;
    }

    /**
     * Obtain complete HTML output for carousel.
     *
     * @return string HTML result.
     */
    public function get_output()
    {

        $count = count($this->posts);

        // Initialize carousel HTML.
        $output = $this->get_carousel_container('start');
        // Try to obtain indicators before inner.
        $output .= ( 'before-inner' === $this->args['indicators'] && $count > 1 ) ? $this->get_indicators() : '' ;
        // Initialize inner.
        $output .= $this->get_carousel_inner('start');
        // Start counter for posts iteration.
        $i = 0;

        /**
         * Process each item into $this->posts array and create HTML.
         *
         * @todo $post viene creato da get_post() che è usato anche dentro
         *       $this->get_img con la funzione wp_get_attachment_image()
         *       Valutare se è possibile ridurre a una sola chiamata di get_post()
         */
        foreach ($this->posts as $post) {
            if (! is_array($post)) {
                continue;
            }

            $class = ( 0 === $i ) ? 'active ' : '';
            $output .= $this->get_img_container('start', $i);
            $output .= $this->get_img($post, $i);
            if ('false' !== $this->args['image_title'] || 'false' !== $this->args['text']) {
                $output .= $this->get_caption_container('start');
                $output .= $this->get_title($post);
                $output .= $this->get_excerpt($post);
                $output .= $this->get_permalink($post);
                $output .= $this->get_caption_container('end');
            }
            $output .= $this->get_img_container('end');
            $i++;
        }

        // End inner.
        $output .= $this->get_carousel_inner('end');
        // Try to obtain indicators after inner.
        $output .= ( 'after-inner' === $this->args['indicators'] && $count > 1 ) ? $this->get_indicators() : '' ;
        // Obtain links for carousel control.
        $output .= ( 'false' !== $this->args['control'] && $count > 1 ) ? $this->get_control() : '' ;
        // Try to obtain indicators after control.
        $output .= ( 'after-control' === $this->args['indicators'] && $count > 1 ) ? $this->get_indicators() : '' ;
        // End carousel HTML.
        $output .= $this->get_carousel_container('end');

        $output = apply_filters('italystrap_carousel_output', $output, $this->args);

        return $output;
    }

    /**
     * Get starting and ending HTML tag for carousel container.
     *
     * @param  string $position Indicator for starting or ending tag.
     * @return string           HTML result.
     */
    public function get_carousel_container($position)
    {

        $output = '';

        switch ($position) {
            case 'start':
                $output .= '<div id="' . esc_attr($this->args['name']) . '" class="carousel slide ' . esc_attr($this->args['containerclass']) . '" ' . $this->container_style . '>';
                break;
            case 'end':
                $output .= '</div>';
                break;
            default:
                // Do nothing.
                break;
        }

        $output = apply_filters('italystrap_carousel_container', $output, $this->args);

        return $output;
    }

    /**
     * Get starting and ending HTML tag for carousel inner element.
     *
     * @param  string $position Indicator for starting or ending tag.
     * @return string           HTML result.
     */
    public function get_carousel_inner($position)
    {

        $output = '';

        switch ($position) {
            case 'start':
                $output = '<div class="carousel-inner" itemscope itemtype="http://schema.org/ImageGallery">';
                break;
            case 'end':
                $output = '</div>';
                break;
            default:
                // Do nothing.
                break;
        }

        $output = apply_filters('italystrap_carousel_inner', $output, $this->args);

        return $output;
    }

    /**
     * Get starting and ending HTML tag for container of a caption.
     *
     * @param  string $position Indicator for starting or ending tag.
     * @return string           HTML result.
     */
    public function get_caption_container($position)
    {

        $output = '';

        switch ($position) {
            case 'start':
                $output .= '<div class="carousel-caption ' . esc_attr($this->args['captionclass']) . '" itemprop="caption">';
                $output = apply_filters('italystrap_carousel_caption_container_start', $output);
                break;
            case 'end':
                $output .= '</div>';
                $output = apply_filters('italystrap_carousel_caption_container_end', $output);
                break;
            default:
                // Do nothing.
                break;
        }

        $output = apply_filters('italystrap_carousel_caption_container', $output, $this->args);

        return $output;
    }

    /**
     * Get starting and ending HTML tag for container of a gallery item.
     *
     * @param  string  $position Indicator for starting or ending tag.
     * @param  integer $i        Position of the current item into carousel.
     * @return string            HTML result.
     */
    public function get_img_container($position, $i = 0)
    {

        $output = '';

        switch ($position) {
            case 'start':
                $class = ( 0 === $i ) ? 'active ' : '';
                $output .= '<div class="' . $class . 'item carousel-item ' . esc_attr($this->args['itemclass']) . '" data-slide-no="' . $i . '" ' . $this->item_style . ' itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
                break;
            case 'end':
                $output .= '</div>';
                break;
            default:
                // Do nothing.
                break;
        }

        $output = apply_filters('italystrap_carousel_img_container', $output, $this->args);

        return $output;
    }

    /**
     * Get HTML-formatted image for a carousel item.
     *
     * @param  array $post           A WordPress $post object.
     * @param  array $schemaposition The position of the item for schema.org.
     * @return string                HTML result.
     */
    public function get_img($post, $schemaposition)
    {

        $post_thumbnail_id = ( 'attachment' === $post['post_type'] ) ? ( (int) $post['ID'] ) : ( (int) get_post_thumbnail_id($post['ID']) ) ;

        if (empty($post_thumbnail_id)) {
            return;
        }

        $size_class = $this->get_img_size_attr();

        $img_attr = [];

        /**
         * Get the image attribute
         * [0] => url
         * [1] => larghezza
         * [2] => altezza
         * [3] => boolean: true se $url è un'immagine ridimensionata,
         *        false se è l'originale.
         *
         * @var array
         */
        $img_attr = wp_get_attachment_image_src($post_thumbnail_id, $size_class);

        $attr = ['class'     => "center-block img-responsive attachment-$size_class size-$size_class", 'itemprop'  => 'image', 'style'     => 'max-height:' . absint($img_attr[2]) . 'px'];

        $output = wp_get_attachment_image($post_thumbnail_id, $size_class, false, $attr);

        $output .= $this->get_img_metadata($post_thumbnail_id, $post, $img_attr, $schemaposition);

        $output = apply_filters('italystrap_carousel_img', $output, $img_attr, $this->args, $post);

        return $output;
    }

    /**
     * Get the image metadata (built for get the exif data).
     *
     * @param  int   $id             ID.
     * @param  array $post           Array of post.
     * @param  array $img_attr       Attributes of image.
     * @param  int   $schemaposition The position number of image.
     * @return string                The metadata fo image.
     */
    public function get_img_metadata($id, array $post, array $img_attr, $schemaposition)
    {

        /**
         * Array for exifData informations.
         *
         * @link https://wordpress.org/support/topic/retrieving-exif-data?replies=5
         * @var array
         */
        $imgmeta = wp_get_attachment_metadata($id);
        $imgmeta = $imgmeta['image_meta'] ?? [];

        /**
         * The metadata of the image.
         *
         * @var string
         */
        $metadata = sprintf(
            '<meta  itemprop="name" content="%s"/><meta  itemprop="width" content="%s"/><meta  itemprop="height" content="%s"/><meta  itemprop="position" content="%s"/>',
            esc_attr($post['post_title']),
            absint($img_attr[1]),
            absint($img_attr[2]),
            $schemaposition
        );

        foreach ($imgmeta as $key => $value) {
            if (empty($value)) {
                continue;
            }
            /**
             * 14/03/2017 Ho aggiunto is_array perché in caso dovesse esistere
             * una $key = 'keyword' allora questa sarà probabilmente un array.
             */
            $metadata .= sprintf(
                '<meta  itemprop="exifData" content="%s: %s"/>',
                esc_attr($key),
                is_array($value) ? esc_attr($value[0]) : esc_attr($value)
            );
        }

        return $metadata;
    }

    /**
     * Get the image size attribute from user options and selecte wich to use on desktop, tablet or mobile.
     *
     * @return string The image size selected
     */
    public function get_img_size_attr()
    {

        /**
         * This filter is added in init.php
         *
         * @var Detection\MobileDetect
         */
        $detect = apply_filters('mobile_detect', null);

        /**
         * Set the initial image size
         *
         * @var string
         */
        $image_size = $this->args['size'];

        if (! ( $detect instanceof \Detection\MobileDetect )) {
            return $image_size;
        }

        if ($detect->isTablet() && $this->args['responsive']) {
            $image_size = $this->args['sizetablet'];
        } elseif ($detect->isMobile() && $this->args['responsive']) {
            $image_size = $this->args['sizephone'];
        }

        return esc_attr($image_size);
    }

    /**
     * Obtain the HTML-formatted title for post_type.
     *
     * @param array $post The post object.
     * @return string HTML result.
     */
    public function get_title($post)
    {

        if (empty($this->args['image_title'])) {
            return '';
        }

        if ('false' === $this->args['image_title']) {
            return '';
        }

        $post_thumbnail_id = 'attachment' === $post['post_type'] ? (int) $post['ID'] : (int) get_post_thumbnail_id($post['ID']);

        if (empty($post_thumbnail_id)) {
            return '';
        }

        $size_class = $this->get_img_size_attr();
        $img_attr = wp_get_attachment_image_src($post_thumbnail_id, $size_class);

        $link_file = 'attachment' === $post['post_type'] ? $post['guid'] : $img_attr[0];

        $output = '';

        $post['post_title'] = apply_filters('the_title', $post['post_title'], $post['ID']);

        switch ($this->args['link']) {
            case 'file':
                $post_title = sprintf(
                    '<a href="%s" itemprop="url">%s</a>',
                    esc_url($link_file),
                    wp_kses_post($post['post_title'])
                );
                break;

            case 'parent':
                $post_title = sprintf(
                    '<a href="%s" itemprop="url">%s</a>',
                    esc_url(get_permalink($post['post_parent'])),
                    wp_kses_post($post['post_title'])
                );
                break;

            case 'none':
                // $post_title = esc_attr( $post['post_title'] );
                $post_title = wp_kses_post($post['post_title']);
                break;

            default:
                $post_title = sprintf(
                    '<a href="%s" itemprop="url">%s</a>',
                    esc_url(get_permalink($post['ID'])),
                    wp_kses_post($post['post_title'])
                );
                break;
        }

        $output .= sprintf(
            '<%1$s class="slide-title">%2$s</%1$s>',
            esc_attr($this->args['titletag']),
            $post_title
        );

        $output = apply_filters('italystrap_carousel_title', $output, $this->args, $post);

        return $output;
    }

    /**
     * Get the excerpt for an image.
     * Didascalia
     *
     * @param array $post The post object.
     * @return string     HTML result.
     */
    public function get_excerpt($post)
    {

        if (empty($this->args['text'])) {
            return '';
        }

        /**
         * Da fare.
         *
         * @todo L'excerpt lo prende dal post se l'Id è quello di un post
         *       Valutare se farlo prendere dall'immagine come comportamento standard
         * @todo Testare meglio la funzionalità di checkbox, vecchie impostazioni
         *       shortcode true false, nuove impostazioni 1 0
         *       C'è qualche problema
         */

        $output = '';

        if ('false' !== $this->args['text']) {
            $output .= '1' === $this->args['wpautop'] || 'false' !== $this->args['wpautop']
            ? wpautop(wp_kses_post($post['post_excerpt']))
            : esc_attr($post['post_excerpt']);

            $output = '1' === $this->args['do_shortcode'] ? do_shortcode($output) : $output;
        } else {
            return $output;
        }

        $output = sprintf(
            '<div class="slide-description" itemprop="description">%s</div>',
            $output
        );

        $output = apply_filters('italystrap_carousel_excerpt', $output, $this->args, $post);

        return $output;
    }

    /**
     * Get post permalink or if it is an attachment get the parent if it is set.
     *
     * @param  string $post The post array.
     *
     * @return string       The permalink
     */
    public function get_permalink($post)
    {

        if (empty($this->args['link_button'])) {
            return '';
        }

        if (0 === $post['post_parent']) {
            return '';
        }

        $post_id = $post['ID'];

        if ('attachment' === $post['post_type']) {
            $post_id = $post['post_parent'];
        }

        return sprintf(
            '<a href="%1$s" class="%2$s">%3$s</a>',
            esc_url(get_permalink($post_id)),
            esc_attr($this->args['link_button_css_class']),
            esc_attr($this->args['link_button_text'])
        );
    }


    /**
     * Obtain indicators from $this->posts array.
     *
     * @return string HTML result.
     */
    public function get_indicators()
    {

        $output = '<ol class="carousel-indicators">';
        $i = 0;

        /**
         * Da fare.
         *
         * @todo fare il foreach solo sul numero degli ID validi nell'array degli ID
         */
        foreach ($this->posts as $post) {
                $class = ( 0 === $i ) ? 'active' : '';
                $output .= '<li data-target="#' . esc_attr($this->args['name']) . '" data-slide-to="' . $i . '" class="' . $class . '"></li>';
                $i++;
        }

        $output .= '</ol>';

        $output = apply_filters('italystrap_carousel_indicators', $output, $this->args);

        return $output;
    }


    /**
     * Obtain control links.
     *
     * @return string HTML control result.
     */
    public function get_control()
    {

        /**
         * THe output of the controllers.
         *
         * @todo Dare la possibilità di scegliere l'icona o l'inserimento di un carattere
         */
        $output = '<a class="carousel-control left" data-slide="prev" role="button" href="#' . esc_attr($this->args['name']) . '" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>';

        $output .= '<a class="carousel-control right" data-slide="next" role="button" href="#' . esc_attr($this->args['name']) . '" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>';

        $output = apply_filters('italystrap_carousel_control', $output, $this->args);

        return $output;
    }

    /**
     * Get Javascript for carousel.
     *
     * @return string HTML script tag.
     */
    function get_javascript()
    {

        $pause = '1' === $this->args['pause'] ? ',pause:"' . esc_js($this->args['pause']) . '"' : '' ;

        /**
         * LazyLoad for Bootstrap carousel
         * http://stackoverflow.com/questions/27675968/lazy-load-not-work-in-bootstrap-carousel
         * http://jsfiddle.net/51muqdLf/5/
         *
         *  Old function:
         *  var cHeight = 0;
         *
         *  var src = $nextImage.data("src");
         *  if (typeof src !== "undefined" && src !== ""){
         *      $nextImage.attr("src", src);
         *      $nextImage.data("src", "");
         *  }
         */
        $lazyload = sprintf(
            '
$("#%s").on("slide.bs.carousel", function(e){
	var $img;
	if ( "left" === e.direction ) {
		$img = $(".active.item", this).next(".item").find("img");
	} else {
		$img = $(".active.item", this).prev(".item").find("img");
	}
	$img.trigger("unveil");
});',
            esc_js($this->args['name'])
        );

        $output = sprintf(
            'jQuery(document).ready(function($){$("#%s").carousel({interval:%d%s});%s});',
            esc_js($this->args['name']),
            absint($this->args['interval']),
            $pause,
            $lazyload
        );

        $output = apply_filters('italystrap_carousel_javascript', $output, $this->args, $lazyload);

        return $output;
    }

    /**
     * Obtain array of id given comma-separated values in a string.
     *
     * @param  string $string  Comma-separated IDs of posts.
     * @param  string $orderby Alternative order for array to be returned.
     * @return array           Array of WordPress post IDs.
     */
    public function make_array($string, $orderby = '')
    {

        $array = explode(',', $string);

        $count = count($array);

        if (empty($array[ $count - 1 ])) {
            unset($array[ $count - 1 ]);
        }

        // Support for random order.
        if ('rand' === $orderby) {
            shuffle($array);
        }

        $array = apply_filters('italystrap_carousel_make_array', $array, $this->args);

        return $array;
    }
}
