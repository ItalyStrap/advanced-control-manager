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
     * You can insert the ID of the media images or the ID of every post type (post, page, attachment, custom post type and so on) all separated by comma. Example: <code>1,2,3,4</code>
     */
    'ids'               => [
        'label'     => __('Media or Post Type ID', 'italystrap'),
        'desc'      => __('You can insert the ID of the media images or the ID of every post type (post, page, attachment, custom post type and so on) all separated by comma. Example: <code>1,2,3,4</code>', 'italystrap'),
        'id'        => 'ids',
        'type'      => 'media_list',
        'class'     => 'widefat ids',
        'default'   => false,
        // 'validate'   => 'numeric_comma',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Type of gallery. If it's not "carousel", nothing will be done.
     */
    'type'              => ['label'     => __('Type of gallery', 'italystrap'), 'desc'      => __('Enter the type of gallery, if it\'s not "carousel", nothing will be done.', 'italystrap'), 'id'        => 'type', 'type'      => 'select', 'class'     => 'widefat', 'class-p'   => 'hidden', 'default'   => 'carousel', 'options'   => ['standard'  => __('Standard Gallery', 'italystrap'), 'carousel'  => __('Carousel (Default)', 'italystrap')], 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
    /**
     * Alternative order for your images.
     */
    'orderby'           => ['label'     => __('Order Image By', 'italystrap'), 'desc'      => __('Alternative order for your images.', 'italystrap'), 'id'        => 'orderby', 'type'      => 'select', 'class'     => 'widefat', 'default'   => 'menu_order', 'options'   => ['menu_order'    => __('Menu order (Default)', 'italystrap'), 'title'         => __('Order by the image\'s title', 'italystrap'), 'post_date'     => __('Sort by date/time', 'italystrap'), 'rand'          => __('Order randomly', 'italystrap'), 'ID'            => __('Order by the image\'s ID', 'italystrap')], 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
    /**
     * String will be sanitize to be used as an HTML ID. Recommended when you want to have more than one carousel in the same page.
     * Default: italystrap-bootstrap-carousel.
     * */
    'name'              => [
        'label'     => __('Carousel Name', 'italystrap'),
        'desc'      => __('String will be sanitize to be used as an HTML ID. Recommended when you want to have more than one carousel in the same page.', 'italystrap'),
        'id'        => 'name',
        'type'      => 'text',
        'class'     => 'widefat',
        'default'   => 'italystrap-media-carousel-' . random_int(0, mt_getrandmax()),
        // 'validate'   => 'alpha_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'general',
    ],
    /**
     * Carousel container width, in px or % (optional). Default: empty. Example: 500px or 100%
     */
    'width'             => [
        'label'     => __('Carousel container width', 'italystrap'),
        'desc'      => __('Carousel container width, in px or % (optional). Default: empty. Example: 500px or 100%', 'italystrap'),
        'id'        => 'width',
        'type'      => 'text',
        'class'     => 'widefat',
        'default'   => '',
        // 'validate'   => 'numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'size',
    ],
    /**
     * Carousel item height, in px(optional). Default: empty. Example: 500px
     */
    'height'            => [
        'label'     => __('Carousel container height', 'italystrap'),
        'desc'      => __('Carousel item height, in px(optional). Default: empty. Example: 500px', 'italystrap'),
        'id'        => 'height',
        'type'      => 'text',
        'class'     => 'widefat',
        'default'   => '',
        // 'validate'   => 'numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'size',
    ],
    /**
     * Indicators position. Accepted values: before-inner, after-inner, after-control, false (hides indicators).
     * Default: before-inner.
     * */
    'indicators'        => ['label'     => __('Indicators', 'italystrap'), 'desc'      => __('Indicators position. Accepted values: before-inner, after-inner, after-control, false (hides indicators).', 'italystrap'), 'id'        => 'indicators', 'type'      => 'select', 'class'     => 'widefat', 'default'   => 'before-inner', 'options'   => ['before-inner'  => __('Before inner (Default)', 'italystrap'), 'after-inner'   => __('After inner', 'italystrap'), 'after-control' => __('After control', 'italystrap'), 'false'         => __('False', 'italystrap')], 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
    /**
     * Enable or disable arrow right and left. Accepted values: true, false. Default: true.
     */
    'control'           => ['label'     => __('Enable control', 'italystrap'), 'desc'      => __('Enable or disable arrow right and left. Accepted values: true, false. Default: true.', 'italystrap'), 'id'        => 'control', 'type'      => 'checkbox', 'default'   => 1, 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
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
     * The amount of time to delay between automatically cycling an item in milliseconds. Example 5000 = 5 seconds. Default 0, carousel will not automatically cycle.
     * @link http://www.smashingmagazine.com/2015/02/09/carousel-usage-exploration-on-mobile-e-commerce-websites/
     */
    'interval'          => ['label'     => __('Carousel interval', 'italystrap'), 'desc'      => __('The amount of time to delay between automatically cycling an item in milliseconds. Example 5000 = 5 seconds. Default 0, carousel will not automatically cycle.', 'italystrap'), 'id'        => 'interval', 'type'      => 'number', 'class'     => 'widefat', 'default'   => 0, 'validate'  => 'alpha_dash', 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
    /**
     * Pauses the cycling of the carousel on mouseenter and resumes the cycling of the carousel on mouseleave.
     * @type string Default hover.
     */
    'pause'             => ['label'     => __('Pause', 'italystrap'), 'desc'      => __('Pauses the cycling of the carousel on mouseenter and resumes the cycling of the carousel on mouseleave.', 'italystrap'), 'id'        => 'pause', 'type'      => 'select', 'class'     => 'widefat', 'default'   => 'hover', 'options'   => ['false'     => __('none', 'italystrap'), 'hover'     => __('hover (Default)', 'italystrap')], 'sanitize'  => 'sanitize_text_field', 'section'   => 'general'],
    /**
     * Show or hide image or post title. This is not the widget title. Set false to hide. Default: true.
     */
    'image_title'       => ['label'     => __('Show Title', 'italystrap'), 'desc'      => __('Show or hide image or post title. This is not the widget title. Set false to hide. Default: true.', 'italystrap'), 'id'        => 'image_title', 'type'      => 'checkbox', 'default'   => 1, 'sanitize'  => 'sanitize_text_field', 'section'   => 'content'],
    /**
     * Define HTML tag for image title. Default: h4.
     */
    'titletag'          => [
        'label'     => __('Carousel titletag', 'italystrap'),
        'desc'      => __('Define HTML tag for image title. Default: h4.', 'italystrap'),
        'id'        => 'titletag',
        'type'      => 'text',
        'class'     => 'widefat',
        'default'   => 'h4',
        // 'validate'   => 'esc_attr',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'content',
    ],
    /**
     * Show or hide image caption text (the excerpt of attachment) or the excerpt of the post. Set false to hide. Default: true.
     */
    'text'              => ['label'     => __('Text', 'italystrap'), 'desc'      => __('Show or hide image caption text (the excerpt of attachment) or the excerpt of the post. Set false to hide. Default: true.', 'italystrap'), 'id'        => 'text', 'type'      => 'checkbox', 'default'   => 1, 'sanitize'  => 'sanitize_text_field', 'section'   => 'content'],
    /**
     * Auto-format text. Changes double line-breaks in the text into HTML paragraphs <code>&lt;p&gt;...&lt;/p&gt;</code>. Default: true.
     */
    'wpautop'           => ['label'     => __('wpautop', 'italystrap'), 'desc'      => __('Auto-format text. Changes double line-breaks in the text into HTML paragraphs <code>&lt;p&gt;...&lt;/p&gt;</code>. Default: true.', 'italystrap'), 'id'        => 'wpautop', 'type'      => 'checkbox', 'default'   => 1, 'sanitize'  => 'sanitize_text_field', 'section'   => 'content'],
    /**
     * Allow shortcode for text. Default: false.
     */
    'do_shortcode'      => ['label'     => __('do_shortcode', 'italystrap'), 'desc'      => __('Allow shortcode for text. Default: false.', 'italystrap'), 'id'        => 'do_shortcode', 'type'      => 'checkbox', 'default'   => 0, 'sanitize'  => 'sanitize_text_field', 'section'   => 'content'],
    /**
     * Where your image titles will link to if "title" is set to true. Accepted values: file, none and empty. An empty value will link to your attachment’s page.
     */
    'link'              => ['label'     => __('Post or Image link', 'italystrap'), 'desc'      => __('Where your image titles will link to if "title" is set to true. Accepted values: file, none and empty. An empty value will link to your attachment’s page.', 'italystrap'), 'id'        => 'link', 'type'      => 'select', 'class'     => 'widefat', 'default'   => 'none', 'options'   => ['none'          => __('No link for the title (Default)', 'italystrap'), 'file'          => __('Link to the file media', 'italystrap'), 'parent'        => __('Link to the parent post', 'italystrap'), 'link'          => __('Link to the attachment', 'italystrap')], 'sanitize'  => 'sanitize_text_field', 'section'   => 'content'],
    /**
     * Show or hide a link to post, this works only for post_type and for parent of images, it works only with the selection of the link above. Default: false.
     *
     * Attualmente è già possibile aggiungere una CTA allo slider con i seguenti parametri nello shortcode [gallery]:
     *
     * link="parent" // Importante per linkare la pagina genitore associata
     * link_button="1"
     * link_button_text="Questa sarà la CTA"
     * link_button_css_class="btn btn-primary"
     *
     * Funziona solo per le immagini in evidenza poiché inserendo l'ID delle immagini verrà linkata la pagina genitore associata nella CTA.
     *
     * La CTA sarà uguale per tutte le slide aggiunte, al momento non è possibile personalizzarla per singola slide.
     */
    'link_button'               => ['label'     => __('Link button', 'italystrap'), 'desc'      => __('Show or hide a link to post, this works only for post_type and for parent of images, it works only with the selection of the link above. Default: false.', 'italystrap'), 'id'        => 'link_button', 'type'      => 'checkbox', 'default'   => 0, 'sanitize'  => 'sanitize_text_field', 'section'   => 'content'],
    /**
     * The text to display in the button link. Default: Read More.
     */
    'link_button_text'              => ['label'     => __('Link button text', 'italystrap'), 'desc'      => __('The text to display in the button link. Default: Read More.', 'italystrap'), 'id'        => 'link_button_text', 'class'     => 'widefat', 'type'      => 'text', 'default'   => __('Read more', 'italystrap'), 'sanitize'  => 'sanitize_text_field', 'section'   => 'content'],
    /**
     * You can add custom CSS class to the button. Default: btn btn-ptimary.
     */
    'link_button_css_class'             => ['label'     => __('Link button CSS class', 'italystrap'), 'desc'      => __('You can add custom CSS class to the button. Default: btn btn-ptimary.', 'italystrap'), 'id'        => 'link_button_css_class', 'class'     => 'widefat', 'type'      => 'text', 'default'   => 'btn btn-primary', 'sanitize'  => 'sanitize_text_field', 'section'   => 'class'],
    /**
     * Extra CSS class for carousel container.
     */
    'containerclass'    => [
        'label'     => __('Container Class', 'italystrap'),
        'desc'      => __('Extra CSS class for carousel container.', 'italystrap'),
        'id'        => 'containerclass',
        'type'      => 'text',
        'class'     => 'widefat',
        'default'   => '',
        // 'validate'   => 'alpha_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'class',
    ],
    /**
     * Extra CSS class for carousel item.
     */
    'itemclass'         => [
        'label'     => __('Item Class', 'italystrap'),
        'desc'      => __('Extra CSS class for carousel item.', 'italystrap'),
        'id'        => 'itemclass',
        'type'      => 'text',
        'class'     => 'widefat',
        'default'   => '',
        // 'validate'   => 'alpha_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'class',
    ],
    /**
     * Extra CSS class for carousel caption.
     */
    'captionclass'      => [
        'label'     => __('Caption Class', 'italystrap'),
        'desc'      => __('Extra CSS class for carousel caption.', 'italystrap'),
        'id'        => 'captionclass',
        'type'      => 'text',
        'class'     => 'widefat',
        'default'   => '',
        // 'validate'   => 'alpha_numeric',
        'sanitize'  => 'sanitize_text_field',
        'section'   => 'class',
    ],
    /**
     * Size for image attachment. Accepted values: thumbnail, medium, large, full or own custom name added in add_image_size function. Default: full. See <a href="http://codex.WordPress.org/Function_Reference/wp_get_attachment_image_src">wp_get_attachment_image_src()</a> for further reference.
     * Default: full.
     * @see wp_get_attachment_image_src() for further reference.
     */
    'size'              => ['label'     => __('Size for images', 'italystrap'), 'desc'      => __('Size for image attachment. Accepted values: thumbnail, medium, large, full or own custom name added in add_image_size function. Default: full. See <a href="http://codex.WordPress.org/Function_Reference/wp_get_attachment_image_src">wp_get_attachment_image_src()</a> for further reference.', 'italystrap'), 'id'        => 'size', 'type'      => 'select', 'class'     => 'widefat', 'default'   => 'full', 'options'   => $image_size_media_array ?? '', 'sanitize'  => 'sanitize_text_field', 'section'   => 'size'],
    /**
     * Activate responsive image. Accepted values: true, false. Default false. It works only if you add sizetablet and sizephone attribute. See below.
     */
    'responsive'        => ['label'     => __('Responsive image', 'italystrap'), 'desc'      => __('Activate responsive image. Default false. It works only if you add sizetablet and sizephone attribute. See below.', 'italystrap'), 'id'        => 'responsive', 'type'      => 'checkbox', 'default'   => 0, 'sanitize'  => 'sanitize_text_field', 'section'   => 'size'],
    /**
     * Size for image attachment for tablet device. Accepted values: thumbnail, medium, large, full or own custom name added in add_image_size function. Default: large.
     * @see wp_get_attachment_image_src() for further reference.
     */
    'sizetablet'        => ['label'     => __('Size for images', 'italystrap'), 'desc'      => __('Size for image attachment for tablet device. Accepted values: thumbnail, medium, large, full or own custom name added in add_image_size function. Default: large.', 'italystrap'), 'id'        => 'sizetablet', 'type'      => 'select', 'class'     => 'widefat', 'default'   => 'large', 'options'   => $image_size_media_array ?? '', 'sanitize'  => 'sanitize_text_field', 'section'   => 'size'],
    /**
     * Size for image attachment for phone device. Accepted values: thumbnail, medium, large, full or own custom name added in add_image_size function. Default: medium.
     * @see wp_get_attachment_image_src() for further reference.
     */
    'sizephone'         => ['label'     => __('Size for images', 'italystrap'), 'desc'      => __('Size for image attachment for phone device. Accepted values: thumbnail, medium, large, full or own custom name added in add_image_size function. Default: medium.', 'italystrap'), 'id'        => 'sizephone', 'type'      => 'select', 'class'     => 'widefat', 'default'   => 'medium', 'options'   => $image_size_media_array ?? '', 'sanitize'  => 'sanitize_text_field', 'section'   => 'size'],
];
