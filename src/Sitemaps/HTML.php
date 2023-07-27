<?php

/**
 * ItalyStrap HTML Sitemaps
 *
 * Display an HTML Sitemaps with shortcode
 * Under develpment
 *
 * @package ItalyStrap\Core
 * @version 1.0
 * @since   2.0
 */

namespace ItalyStrap\Sitemaps;

/**
 * This class creates an HTML sitemaps
 *
 * @todo Verificare cosa stampare e cosa no
 *       Eventualmente mettere sotto opzione
 *       Per esempio, le pagine che hanno un redirect con Yoast ma sono pubbliche?
 *       Oppure quelle che hanno un noindex follow?
 *       Insomma, fare dei test
 * @todo Anche le varie query fanno schifo, usare WP_Query invece di query_posts
 *       E valutare anche l'utilizzo della cache per le query
 *       Cosa succede anche in blogo con migliaia di articoli?
 *       Limitare il numero di articoli o al limite valutare
 *       di inserire solo le categorie.
 */
class HTML
{
    /**
     * Arguments for class.
     */
    private array $args = [];

    /**
     * The post HTML output
     */
    private string $posts_output = '';

    /**
     * The constructor
     *
     * @param array $args The array of arguments.
     */
    function __construct($args = [])
    {

        $this->args = $args;

        if (! $this->args['print']) {
            $this->the_html_sitemaps($this->args);
        }
    }

    /**
     * Get the author list
     *
     * @see https://codex.wordpress.org/Function_Reference/wp_list_authors
     * @param  array  $author_args Arguments for wp_list_author().
     * @return string              Return list of author
     */
    private function get_wp_list_author($author_args = [])
    {

        $author_args['echo'] = false;

        return wp_list_authors($author_args);
    }

    /**
     * Get the pages list
     *
     * @see https://codex.wordpress.org/Function_Reference/wp_list_pages
     * @param  array  $pages_args Argument for wp_list_pages()
     * @return string             Return list of pages
     */
    private function get_wp_list_pages($pages_args = [])
    {

        $pages_args['title_li'] = '';
        $pages_args['echo'] = false;

        return wp_list_pages($pages_args);
    }

    /**
     * Print the HTML sitemaps
     *
     * @param  array $args The arguments for class.
     */
    public function the_html_sitemaps($args = [])
    {

        echo $this->get_the_html_sitemaps($args); // XSS ok.
    }

    /**
     * Get the HTML sitemaps outputs
     *
     * @param  array  $args Argument for HTML sitemaps.
     * @return string       Return the HTML sitemaps
     */
    public function get_the_html_sitemaps($args = [])
    {

            /**
             * The post HTML output
             *
             * @var string
             */
            $posts_output = '';



        $posts_output .= '<div itemscope itemtype="http://schema.org/ItemList">';


            //http://yoast.com/html-sitemap-wordpress/
            //
            // var_dump(get_pages());
            // var_dump(get_posts());

            /**
             * Args for wp_list_authors function
             *
             * @var array
             */
            $author_args = ['exclude_admin' => false];

            $posts_output .= '<h2 itemprop="name">' . __('Authors:', 'italystrap') . '</h2><meta itemprop="itemListOrder" content="Ascending" /><ul>' . $this->get_wp_list_author($author_args) . '</ul>';

            /**
             * A list of post names registered
             *
             * @var array
             */
            $post_types = get_post_types(['public' => true]);

        foreach ($post_types as $post_type) {
            // if ( in_array( $post_type, array('post','page','attachment') ) )
            //  continue;

            $pt = get_post_type_object($post_type);

            if ('post' === $post_type) {
                $posts_output .= '<h2 itemprop="name">' . $pt->labels->name . '</h2><meta itemprop="itemListOrder" content="Descending" /><ul>';

                /**
                 * Returns an array of category objects matching the query parameters.
                 *
                 * @link https://codex.wordpress.org/Function_Reference/get_categories
                 * @var array
                 */
                $cats = get_categories(['hide_empty' => 1]);

                foreach ($cats as $cat) {
                    $posts_output .= '<li><h3 itemprop="name">' . $cat->cat_name . '</h3><ul>';

                    query_posts('posts_per_page=-1&cat=' . $cat->cat_ID);
                    while (have_posts()) {
                        the_post();
                        $category = get_the_category();

                        // Only display a post link once, even if it's in multiple categories.
                        if ($category[0]->cat_ID == $cat->cat_ID) {
                            $posts_output .= '<li itemprop="itemListElement"><a href="' . get_permalink() . '" itemprop="url">' . get_the_title() . '</a></li>';
                        }
                    }
                    $posts_output .= '</ul></li>';
                }

                    $posts_output .= '</ul>';
            } elseif ('page' === $post_type) {
                $posts_output .= '<h2 itemprop="name">' . $pt->labels->name . '</h2><meta itemprop="itemListOrder" content="Descending" /><ul>' . $this->get_wp_list_pages() . '</ul>';
            } elseif ('attachment' === $post_type) {
                continue;
            } else {
                /**
                 * Get all custopm post type
                 */

                $posts_output .= '<h2 itemprop="name">' . $pt->labels->name . '</h2><meta itemprop="itemListOrder" content="Descending" /><ul>';

                query_posts('post_type=' . $post_type . '&posts_per_page=-1');

                while (have_posts()) {
                    the_post();
                    $posts_output .= '<li itemprop="itemListElement"><a href="' . get_permalink() . '" itemprop="url">' . get_the_title() . '</a></li>';
                }

                $posts_output .= '</ul>';
            }
        }

            $posts_output .= '</div>';

            return apply_filters('italystrap_html_sitemaps', $posts_output);
    }
}
