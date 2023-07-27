<?php

/**
 * Abstract class for custom query
 *
 * This is the abstract class for the custom query used in widget and shortcode to display post type
 *
 * @since 2.0.0
 *
 * @version 1.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Query;

use ItalyStrap\I18N\Translator;
use WP_Query;
use ItalyStrap\Excerpt\Excerpt;
use ItalyStrap\Config\Config;

/**
 * Query Class for widget and shortcode
 */
class Query implements Query_Interface
{
    /**
     * WordPress query object.
     *
     * @var WP_Query
     */
    protected $query;

    /**
     * WordPress query object.
     *
     * @var object
     */
    protected $args;

    /**
     * WordPress global $post
     *
     * @var object
     */
    protected $post;

    /**
     * Set an array with post to be escluded from loop
     *
     * @var array
     */
    protected $posts_to_exclude = [];

    /**
     * Declare this variable static and call it only one time
     *
     * @var array
     */
    protected static $sticky_posts;

    /**
     * The context of this instance works
     *
     * @var string
     */
    protected $context;

    protected $excerpt;
    protected $config;

    /**
     * Constructor.
     *
     * @param WP_Query $query The standard query of WordPress.
     */
    function __construct(WP_Query $query, Excerpt $excerpt, $context = 'posts')
    {

        $this->excerpt = $excerpt;
        $this->query = $query;

        global $post;
        $this->post = $post;

        if (! isset(self::$sticky_posts)) {
            self::$sticky_posts = get_option('sticky_posts');
        }

        if (! isset($context)) {
            $context = 'main';
        }

        if (! is_string($context)) {
            throw new \InvalidArgumentException(__('Context name must be a string', 'italystrap'));
        }

        $this->context = $context;
    }

    /**
     * Initialize the repository.
     *
     * @uses PHP 5.3
     *
     * @return self
     */
    public static function init($context = 'posts')
    {
        return new self(new WP_Query(), new Excerpt(new Config(), new Translator('ItalyStrap')), $context);
    }

    /**
     * Get the global post
     *
     * @return object Return the global objesct post
     */
    public function get_global_post()
    {
        return $this->post;
    }

    /**
     * Get arguments from widget attributes
     *
     * @param  array $args Arguments from widget.
     */
    public function get_widget_args($args)
    {

        $this->config = $this->get_attributes($args);
    }

    /**
     * Get arguments from shortcode attributes
     *
     * @param  array $args Arguments from shortcode.
     */
    public function get_shortcode_args($args)
    {

        $this->config = $this->get_attributes($args);
    }

