<?php

/**
 * Abstract Post Meta Class
 *
 * Handle the post meta functionality
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Asset;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

/**
 * The Post Meta Class
 */
abstract class Custom_Css_Base
{
    /**
     * CMB prefix
     *
     * @var string
     */
    protected $prefix;

    /**
     * CMB _prefix
     *
     * @var string
     */
    protected $_prefix;

    /**
     * The plugin options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Init the constructor
     *
     * @param array $options The plugin options
     */
    function __construct(array $options = [])
    {

        /**
         * Start with an underscore to hide fields from custom fields list
         *
         * @var string
         */
        $this->prefix = 'italystrap';

        $this->_prefix = '_' . $this->prefix;

        $this->options = $options;
    }

    /**
     * Riceve il valore della metabox top o middle
     * Se in pagina usa get_post_meta invece se in categoria usa get_term_meta
     *
     * @param  int     $post_id ID del post o della categoria.
     * @param  string  $key     ID della metabox.
     * @param  boolean $single  Se ritornare un array o un valore.
     * @param  boolean $is_cat  Boleano per detterminare se la funzione è richiamata in categoria o meno.
     * @return string      Contenuto della metabox
     */
    public function get_metabox($post_id, $key = '', $single = false, $is_cat = false)
    {

        $content = '';

        if (function_exists('get_term_meta') && $is_cat) {
            $content = get_term_meta($post_id, $key, $single);
        } else {
            $content = get_post_meta($post_id, $key, $single);
        }
        return $content;
    }

    /**
     * Uso questa funzione per fare l'escape,
     * stampare shortcode e inserire i paragrafi
     * per la funzione controzzi_get_the_metabox_page_content()
     *
     * @param  string $content Contenuto della meta.
     * @param  bool   $wpautop      Use wpautop.
     * @param  bool   $kses         Use wp_kses_post.
     * @param  bool   $do_shortcode Use do_shortcode.
     *
     * @return string          Ritorna il contenuto formattato e pulito.
     */
    public function escape_metabox($content = '', $wpautop = true, $kses = true, $do_shortcode = true)
    {

        if ($wpautop) {
            $content = wpautop($content);
        }

        if ($kses) {
            $content = wp_kses_post($content);
        }

        if ($do_shortcode) {
            $content = do_shortcode($content);
        }

        return $content;
    }
}
