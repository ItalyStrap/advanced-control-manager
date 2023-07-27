<?php

/**
 * This class is for elaborate te arguments from widget or shortcode and then return an HTML for display the posts/page result.
 *
 * @package Query_Posts
 * @version 1.0
 * @since   2.0
 */

namespace ItalyStrap\Query;

use WP_Query;

/**
 * Query Class for widget and shortcode
 */
class Product extends Query
{
    /**
     * Constructor.
     *
     * @param WP_Query $query The standard query of WordPress.
     */
    // function __construct( WP_Query $query ) {

    //  $this->query = $query;

    //  global $post;
    //  $this->post = $post;

    //  if ( ! isset( self::$sticky_posts ) ) {
    //      self::$sticky_posts = get_option( 'sticky_posts' );
    //  }

    // }

    /**
     * Initialize the repository.
     *
     * @uses PHP 5.3
     *
     * @return self
     */
    public static function init()
    {

        return new self(new WP_Query());
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
         */
        $args = \ItalyStrap\Core\shortcode_atts_multidimensional_array(require(ITALYSTRAP_PLUGIN_PATH . 'config/posts.php'), $args, 'query_posts');

        $args = apply_filters('italystrap_query_posts_args', $args);

        return $args;
    }

    /**
     * Output the query result
     *
     * @return string The HTML result
     */
    public function output()
    {

        $first_tag = [];
        $first_cat = [];
        /**
         * Get the current post id
         *
         * @var int
         */
        $current_post_id = is_object($this->post) ? $this->post->ID : '';

        if (! empty($this->args['exclude_current_post'])) {
            $this->posts_to_exclude[] = (int) $current_post_id;
        }

        /**
         * I filtri 'excerpt_more' e 'excerpt_length' sono commentati perché
         * causavano dei conflitti scoperti inserendo get_the_excerpt in un template
         * di WooCommerce, fare ulteriori test ma non credo che siano da usare
         * qui poiché le impostazioni di un widget non dovrebbero sovrascrivere
         * quelle del tema.
         * @see Posts::get_query_args();
         * @see Product::get_query_args();
         */

        /**
         * Excerpt more filter
         *
         * @var function
         */
        // $new_excerpt_more = function ( $more ) {
        //  return '...';
        // };
        // add_filter( 'excerpt_more', $new_excerpt_more );

        /**
         * Excerpt length filter
         *
         * @var functions
         */
        // if ( $this->args['excerpt_length'] > 0 ) {
        //  add_filter( 'excerpt_length', function ( $length ) {
        //      return $this->args['excerpt_length'];
        //  } );
        // }

        /**
         * $class = $this->args['widget_class'];
         * var_dump($this->args['post_types']);
         * var_dump(get_users());
         */

        /**
         * Arguments for WP_Query
         *
         * @var array
         */
        $args = [
            'posts_per_page'            => $this->args['posts_number'] + count($this->posts_to_exclude),
            'order'                     => $this->args['order'],
            'orderby'                   => $this->args['orderby'],
            'post_type'                 => ( empty($this->args['post_types']) ? 'post' : explode(',', $this->args['post_types']) ),
            'no_found_rows'             => true,
            'update_post_term_cache'    => false,
            'update_post_meta_cache'    => false,
            // 'product_tag'                => 'tag',
            // 'product_cat'                => 'tag',
            'tax_query'             => [['taxonomy'      => 'product_tag', 'terms'     => ['226']]],
        ];

        /**
         * Display per post/page ID
         */
        if (! empty($this->args['post_id'])) {
            $args['post__in'] = explode(',', $this->args['post_id']);

            /**
             * This delete last comma in case the input is like 1,2,
             */
            $args['post__in'] = array_filter($args['post__in']);

            /**
             * Convert array value from string to integer
             */
            $args['post__in'] = array_map('absint', $args['post__in']);
        }

        /**
         * Sticky posts.
         */
        if ('only' === $this->args['sticky_post']) {
            $args['post__in'] = self::$sticky_posts;
        } elseif ('hide' === $this->args['sticky_post']) {
            $args['ignore_sticky_posts'] = true;
        } else {
            $args['posts_per_page'] -= count(self::$sticky_posts);
        }

        /**
         * Show the posts with tags selected
         */
        if (! empty($this->args['tags'])) {
            $args['tag__in'] = $this->args['tags'];
            $args['update_post_term_cache'] = true;
        }

        /**
         * Show related posts by tags
         * You can also select more tags to filter other than the tags assigned to post.
         */
        if (! empty($this->args['related_by_tags'])) {
            $tags = wp_get_post_terms($this->post->ID);

            if ($tags) {
                $count = is_countable($tags) ? count($tags) : 0;
                for ($i = 0; $i < $count; $i++) {
                    $first_tag[] = $tags[ $i ]->term_id;
                }
                $args['tag__in'] = array_merge($first_tag, (array) $this->args['tags']);
                $args['tag__in'] = array_flip(array_flip($args['tag__in']));
                $args['update_post_term_cache'] = true;
            }
        }

        /**
         * Show the posts with cats selected
         */
        if (! empty($this->args['cats'])) {
            $args['category__in'] = $this->args['cats'];
            $args['update_post_term_cache'] = true;
        }

        /**
         * Show related posts by cats
         * You can also select more cats to filter other than the cats assigned to post.
         */
        if (! empty($this->args['related_by_cats'])) {
            $cats = wp_get_post_terms($this->post->ID, 'category');

            if ($cats) {
                $count = is_countable($cats) ? count($cats) : 0;
                for ($i = 0; $i < $count; $i++) {
                    $first_cat[] = $cats[ $i ]->term_id;
                }
                $args['category__in'] = array_merge($first_cat, (array) $this->args['cats']);
                $args['category__in'] = array_flip(array_flip($args['category__in']));
                $args['update_post_term_cache'] = true;
            }
        }
// var_dump($this->args['from_current_user']);
        /**
         * Show posts only from current user.
         */
        if (! empty($this->args['from_current_user'])) {
            $current_user = wp_get_current_user();

            if (isset($current_user->ID)) {
                $args['author'] = $current_user->ID;
            }
        }

        if ('meta_value' === $this->args['orderby']) {
            $args['meta_key'] = $this->args['meta_key'];
        }

        $args = apply_filters('italystrap_widget_query_args', $args);
// var_dump( $args );
        $this->query->query($args);

        ob_start();

        // include $this->get_template_part();
        include get_template('/templates/content-post.php');

        wp_reset_postdata();

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