    /**
     * Get custom template
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_custom_template_path()
    {
//      d($this->config['template_custom']);
//      return ITALYSTRAP_PLUGIN_PATH . DS . $templates['standard'];
    }

    /**
     * Get the correct path for template parts
     *
     * @return string Return the path
     */
    public function get_template_part($path = '')
    {

        // d( $this->config['template'] );
        // d( $this->config['template_custom'] );

        $template_dir_name = apply_filters('italystrap_template_dir_name', 'templates');

        /**
         * Standard
         * Legacy
         * Card
         * Masonry
         *
         * Da gestire
         * Template standard per posts + eventuali altri template preimpostati
         * Possibilità di poter aggiungere template anche da plugin
         *
         * Possibilità
         *
         * Possibile percorso
         *
         * File inserito nel plugin
         * Possibilità di sovrascriverlo dal tema chile e dal tema parent
         * quindi devo avere il path relativo per fare il check con locate template
         *
         * Se io registro dei template da un plugin devo fornire il path completo del plugin
         * e deve fare il check con locate_template denteo il plugin stesso
         */

        // d( $this->config['template'] );
        // 'thematic-areas-home'

        $templates = [
            // 'name'   => 'fullpath/of/the/template.php'
            'default'   => $template_dir_name . DS . 'posts/default.php',
            'legacy'    => $template_dir_name . DS . 'posts/legacy.php',
            'custom'    => null,
        ];
            // 'default'    => $template_dir_name . DS . 'posts/test.php',

        $context = 'posts';

        // $templates = apply_filters( "italystrap_{$context}_templates_name_registered", $templates );
        $templates = apply_filters('italystrap_templates_posts_name_registered', $templates);

        $templates = array_merge($templates, ['loop'  => $template_dir_name . DS . 'posts/loop.php']);

        // d( locate_template( $templates['default'] ) );
        // d( $templates['loop'] );
        // d( locate_template( $templates['legacy'] ) );
        // d( locate_template( $templates[ $this->config['template'] ] ) );

        // $this->config['template'] = 'thematic-areas-home';

        /**
         * For back compat.
         */
        if ('standard' === $this->config['template']) {
            $this->config['template'] = 'default';
        }

        $locate_template = [$templates[ $this->config['template'] ]];

        // d( file_exists( 'E:\xampp\htdocs\helpcode/wp-content/themes/italystrap/templates\posts/default.php' ) );

        // $locate_template = 'E:\xampp\htdocs\helpcode/wp-content/themes/italystrap/templates\posts/default.php';
        // d( 'Cerco nel tema', $templates[ $this->config['template'] ] );
        /**
         * Cerca se è presente il file nel tema figlio e poi nel tema genitore
         * Se lo trova restituisce il full path del file da caricare.
         *
         * @var string
         */
        if ($template_file_full_path = locate_template($locate_template)) {
            return $template_file_full_path;
        }
        // d( 'Nel tema not found, continuo a cercare', $templates[ $this->config['template'] ] );
        /**
         * Posso registrare un file template anche da plugin e
         * verifico qui dopo la verifica del tema
         * Il file deve essere registrato con il full path
         * Però non potrà essere sovrascritto dal tema
         */
        if (file_exists($templates[ $this->config['template'] ])) {
            return $templates[ $this->config['template'] ];
        }

        /**
         * Se non c'è nessun file in temi o plugin
         * ritorno il file selezionato dal widget e presente
         * nel plugin e ritorno il full path
         */
        if (file_exists(ITALYSTRAP_PLUGIN_PATH . $templates[ $this->config['template'] ])) {
            return ITALYSTRAP_PLUGIN_PATH . $templates[ $this->config['template'] ];
        }

        /**
         * Se nessuno dei controlli precedenti ha funzionato
         * ritorno il valore di default
         */
        return ITALYSTRAP_PLUGIN_PATH . DS . $templates['default'];

        // d( $this->config['template'] );

        // $template_path = ITALYSTRAP_PLUGIN_PATH . '/templates/legacy.php';

        // if ( 'custom' === $this->config['template'] ) {

        //  $custom_template_path = '/templates/' . $this->config['template_custom'] . '.php';

        //  if ( locate_template( $custom_template_path ) ) {

        //      $template_path = STYLESHEETPATH . $custom_template_path;

        //  } else {

        //      $template_path = ITALYSTRAP_PLUGIN_PATH . '/templates/default.php';

        //  }
        // } elseif ( 'default' === $this->config['template'] ) {

        //  $template_path = ITALYSTRAP_PLUGIN_PATH . '/templates/default.php';

        // } else {

        //  $template_path = ITALYSTRAP_PLUGIN_PATH . '/templates/legacy.php';

        // }

        // return apply_filters( "italystrap_{$this->context}_template_path", $template_path, $this->config );
        // include \ItalyStrap\Core\get_template( '/templates/content-post.php' );
        // include \ItalyStrap\Core\get_template( '/templates/posts/loop.php' );
        return \ItalyStrap\Core\get_template('/templates/content-post.php');
    }

    /**
     * Output the query result
     *
     * @return string The HTML result
     */
    public function output()
    {
    }

    /**
     * Render the query result
     *
     * @return string The HTML result
     */
    public function render()
    {
    }

    /**
     * Get posts id by views.
     * This function is forked from Jetpack.
     * It functions only if jetpack is actived.
     *
     * @param  array      $args   The widget arguments.
     *
     * @return array/null         Return an array of posts id
     *                            or null if none are found.
     */
    protected function get_posts_ids_by_views($args)
    {

        if (! function_exists('stats_get_csv')) {
            return null;
        }

        /**
         * Filter the number of days used to calculate Top Posts for the Top Posts widget.
         * We do not recommend accessing more than 10 days of results at one.
         * When more than 10 days of results are accessed at once, results should be cached via the WordPress transients API.
         * Querying for -1 days will give results for an infinite number of days.
         *
         * @module widgets
         *
         * @since 3.9.3
         *
         * @param int 2 Number of days. Default is 2.
         * @param array $args The widget arguments.
         */
        $days = (int) apply_filters('italystrap_top_posts_days', 2, $args);

        /** Handling situations where the number of days makes no sense - allows for unlimited days where $days = -1 */
        if (0 === $days || false === $days) {
            $days = 2;
        }

        $post_view_posts = stats_get_csv('postviews', ['days' => absint($days), 'limit' => 11]);

        if (! $post_view_posts) {
            return [];
        }

        $post_view_ids = array_filter(wp_list_pluck($post_view_posts, 'post_id'));

        if (! $post_view_ids) {
            return null;
        }

        return (array) array_map('absint', $post_view_ids);
    }
}
