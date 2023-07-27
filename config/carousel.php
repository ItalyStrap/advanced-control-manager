<?php

/**
 * Array definition for carousel default options
 *
 * @package ItalyStrap
 */

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

/**
 * Definition array() with all the options connected to the
 * module which must be called by an include (setoptions).
 */
return [
    /**
     * Ids for the images to use.
     */
    'ids'               => false,
    /**
     * Type of gallery. If it's not "carousel", nothing will be done.
     */
    'type'              => '',
    /**
     * Alternative appearing order of images.
     */
    'orderby'           => '',
    /**
     * Any name. String will be sanitize to be used as HTML ID. Recomended
     * when you want to have more than one carousel in the same page.
     * Default: italystrap-bootstrap-carousel.
     * */
    'name'              => 'italystrap-bootstrap-carousel',
    /**
     * Carousel container width, in px or %
     */
    'width'             => '',
    /**
     * Carousel item height, in px or %
     */
    'height'            => '',
    /**
     * Accepted values: before-inner, after-inner, after-control, false.
     * Default: before-inner.
     * */
    'indicators'        => 'before-inner',
    /**
     * Enable or disable arrow right and left
     * Accepted values: true, false. Default: true.
     */
    'control'           => 'true',
    /**
     * Add custom control icon
     * @todo Aggiungere la possibilità di poter decidere quali simbili
     *       usare come selettori delle immagini (@see Vedi sotto)
     * Enable or disable arrow from Glyphicons
     * Accepted values: true, false. Default: true.
     * 'arrow' => 'true',
     */
    /**
     * Add custom control icon
     * @todo Aggiungere inserimento glyphicon nello shortcode
     *       decidere se fare inserire tutto lo span o solo l'icona
     * 'control-left' => '<span class="glyphicon glyphicon-chevron-left"></span>',
     * 'control-right' => '<span class="glyphicon glyphicon-chevron-right"></span>',
     */
    /**
     * The amount of time to delay between automatically
     * cycling an item in milliseconds.
     * @type integer Example 5000 = 5 seconds.
     * Default 0, carousel will not automatically cycle.
     * @link http://www.smashingmagazine.com/2015/02/09/carousel-usage-exploration-on-mobile-e-commerce-websites/
     */
    'interval'          => 0,
    /**
     * Pauses the cycling of the carousel on mouseenter and resumes the
     * cycling of the carousel on mouseleave.
     * @type string Default hover.
     */
    'pause'             => 'hover',
    /**
     * Define tag for image title. Default: h4.
     */
    'titletag'          => 'h4',
    /**
     * Show or hide image title. Set false to hide. Default: true.
     */
    'image_title'       => 'true',
    /**
     * Type of link to show if "title" is set to true.
     * Default Link Parameters file, none, link
     */
    'link'              => '',
    /**
     * Show or hide image text. Set false to hide. Default: true.
     */
    'text'              => 'true',
    /**
     * Auto-format text. Default: true.
     */
    'wpautop'           => 'true',
    /**
     * Extra class for container.
     */
    'containerclass'    => '',
    /**
     * Extra class for item.
     */
    'itemclass'         => '',
    /**
     * Extra class for caption.
     */
    'captionclass'      => '',
    /**
     * Size for image attachment. Accepted values: thumbnail, medium,
     * large, full or own custom name added in add_image_size function.
     * Default: full.
     * @see wp_get_attachment_image_src() for further reference.
     */
    'size'              => 'full',
    /**
     * Activate responsive image. Accepted values: true, false.
     * Default false.
     */
    'responsive'        => false,
    /**
     * Size for image attachment. Accepted values: thumbnail, medium,
     * large, full or own custom name added in add_image_size function.
     * Default: large.
     * @see wp_get_attachment_image_src() for further reference.
     */
    'sizetablet'        => 'large',
    /**
     * Size for image attachment. Accepted values: thumbnail, medium,
     * large, full or own custom name added in add_image_size function.
     * Default: medium.
     * @see wp_get_attachment_image_src() for further reference.
     */
    'sizephone'         => 'medium',
];
