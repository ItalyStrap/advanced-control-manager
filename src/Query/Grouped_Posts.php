<?php

namespace ItalyStrap\Query;

use WP_Query;

/**
 * Classe per la documentazione
 */
class Grouped_Posts
{
    /**
     * Pluin options
     */
    private array $options = [];

    protected $term = null;

    /**
     * Inizializzo il costruttore
     */
    function __construct(array $options = [], Posts $query = null)
    {

        $this->options = $options;

        $this->query = $query;
    }

    /**
     * Get shortcode attributes.
     *
     * @param  array $args The carousel attribute.
     * @return array Mixed array of shortcode attributes.
     */
    public function get_attributes(array $instance)
    {

        /**
         * Define data by given attributes.
         */
        $instance = \ItalyStrap\Core\shortcode_atts_multidimensional_array(require(ITALYSTRAP_PLUGIN_PATH . 'config/grouped-posts.php'), $instance, 'query_posts');

        $instance = apply_filters('italystrap_query_posts_args', $instance);

        $this->args = $instance;

        return $instance;
    }

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_posts($category_term_id)
    {

        // $query_posts = Posts::init();

        // $query_posts->get_widget_args( $instance );

        // return $query_posts->output();
        // echo "<pre>";
        // print_r($this->query->query);
        // print_r($query_posts);
        // echo "</pre>";
        //

        $output = '';

        $args = [
            'no_found_rows'     => true,
            'posts_per_page'    => 5,
            // 'cat' => $category->term_id,
            'category__in'      => $category_term_id,
        ];

        $query = new WP_Query($args);
        // $query = $this->query->get_attributes( $args );

        if ($query->have_posts()) :
            $output .= '<ul class="list-unstyled">';

            while ($query->have_posts()) :
                $query->the_post();

                $output .= '<li><i class="fa fa-file-text-o"></i> ';
                // $output .= get_the_post_thumbnail( $query->post->ID );
                $output .= get_the_post_thumbnail($query->post->ID, 'thumbnail');

                $output .= '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';

                $output .= '</li>';
            endwhile;

            $output .= '</ul>';

            // $output .= '<i class="fa fa-arrow-circle-o-right"></i> <a class="hkb-category__view-all" href="' . $term_link . '">View all</a>';
        endif;

        wp_reset_postdata();

        return $output;
    }

