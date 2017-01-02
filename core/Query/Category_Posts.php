<?php namespace ItalyStrap\Core\Query;

use \WP_Query;

/**
 * Classe per la documentazione
 */
class Category_Posts {

	/**
	 * Pluin options
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Inizializzo il costruttore
	 */
	function __construct( array $options = array(), Posts $query = null ) {

		$this->options = $options;

		$this->query = $query;

		// add_shortcode( 'docs', array( $this, 'docs' ) );

	}

	/**
	 * Get shortcode attributes.
	 *
	 * @param  array $args The carousel attribute.
	 * @return array Mixed array of shortcode attributes.
	 */
	public function get_attributes( array $instance ) {

		/**
		 * Define data by given attributes.
		 */
		$instance = \ItalyStrap\Core\shortcode_atts_multidimensional_array( require( ITALYSTRAP_PLUGIN_PATH . 'config/taxonomies-posts.php' ), $instance, 'query_posts' );

		$instance = apply_filters( 'italystrap_query_posts_args', $instance );

		$this->args = $instance;

		return $instance;

	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function get_posts( $category_term_id ) {

		// $query_posts = Posts::init();

		// $query_posts->get_widget_args( $instance );

		// return $query_posts->output();
		// echo "<pre>";
		// print_r($this->query->query);
		// print_r($query_posts);
		// echo "</pre>";
		//
	
		$output = '';

		$args = array(
			'no_found_rows'		=> true,
			'posts_per_page'	=> 5,
			// 'cat' => $category->term_id,
			'category__in'		=> $category_term_id,
		);

		$query = new WP_Query( $args );
		// $query = $this->query->get_attributes( $args );

		if ( $query->have_posts() ) :

			$output .= '<ul class="list-unstyled">';

			while ( $query->have_posts() ) :

				$query->the_post();

				$output .= '<li><i class="fa fa-file-text-o"></i> ';
				// $output .= get_the_post_thumbnail( $query->post->ID );
				$output .= get_the_post_thumbnail( $query->post->ID, 'thumbnail' );

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
	public function get_tax_query_args( array $tax_query_args = array(), $tax = 'category'  ) {

		$defaults = array(
			'taxonomy'		=> $tax,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 0,
			// 'include'		=> 0, // (array|string) Array or comma/space-separated string of term ids to include.
			// 'exclude'		=> 0, // (array|string) Array or comma/space-separated string of term ids to exclude. If $include is non-empty, $exclude is ignored.
			// 'exclude_tree'	=> 0, // (array|string) Array or comma/space-separated string of term ids to exclude along with all of their descendant terms. If $include is non-empty, $exclude_tree is ignored.
			// 'number'			=> 0, // (int|string) Maximum number of terms to return. Accepts ''|0 (all) or any positive number. Default ''|0 (all).
			// 'offset'			=> 0, // (int) The number by which to offset the terms query.
			// 'fields'			=> 0, // (string) Term fields to query for. Accepts 'all' (returns an array of complete term objects), 'ids' (returns an array of ids), 'id=>parent' (returns an associative array with ids as keys, parent term IDs as values), 'names' (returns an array of term names), 'count' (returns the number of matching terms), 'id=>name' (returns an associative array with ids as keys, term names as values), or 'id=>slug' (returns an associative array with ids as keys, term slugs as values). Default 'all'.
			// 'name'			=> 0, // (string|array) Optional. Name or array of names to return term(s) for.
			// 'slug'			=> 0, // (string|array) Optional. Slug or array of slugs to return term(s) for. 
			// 'hierarchical'	=> 0, // (bool) Whether to include terms that have non-empty descendants (even if $hide_empty is set to true). Default true.
			// 'search'	=> 0, // (string) Search criteria to match terms. Will be SQL-formatted with wildcards before and after.
			// 'name__like'	=> 0, // (string) Retrieve terms with criteria by which a term is LIKE $name__like.
			// 'description__like'	=> 0, // (string) Retrieve terms where the description is LIKE $description__like.
			// 'pad_counts'	=> 0, // (bool) Whether to pad the quantity of a term's children in the quantity of each term's "count" object variable. Default false.
			// 'get'	=> 0, // (string) Whether to return terms regardless of ancestry or whether the terms are empty. Accepts 'all' or empty (disabled).
			// 'child_of'	=> 0, // (int) Term ID to retrieve child terms of. If multiple taxonomies are passed, $child_of is ignored. Default 0.
			'parent'		=> 0,
			// 'childless'	=> 0, // (bool) True to limit results to terms that have no children. This parameter has no effect on non-hierarchical taxonomies. Default false.
			// 'cache_domain'	=> 0, // (string) Unique cache key to be produced when this query is stored in an object cache. Default is 'core'.
			// 'update_term_meta_cache'	=> 0, // (bool) Whether to prime meta caches for matched terms. Default true.
			// 'meta_query'	=> 0, // (array) Meta query clauses to limit retrieved terms by. See WP_Meta_Query.
			// 'meta_key'	=> 0, // (string) Limit terms to those matching a specific metadata key. Can be used in conjunction with $meta_value.
			// 'meta_value'	=> 0, // (string) Limit terms to those matching a specific metadata value. Usually used in conjunction with $meta_key.
		);

		// if ( ! empty( $this->args['include'] ) ) {
		// 	$defaults['include'] = $this->args['include'];
		// }

		// if ( ! empty( $this->args['exclude'] ) ) {
		// 	$defaults['exclude'] = $this->args['exclude'];
		// }
		$this->set_tax_query_args( $defaults );

		return wp_parse_args( $tax_query_args, $defaults );
	}

	/**
	 * Continue after first call
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function set_tax_query_args( &$defaults ) {

		static $count = 0;

		if ( 1 <= $count ) {
			return;
		}
	
		if ( ! empty( $this->args['include'] ) ) {
			$defaults['include'] = $this->args['include'];
		}

		if ( ! empty( $this->args['exclude'] ) ) {
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
	function get_category_array( $force_refresh = false, $tax = 'category', array $args = array(), $id = null ) {

		if ( ! isset( $id ) ) {
			$id = $tax;
		}

		// Check for the category_array key in the 'category_posts' group.
		$category_array = wp_cache_get( "italystrap_{$id}_array", 'category_posts' );

		// If nothing is found, build the object.
		if ( true === $force_refresh || false === $category_array ) {
			// Grab the top 10 most commented posts.
			$category_array = get_categories( $this->get_tax_query_args( $args, $tax ) );

			if ( ! is_wp_error( $category_array ) ) {
				// In this case we don't need a timed cache expiration.
				wp_cache_set( "italystrap_{$id}_array", $category_array, 'category_posts' );
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
	public function get_categories( $parent_id ) {

		$args = array(
			'parent'		=> $parent_id,
			// 'number'		=> '5',
		);

		$categories = $this->get_category_array( false, 'category', $args, $parent_id );

		if ( empty( $categories ) ) {
			return;
		}

		$output = '';

		$output .= '<ul class="list-unstyled">';

		foreach ( (array) $categories as $category ) {

			if ( 0 === $category->count ) {
				continue;
			}
			$output .= sprintf(
				'<li><i class="fa fa-folder"></i> <strong><a href="%s">%s</a></strong> <small>(%s)</small></li>',
				esc_url( get_term_link( $category ) ),
				$category->name,
				sprintf(
					_n( '%1$s Article', '%1$s Articles', $category->count, 'italystrap' ),
					number_format_i18n( $category->count )
				)
			);

		}

		$output .= '</ul>';

		return $output;
	
	}

	/**
	 * Aggiungo lo shortcode per mostrare il loop dei plus
	 * @param  array $atts Attributi dello shortcode.
	 */
	public function output( $atts = null ) {

		$query_posts = Posts::init();

		$query_posts->get_widget_args( $this->args );

		$output = '';

		/**
		 * Array delle categorie
		 * @todo Mettere a posto la cache per le varie query, leggere 10up su github
		 * @var array
		 */
		$categories = $this->get_category_array();

		$output .= sprintf(
			'<div class="%s">',
			! empty( $this->args['tax_container_class'] ) ? esc_attr( $this->args['tax_container_class'] ) : ''
		);

		foreach ( (array) $categories as $category ) :

			if ( 0 === $category->count ) {
				continue;
			}

			/**
			 * @see http://wordpress.stackexchange.com/questions/169469/get-posts-from-child-categories-with-parent-category-id
			 */
			$query_args = array(
				'category__in' 			=> $category->term_id,
				// 'tax_query' 			=> array(
				// 	array(
				// 		'taxonomy'	=> 'category',
				// 		'field'		=> 'term_id',
				// 		'terms'		=> $category->term_id,
				// 	),
				// ),
				'update_post_term_cache' => true
			);

			$output .= sprintf(
				'<div class="%s"><header><h2 class="entry-title-category"><i class="fa fa-folder-o"></i> <a href="%s">%s</a> <small>(%s)</small></h2><p>%s</p></header>%s%s</div>',
				! empty( $this->args['tax_class'] ) ? esc_attr( $this->args['tax_class'] ) : '',
				esc_url( get_term_link( $category ) ),
				$category->name,
				sprintf(
					_n( '%1$s Article', '%1$s Articles', $category->count, 'italystrap' ),
					number_format_i18n( $category->count )
				),
				$category->description,
				$this->get_categories( $category->term_id ),
				// ''
				// $this->get_posts( $category->term_id )
				$query_posts->output( $query_args )
			);

		endforeach;

		$output .= '</div>';

		return $output;
	}
}
