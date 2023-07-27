<?php

/**
 * Class for new vCard widget
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\VCard\VCard as VCard_Base;

/**
 * Da leggere https://carlalexander.ca/polymorphism-wordpress-interfaces/
 */

/**
 * Link utili
 *
 * @link http://codex.wordpress.org/Function_Reference/the_widget
 * @link https://core.trac.wordpress.org/browser/tags/3.9.2/src/wp-includes/default-widgets.php#L0
 */

/**
 * Widget vCard Local Business
 *
 * @todo Controllare i vari link Schema.org, qualcuno potrebbe dare errore
 * @todo Mettere lista opzioni in ordine alfabetico con jquery
 * @todo Aggiornare la lista completa delle attività di local business
 * @todo Aggiungere la lista delle attività al file readme
 * @todo Ordinare la lista per dipendenze
 *       store
 *       store - musicstore
 * @todo creare array nome -> valore con lista di tutte le attività
 * @todo https://wordpress.org/plugins/mdp-local-business-seo/
 * @todo https://wordpress.org/plugins/local-business-microdata-widget/
 * @todo https://wordpress.org/plugins/local-search-seo-contact-page/
 * @todo search local seo
 *
 * Added upload media library for image
 * @link http://www.paulund.co.uk/add-upload-media-library-widgets
 */

/**
 * Class
 */
class VCard extends Widget
{
    /**
     * Init the constructor
     */
    function __construct()
    {

        $this->config = require(ITALYSTRAP_PLUGIN_PATH . 'config/vcard.php');

        /**
         * I don't like this and I have to find a better solution for loading script and style for widgets.
         */
        add_action('admin_enqueue_scripts', [$this, 'upload_scripts']);

        $fields = array_merge($this->title_field(), $this->config);

        /**
         * Configure widget array.
         *
         * @var array
         */
        $args = [
            // Widget Backend label.
            'label'             => __('ItalyStrap vCard Local Business', 'italystrap'),
            // Widget Backend Description.
            'description'       => __('Add a vCard Local Business with Schema.org markup to your theme widgetized area', 'italystrap'),
            'fields'            => $fields,
            'widget_options'    => ['customize_selective_refresh' => true],
            'control_options'   => ['width' => 340],
        ];

        /**
         * Create Widget
         */
        $this->create_widget($args);
    }

    /**
     * Dispay the widget content
     *
     * @param  array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param  array $instance The settings for the particular instance of the widget.
     */
    public function widget_render($args, $instance)
    {


        $vcard = new VCard_Base();

        $vcard->get_args('widget_vcard', $instance, $this->config);

        return $vcard->output();

        /**
         * var_dump( get_option( 'widget_italystrap-vcard-local-business' ) );
         */

        // ob_start();

        // require( \ItalyStrap\Core\get_template( 'templates/content-vcard.php' ) );

        // $out = ob_get_contents();
        // ob_end_clean();

        // return $out;
    }
} // Class.