    /**
     * Get tax query args
     *
     * @see https://developer.wordpress.org/reference/functions/get_terms/
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_tax_query_args(array $tax_query_args = [], $tax = 'category')
    {

        $defaults = [
            'taxonomy'      => $tax,
            'orderby'       => 'name',
            'order'         => 'ASC',
            'hide_empty'    => 0,
            // 'include'        => 0, // (array|string) Array or comma/space-separated string of term ids to include.
            // 'exclude'        => 0, // (array|string) Array or comma/space-separated string of term ids to exclude. If $include is non-empty, $exclude is ignored.
            // 'exclude_tree'   => 0, // (array|string) Array or comma/space-separated string of term ids to exclude along with all of their descendant terms. If $include is non-empty, $exclude_tree is ignored.
            // 'number'         => 0, // (int|string) Maximum number of terms to return. Accepts ''|0 (all) or any positive number. Default ''|0 (all).
            // 'offset'         => 0, // (int) The number by which to offset the terms query.
            // 'fields'         => 0, // (string) Term fields to query for. Accepts 'all' (returns an array of complete term objects), 'ids' (returns an array of ids), 'id=>parent' (returns an associative array with ids as keys, parent term IDs as values), 'names' (returns an array of term names), 'count' (returns the number of matching terms), 'id=>name' (returns an associative array with ids as keys, term names as values), or 'id=>slug' (returns an associative array with ids as keys, term slugs as values). Default 'all'.
            // 'name'           => 0, // (string|array) Optional. Name or array of names to return term(s) for.
            // 'slug'           => 0, // (string|array) Optional. Slug or array of slugs to return term(s) for.
            // 'hierarchical'   => 0, // (bool) Whether to include terms that have non-empty descendants (even if $hide_empty is set to true). Default true.
            // 'search' => 0, // (string) Search criteria to match terms. Will be SQL-formatted with wildcards before and after.
            // 'name__like' => 0, // (string) Retrieve terms with criteria by which a term is LIKE $name__like.
            // 'description__like'  => 0, // (string) Retrieve terms where the description is LIKE $description__like.
            // 'pad_counts' => 0, // (bool) Whether to pad the quantity of a term's children in the quantity of each term's "count" object variable. Default false.
            // 'get'    => 0, // (string) Whether to return terms regardless of ancestry or whether the terms are empty. Accepts 'all' or empty (disabled).
            // 'child_of'   => 0, // (int) Term ID to retrieve child terms of. If multiple taxonomies are passed, $child_of is ignored. Default 0.
            'parent'        => 0,
        ];

        // if ( ! empty( $this->args['include'] ) ) {
        //  $defaults['include'] = $this->args['include'];
        // }

        // if ( ! empty( $this->args['exclude'] ) ) {
        //  $defaults['exclude'] = $this->args['exclude'];
        // }
        $this->set_tax_query_args($defaults);

        return wp_parse_args($tax_query_args, $defaults);
    }

    /**
     * Continue after first call
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function set_tax_query_args(&$defaults)
    {

        static $count = 0;

        if (1 <= $count) {
            return;
        }

        if (! empty($this->args['include'])) {
            $defaults['include'] = $this->args['include'];
        }

        if (! empty($this->args['exclude'])) {
            $defaults['exclude'] = $this->args['exclude'];
        }

        $count++;
    }

    /**
     * Retrieve top 10 most-commented posts and cache the results.
     *
     * @param bool $force_refresh Optional. Whether to force the cache to be refreshed.
     *                            Default false.
     * @return array|WP_Error Array of WP_Post objects with the highest comment counts,
     *                        WP_Error object otherwise.
     */
    function get_category_array($force_refresh = false, $tax = 'category', array $args = [], $id = null)
    {

        if (! isset($id)) {
            $id = $tax;
        }

        // Check for the category_array key in the 'category_posts' group.
        $category_array = wp_cache_get("italystrap_{$id}_array", 'category_posts');

        // If nothing is found, build the object.
        if (true === $force_refresh || false === $category_array) {
            // Grab the top 10 most commented posts.
            $category_array = get_categories($this->get_tax_query_args($args, $tax));

            if (! is_wp_error($category_array)) {
                // In this case we don't need a timed cache expiration.
                wp_cache_set("italystrap_{$id}_array", $category_array, 'category_posts');
            }
        }
        // echo "<pre>";
        // print_r($category_array);
        // echo "</pre>";
        return $category_array;
    }

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_categories($parent_id)
    {

        $args = ['parent'        => $parent_id];

        $categories = $this->get_category_array(false, 'category', $args, $parent_id);

        if (empty($categories)) {
            return;
        }

        $output = '';

        $output .= '<ul class="list-unstyled">';

        foreach ((array) $categories as $category) {
            if (0 === $category->count) {
                continue;
            }
            $output .= sprintf(
                '<li><i class="fa fa-folder"></i> <strong><a href="%s">%s</a></strong> <small>(%s)</small></li>',
                esc_url(get_term_link($category)),
                $category->name,
                sprintf(
                    _n('%1$s Article', '%1$s Articles', $category->count, 'italystrap'),
                    number_format_i18n($category->count)
                )
            );
        }

        $output .= '</ul>';

        return $output;
    }

    /**
     * Gets all of the posts grouped by terms for the specified
     * post type and taxonomy.
     *
     * Results are grouped by terms and ordered by the term and post IDs.
     * https://github.com/hellofromtonya/CollapsibleContent/blob/master/src/faq/template/helpers.php
     * @since 1.0.0
     *
     * @param string $post_type_name Post type to limit query to
     * @param string $taxonomy_name Taxonomy to limit query to
     *
     * @return array|false
     */
    protected function get_posts_grouped_by_term_from_db($post_type_name, $taxonomy_name)
    {
        global $wpdb;
        $sql_query =
        "SELECT t.term_id, t.name AS term_name, t.slug AS term_slug, tt.description AS term_description, tt.parent AS term_parent, p.ID AS post_id, p.post_title, p.post_content, p.post_parent, p.menu_order, p.guid, p.post_name, p.post_date
	FROM {$wpdb->term_taxonomy} AS tt
	INNER JOIN {$wpdb->terms} AS t ON (tt.term_id = t.term_id)
	INNER JOIN {$wpdb->term_relationships} AS tr ON (tt.term_taxonomy_id = tr.term_taxonomy_id)
	INNER JOIN {$wpdb->posts} AS p ON (tr.object_id = p.ID)
	WHERE p.post_status = 'publish' AND p.post_type = %s AND tt.taxonomy = %s
	GROUP BY t.term_id, p.ID
	ORDER BY t.term_id, p.menu_order ASC;";

        $sql_query = $wpdb->prepare($sql_query, $post_type_name, $taxonomy_name);
        $results = $wpdb->get_results($sql_query);

        if (! $results || ! is_array($results)) {
            return false;
        }

        return $results;
    }

