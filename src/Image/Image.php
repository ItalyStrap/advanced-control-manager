<?php

/**
 * Class API for Image
 *
 * @since 2.0.0
 *
 * @version 1.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Image;

/**
 * The vCard Class
 */
class Image
{
    /**
     * [$var description]
     *
     * @var null
     */
    private $args = null;

    /**
     * Get arguments
     *
     * @param  string $context The context.
     * @param  array  $args    The arguments for this class.
     * @param  array  $default The default arguments.
     */
    public function get_args($context, array $args, array $default)
    {

        /**
         * Define data by given attributes.
         */
        $args = \ItalyStrap\Core\shortcode_atts_multidimensional_array($default, $args, $context);

        $args = apply_filters('italystrap_{$context}_args', $args);

        $this->args = $args;
    }

    /**
     * Output the query result
     *
     * @return string The HTML result
     */
    public function output($output = '')
    {

        ob_start();

        require(\ItalyStrap\Core\get_template('templates/content-image.php'));

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Get the icon
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_the_icon()
    {

        $output = '';

        /**
         * Image alignement
         *
         * @var string
         */
        $align = esc_attr($this->args['alignment']);

        $icon = esc_attr($this->args['icon']);

        $attr = ['class'     => $icon . ' ' . $align];

        $output .= sprintf(
            '<span %s></span>',
            \ItalyStrap\Core\get_attr('widget_image_icon', $attr)
        );

        return $output;
    }

    /**
     * Get attachment image
     *
     * @return string Return the img html tag
     */
    public function get_attachment_image()
    {

        $output = '';

        /**
         * The size of the image
         *
         * @var string
         */
        $size = esc_attr($this->args['size']);

        /**
         * Image alignement
         *
         * @var string
         */
        $align = esc_attr($this->args['alignment']);

        /**
         * CSS class attrinute fro the image
         *
         * @var string
         */
        $image_css_class = esc_attr($this->args['image_css_class']);

        $attr = ['class'     => "attachment-$size size-$size $align $image_css_class", 'itemprop'  => 'image'];

        if (! empty($this->args['image_title'])) {
            $attr['title'] = esc_attr($this->args['image_title']);
        }

        $output .= wp_get_attachment_image($this->args['id'], $size, false, $attr);

        return $output;
    }

    /**
     * Get the title
     *
     * @return string Return the title.
     */
    public function get_the_title()
    {

        if (empty($this->args['image_title'])) {
            return;
        }

        return sprintf(
            '<%1$s class="%3$s">%2$s</%1$s>',
            $this->args['image_title_tag'],
            esc_attr($this->args['image_title']),
            esc_attr($this->args['image_title_class'])
        );
    }

    /**
     * Get the description
     *
     * @return string Return the description.
     */
    public function get_the_description()
    {

        if (empty($this->args['description'])) {
            return;
        }

        $output = $this->args['description'];

        if (! empty($this->args['wpautop'])) {
            $output = wpautop($output);
        }

        if (! empty($this->args['do_shortcode'])) {
            $output = do_shortcode($output);
        }

        return sprintf(
            '<div>%s</div>',
            $output
        );
    }

    /**
     * Get the caption
     *
     * @return string Return the caption.
     */
    public function get_the_caption()
    {

        if (empty($this->args['caption'])) {
            return;
        }

        return sprintf(
            '<figcaption %s>%s</figcaption>',
            \ItalyStrap\Core\get_attr('widget_image_caption', ['class' => 'fig-null']),
            esc_attr($this->args['caption'])
        );
    }
}
