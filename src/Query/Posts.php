<?php

/**
 * This class is for elaborating the arguments from widget or shortcode and then return an HTML for display the posts/page result.
 *
 * @package Query_Posts
 * @version 1.0
 * @since   2.0
 */

namespace ItalyStrap\Query;

use WP_Query;
use ItalyStrap\Excerpt\Excerpt;
use ItalyStrap\Config\Config;

/**
 * Query Class for widget and shortcode
 */
class Posts extends Query
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
    // public static function init( $context = null ) {

    //  return new self( new WP_Query(), new Excerpt( new Config() ), new Config(), $context );

    // }

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
     * Get the query arguments
     *
     * @param array $args
     * @return string        [description]
     */
    public function get_query_args(array $args = [])
    {

        $first_tag = [];
        $first_cat = [];
        /**
         * Get the current post id
         *
         * @var int
         */
        // $this->current_post_id = is_object( $this->post ) ? $this->post->ID : '';
        /**
         * Questa è da testare meglio perché in situazioni in cui global $post
         * non è settata non ottengo il suo id per esempio se utilizzo
         * questo blocco in una posizione fuori dal loop standard (vedi helpcode places)
         *
         * Per esempio con l'hook After the header o prima
         *
         * Meglio eseguire ulteriori test
         */
        $this->current_post_id = get_queried_object_id();

        if (! empty($this->config['exclude_current_post'])) {
            if (is_single()) {
                $this->posts_to_exclude[] = (int) $this->current_post_id;
            }
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

        // $excerpt_length = $this->config['excerpt_length'];

        /**
         * Excerpt length filter
         *
         * @var functions
         */
        // if ( $this->config['excerpt_length'] > 0 ) {
        //  add_filter( 'excerpt_length', function ( $length ) use ( $excerpt_length ) {
        //      return $excerpt_length;
        //  } );
        // }

        /**
         * $class = $this->config['widget_class'];
         * var_dump($this->config['post_types']);
         * var_dump(get_users());
         */

        /**
         * Arguments for WP_Query
         *
         * @var array
         */
        $query_args = ['posts_per_page'            => absint($this->config['posts_number']) + count($this->posts_to_exclude), 'order'                     => $this->config['order'], 'orderby'                   => $this->config['orderby'], 'post_type'                 => $this->postType(), 'no_found_rows'             => true, 'update_post_term_cache'    => false, 'update_post_meta_cache'    => false];

        if (! empty($this->config['most_viewed'])) {
            $query_args['post__in'] = $this->get_posts_ids_by_views($this->config);
        }

        /**
         * Display per post/page ID
         */
        if (! empty($this->config['post_id'])) {
            $query_args['post__in'] = explode(',', $this->config['post_id']);

            /**
             * This delete last comma in case the input is like 1,2,
             */
            $query_args['post__in'] = array_filter($query_args['post__in']);

            /**
             * Convert array value from string to integer
             */
            $query_args['post__in'] = array_map('absint', $query_args['post__in']);

            /**
             * If post_id isset than posts_per_page will be override by the number of post ID.
             */
            $query_args['posts_per_page'] = count($query_args['post__in']);
        }

        /**
         * Sticky posts.
         */
        if ('only' === $this->config['sticky_post']) {
            $query_args['post__in'] = self::$sticky_posts;
        } elseif ('hide' === $this->config['sticky_post']) {
            $query_args['ignore_sticky_posts'] = true;
        } else {
            $query_args['posts_per_page'] -= count(self::$sticky_posts);
        }

        /**
         * Show the posts with tags selected
         */
        if (! empty($this->config['tags'])) {
            $query_args['tag__in'] = $this->config['tags'];
            $query_args['update_post_term_cache'] = true;
        }

        /**
         * Show related posts by tags
         * You can also select more tags to filter other than the tags assigned to post.
         */
        if (! empty($this->config['related_by_tags'])) {
            $tags = wp_get_post_terms($this->current_post_id);

            if ($tags) {
                $count = is_countable($tags) ? count($tags) : 0;
                for ($i = 0; $i < $count; $i++) {
                    $first_tag[] = $tags[ $i ]->term_id;
                }
                $query_args['tag__in'] = array_merge($first_tag, (array) $this->config['tags']);
                $query_args['tag__in'] = array_flip(array_flip($query_args['tag__in']));
                $query_args['update_post_term_cache'] = true;
            }
        }

        /**
         * Show the posts with cats selected
         */
        if (! empty($this->config['cats'])) {
            $query_args['category__in'] = $this->config['cats'];
            $query_args['update_post_term_cache'] = true;
        }

        /**
         * Show related posts by cats
         * You can also select more cats to filter other than the cats assigned to post.
         */
        if (! empty($this->config['related_by_cats'])) {
            $cats = wp_get_post_terms($this->current_post_id, 'category');

            if ($cats) {
                $count = is_countable($cats) ? count($cats) : 0;
                for ($i = 0; $i < $count; $i++) {
                    $first_cat[] = $cats[ $i ]->term_id;
                }
                $query_args['category__in'] = array_merge($first_cat, (array) $this->config['cats']);
                $query_args['category__in'] = array_flip(array_flip($query_args['category__in']));
                $query_args['update_post_term_cache'] = true;
            }
        }


    // d( get_taxonomies() );
    // d( $query_args['post_type'] );
    // d( get_object_taxonomies( $query_args['post_type'] ) );

        // $query_args['tax_query'] = array(
        //  // 'relation'   => 'OR',
        //  array(
        //      'taxonomy'  => 'team-groups',
        //      'field'     => 'slug',
        //      'terms'     => 'consiglio-direttivo',
        //  ),
        //  // array(
        //  //  'taxonomy'  => 'team-groups',
        //  //  'field'     => 'term_id',
        //  //  'terms'     => [6],
        //  // ),
        //  // array(
        //  //  'taxonomy'  => 'post_format',
        //  //  'field'     => 'slug',
        //  //  'terms'     => array( 'post-format-quote' ),
        //  // ),
        // );

        // $query_args['tax_query'] = array(
        //  'relation'  => 'OR',
        //  array(
        //      'taxonomy'  => 'category',
        //      'field'     => 'slug',
        //      'terms'     => array( 'quotes' ),
        //  ),
        //  array(
        //      'relation'  => 'AND',
        //      array(
        //          'taxonomy'  => 'post_format',
        //          'field'     => 'slug',
        //          'terms'     => array( 'post-format-quote' ),
        //      ),
        //      array(
        //          'taxonomy'  => 'category',
        //          'field'     => 'slug',
        //          'terms'     => array( 'wisdom' ),
        //      ),
        //  ),
        // );

        /**
         * Show posts only from current user.
         */
        if (! empty($this->config['from_current_user'])) {
            $current_user = wp_get_current_user();

            if (isset($current_user->ID)) {
                $query_args['author'] = $current_user->ID;
            }
        }

        if ('meta_value' === $this->config['orderby']) {
            $query_args['meta_key'] = $this->config['meta_key'];
        }

        if (! empty($this->config['offset'])) {
            $query_args['offset'] = absint($this->config['offset']);
        }

        if (! empty($this->config['connected_type'])) {
            $query_args['connected_type'] = $this->config['connected_type'];

            /**
             * This is because if you use Shortcake get_queried_object() is null in the editor
             * and then you have to use get_post() instead.
             */
            $query_args['connected_items'] = is_admin() ? get_post() : get_queried_object();
            $query_args['nopaging'] = true;
        }

        $query_args = wp_parse_args($args, $query_args);

        /**
         * Back compat with old filter name
         *
         * @var array
         */
        $query_args = apply_filters('italystrap_main_query_arg', $query_args);

        /**
         * The context for this instance
         * usefull for filter the query_args in a specific "context"
         *
         * @var string
         */
        $context = empty($this->config['context']) ? $this->context : $this->config['context'];

        return apply_filters("italystrap_{$context}_query_args", $query_args);
    }

    /**
     * Output the query result
     *
     * @return string The HTML result
     */
    public function output(array $query_args = [])
    {
        return $this->render($query_args);
    }

    /**
     * Render the query result
     *
     * @TODO https://gist.github.com/gmazzap/c3e29d8999ec1b722dd17b87dddac62e
     *
     * @return string The HTML result
     */
    public function render(array $query_args = [])
    {

        $this->query->query($this->get_query_args($query_args));

        ob_start();

        include $this->get_template_part();

        $output = ob_get_contents();
        ob_end_clean();

        wp_reset_postdata();

        return $output;
    }

    /**
     * Get custom fields
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_custom_fields($value = '')
    {

        if ($this->config['custom_fields']) :
            $custom_field_name = explode(',', $this->config['custom_fields']); ?>

            <div class="entry-custom-fields">
            <?php
            foreach ($custom_field_name as $name) :
                $name = trim($name);
                $custom_field_values = get_post_meta($this->query->post->ID, $name);

                if ($custom_field_values) :
                    ?>
                    <div class="custom-field custom-field-<?php echo esc_attr(str_replace('_', '-', ltrim($name, '_'))); ?>">
                        <?php
                        /**
                         * If is not an array echo custom field value
                         */
                        if (! is_array($custom_field_values)) {
                            echo esc_attr($custom_field_values);
                        } else {
                            $last_value = end($custom_field_values);
                            foreach ($custom_field_values as $value) {
                                echo esc_attr($value);

                                if ($value !== $last_value) {
                                    echo ', ';
                                }
                            }
                        }
                        ?>
                    </div>
                <?php endif;
            endforeach; ?>
            </div>
            <?php
        endif;
    }

    /**
     * Read More Link
     */
    public function read_more_link()
    {

        if (empty($this->config['show_readmore'])) {
            return;
        }

        $attr = ['class' => $this->config['read_more_class']];

        $link_text = $this->config['excerpt_readmore'];

        if ($this->config['use_global_read_more'] && $this->excerpt->is_global_read_more_active()) {
            $attr['class'] = '';
            $link_text = '';
        }

        echo $this->excerpt->read_more_link('widget_post_read_more', $attr, $link_text);
    }

    /**
     * @return string|string[]
     */
    private function postType()
    {

        if (empty($this->config[ 'post_types' ])) {
            return 'post';
        }

        if (\is_string($this->config[ 'post_types' ])) {
            $this->config[ 'post_types' ] = (array) explode(',', $this->config[ 'post_types' ]);
            $this->config[ 'post_types' ] = \array_map('trim', $this->config[ 'post_types' ]);
        }

        $this->config[ 'post_types' ] = (array) $this->config[ 'post_types' ];

        if (\count($this->config[ 'post_types' ]) === 1) {
            return $this->config[ 'post_types' ][0];
        }

        return $this->config[ 'post_types' ];
    }
}