    /**
     * Gets all of the posts grouped by terms for the specified
     * post type and taxonomy.
     *
     * Results are grouped by terms and ordered by the term and post IDs.
     * https://github.com/hellofromtonya/CollapsibleContent/blob/master/src/faq/template/helpers.php
     * @since 1.0.0
     *
     * @param string $post_type_name Post type to limit query to
     * @param string $taxonomy_name Taxonomy to limit query to
     *
     * @return array|false
     */
    public function get_posts_grouped_by_term($post_type_name, $taxonomy_name)
    {
        $records = $this->get_posts_grouped_by_term_from_db($post_type_name, $taxonomy_name);
        // ddd( $records );
        $groupings = [];

        foreach ($records as $record) {
            $term_id = (int) $record->term_id;
            $post_id = (int) $record->post_id;
            $term_parent = (int) $record->term_parent;
            if (! array_key_exists($term_id, $groupings)) {
                $groupings[ $term_id ] = ['term_id'           => $term_id, 'term_name'         => $record->term_name, 'term_slug'         => $record->term_slug, 'term_description'  => $record->term_description, 'term_parent'       => $term_parent, 'posts'             => []];
            }
            $groupings[ $term_id ]['posts'][ $post_id ] = ['post_id'       => $post_id, 'post_title'    => $record->post_title, 'post_content'  => $record->post_content, 'post_parent'   => $record->post_parent, 'post_name'     => $record->post_name, 'post_date'     => $record->post_date, 'menu_order'    => $record->menu_order, 'guid'          => $record->guid, 'term_id'       => $term_id];
        }

        // d( $groupings );

        return $groupings;
    }

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function output($atts = null)
    {
        $output = '';
        $this->categories = $this->get_posts_grouped_by_term('post', 'category');

        $term = (object) [];
        foreach ((array) $this->categories as $category) {
            $output .= $this->build_output($category, $term);
        }

        return $output;
    }

    /**
     * Build the output
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function build_output($category, $term)
    {
        $output = '';

        $count = is_countable($category['posts']) ? count($category['posts']) : 0;

        $output .= sprintf(
            '<div class="%s"><header><h2 class="entry-title-category"><i class="fa fa-folder-o"></i> <a href="%s">%s</a> <small>(%s)</small></h2><p>%s</p></header>%s%s</div>',
            ! empty($this->args['tax_class']) ? esc_attr($this->args['tax_class']) : '',
            // home_url( '/' ) . $category['term_slug'], // esc_url( get_term_link( $category['term_id'] ) ),
            // esc_url( \get_permalink_by_slug( $category['term_slug'] ) ),
            esc_url($this->get_term_link($category['term_id']), 'category'),
            // esc_url( get_term_link( $category['term_id'] ) ),
            // esc_url( get_term_link( $term ) ),
            esc_html($category['term_name']),
            sprintf(
                _n('%1$s Article', '%1$s Articles', $count, 'italystrap'),
                number_format_i18n($count)
            ),
            $category['term_description'],
            $this->get_categories($category['term_id']),
            // '',
            // $this->get_posts( $category['term_id'] ),
            $this->get_the_grouped_posts($category['posts']),
            '' //$query_posts->output( $query_args )
        );

        return $output;
    }

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_the_grouped_posts(array $posts = [], $limit = 5)
    {
        $output = '';
        $i = 0;
// d( $posts );
        $output .= '<ul class="list-unstyled">';

        foreach ($posts as $post_key => $post) {
            if ($i >= $limit) {
                continue;
            }

            $output .= '<li>';

            $output .= '<i class="fa fa-file-text-o"></i> ';

            // $output .= get_the_post_thumbnail( $post['post_id'], 'thumbnail' );

            $output .= '<a href="' . esc_url($this->get_permalink($post)) . '">' . esc_html($post['post_title']) . '</a>';

            $output .= '</li>';

            $i++;
        }

        $output .= '</ul>';

        return $output;
    }

    /**
     * Generate a permalink for a taxonomy term archive.
     *
     * @since 2.5.0
     *
     * @global WP_Rewrite $wp_rewrite
     *
     * @param object|int|string $term     The term object, ID, or slug whose link will be retrieved.
     * @param string            $taxonomy Optional. Taxonomy. Default empty.
     * @return string|WP_Error HTML link to taxonomy term archive on success, WP_Error if term does not exist.
     */
    public function get_term_link($term, $taxonomy = '')
    {
        global $wp_rewrite;

        if (!is_object($term)) {
            if (is_int($term)) {
                $term = get_term($term, $taxonomy);
            } else {
                $term = get_term_by('slug', $term, $taxonomy);
            }
        }

        if (!is_object($term)) {
            $term = new WP_Error('invalid_term', __('Empty Term'));
        }

        if (is_wp_error($term)) {
            return $term;
        }

        $taxonomy = $term->taxonomy;

        $termlink = $wp_rewrite->get_extra_permastruct($taxonomy);

        $slug = $term->slug;
        $t = get_taxonomy($taxonomy);

        if (empty($termlink)) {
            if ('category' == $taxonomy) {
                $termlink = '?cat=' . $term->term_id;
            } elseif ($t->query_var) {
                $termlink = "?$t->query_var=$slug";
            } else {
                $termlink = "?taxonomy=$taxonomy&term=$slug";
            }
            $termlink = home_url($termlink);
        } else {
            if ($t->rewrite['hierarchical']) {
                $hierarchical_slugs = [];
                $ancestors = get_ancestors($term->term_id, $taxonomy, 'taxonomy');
                foreach ((array)$ancestors as $ancestor) {
                    $ancestor_term = get_term($ancestor, $taxonomy);
                    $hierarchical_slugs[] = $ancestor_term->slug;
                }
                $hierarchical_slugs = array_reverse($hierarchical_slugs);
                $hierarchical_slugs[] = $slug;
                $termlink = str_replace("%$taxonomy%", implode('/', $hierarchical_slugs), $termlink);
            } else {
                $termlink = str_replace("%$taxonomy%", $slug, $termlink);
            }
            $termlink = home_url(user_trailingslashit($termlink, 'category'));
        }

        /**
         * Filters the term link.
         *
         * @since 2.5.0
         *
         * @param string $termlink Term link URL.
         * @param object $term     Term object.
         * @param string $taxonomy Taxonomy slug.
         */
        return apply_filters('term_link', $termlink, $term, $taxonomy);
    }

    /**
     * Get the post permalink
     * This is a modified get_permalink from core
     * It does not use of the WP_Term
     *
     * @see get_permalink() wp-includes/link-template.php#L118
     *
     * @param  array  $post The post array.
     *
     * @return string      Return the permalink of the post.
     */
    public function get_permalink(array $post, $leavename = false)
    {

        static $permalink_structure = null;

        if (! isset($permalink_structure)) {
            $permalink_structure = get_option('permalink_structure');
        }

        $link = '';

        // if they're not using the fancy permalink option
        if ('' === $permalink_structure) {
            return home_url('?p=' . $post['post_id']);
        }

        $category = '';
        if (strpos($permalink_structure, '%category%') !== false) {
            $cats = $this->get_the_category($post['term_id']);

            if ($cats) {
                // $cats = wp_list_sort( $cats, array(
                //  'term_id' => 'ASC',
                // ) );

                /**
                 * Filters the category that gets used in the %category% permalink token.
                 *
                 * @since 3.5.0
                 *
                 * @param WP_Term  $cat  The category to use in the permalink.
                 * @param array    $cats Array of all categories (WP_Term objects) associated with the post.
                 * @param WP_Post  $post The post in question.
                 */
                // $category_object = apply_filters( 'post_link_category', $cats[0], $cats, $post );

                // $category_object = get_term( $category_object, 'category' );
                // $category = $category_object->slug;
                $category = $cats['term_slug'];
                if ($parent = $cats['term_parent']) {
                    $category = $this->get_category_parents($parent) . '/' . $category;
                }
            }
            // show default category in permalinks, without
            // having to assign it explicitly
            if (empty($category)) {
                $default_category = get_term(get_option('default_category'), 'category');
                if ($default_category && ! is_wp_error($default_category)) {
                    $category = $default_category->slug;
                }
            }
        }

        $author = '';
        // if ( strpos( $permalink_structure, '%author%' ) !== false ) {
        //  $authordata = get_userdata( $post->post_author );
        //  $author = $authordata->user_nicename;
        // }

        $unixtime = strtotime($post['post_date']);

        $date = explode(" ", date('Y m d H i s', $unixtime));

        $rewritecode = ['%year%', '%monthnum%', '%day%', '%hour%', '%minute%', '%second%', $leavename ? '' : '%postname%', '%post_id%', '%category%', '%author%', $leavename ? '' : '%pagename%'];

        $rewritereplace =
        [$date[0], $date[1], $date[2], $date[3], $date[4], $date[5], $post['post_name'], $post['post_id'], $category, $author, $post['post_name']];
        $permalink = '';
        $permalink = home_url(str_replace($rewritecode, $rewritereplace, $permalink_structure));
        $permalink = user_trailingslashit($permalink, 'single');

        return $permalink;
    }

    /**
     * Get hte category
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_the_category($id)
    {
        return $this->categories[ $id ];
    }

    /**
     * Get the parent category
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_category_parents($id)
    {

        if (! $this->categories[ $id ]['term_parent']) {
            return '';
        }

        return $this->categories[ $this->categories[ $id ]['term_parent'] ]['term_slug'];
    }
}
