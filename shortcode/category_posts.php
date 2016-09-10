<?php namespace ItalyStrap\Shortcode;

use \WP_Query;

/**
 * Classe per la documentazione
 */
class Category_Posts {

	/**
	 * Inizializzo il costruttore
	 */
	function __construct() {

		// add_shortcode( 'docs', array( $this, 'docs' ) );

	}

	/**
	 * Aggiungo lo shortcode per mostrare il loop dei plus
	 * @param  array $atts Attributi dello shortcode.
	 */
	function docs( $atts ) {

		$output = '';

		// $base_categories = get_categories( array(
		// 	'taxonomy' => 'category',
		// 	'parent' => 0,
		// 	'hide_empty' => 0,
		// ) );

		/**
		 * Array delle categorie
		 * @todo Mettere a posto la cache per le varie query, leggere 10up su github
		 * @var array
		 */
		$base_categories = $this->get_category_array( true );

		$output .= '<div class="row docs">';

		foreach ( (array) $base_categories as $category ) :

			if ( 0 === $category->count ) {
				continue;
			}

			$output .= '<div class="col-md-6 col-sm-6">';
			$term_link = esc_url( get_term_link( $category ) );
			$output .= '<header><h2><i class="fa fa-folder-o"></i> <a href="' . $term_link . '">' . $category->name . '</a> <small>( ' . sprintf( _n( '%1$s Article', '%1$s Articles', $category->count, 'italystrap' ), number_format_i18n( $category->count ) ) . ' )</small></h2>';
			$output .= '<p>' . $category->description . '</p></header>';

			$sub_categories = get_categories( array(
				'taxonomy' => 'category',
				'parent' => $category->term_id,
				'hide_empty' => 0,
				'number' => '5',
			) );

			if ( $sub_categories ) {

				$output .= '<ul class="list-unstyled">';

				foreach ( (array) $sub_categories as $sub_category ) {

					$output .= '<li><i class="fa fa-folder"></i> ';
					$sub_term_link = esc_url( get_term_link( $sub_category ) );
					$output .= '<a href="' . $sub_term_link . '">' . $sub_category->name . '</a> <small>( ' . sprintf( _n( '%1$s Article', '%1$s Articles', $sub_category->count, 'italystrap' ), number_format_i18n( $sub_category->count ) ) . ' )</small>';
					$output .= '</li>';

				}

				$output .= '</ul>';
			}

			$category_posts = new WP_Query( array(
				'no_found_rows'		=> true,
				'posts_per_page'	=> 5,
				// 'cat' => $category->term_id,
				'category__in'		=> $category->term_id,
			) );

			if ( $category_posts->have_posts() ) :

				$output .= '<ul class="list-unstyled">';

				while ( $category_posts->have_posts() ) :

					$category_posts->the_post();

					$output .= '<li><i class="fa fa-file-text-o"></i> ';

					$output .= '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';

					$output .= '</li>';

				endwhile;

				$output .= '</ul>';

				$output .= '<i class="fa fa-arrow-circle-o-right"></i> <a class="hkb-category__view-all" href="' . $term_link . '">View all</a>';

			endif;

			wp_reset_postdata();

			$output .= '</div>';

		endforeach;

		$output .= '</div>';

		echo $output;

	}

	/**
	 * Retrieve top 10 most-commented posts and cache the results.
	 *
	 * @param bool $force_refresh Optional. Whether to force the cache to be refreshed.
	 *                            Default false.
	 * @return array|WP_Error Array of WP_Post objects with the highest comment counts,
	 *                        WP_Error object otherwise.
	 */
	function get_category_array( $force_refresh = false ) {
		// Check for the category_array key in the 'category_array' group.
		$category_array = wp_cache_get( 'italystrap_category_array', 'category_array' );

		// If nothing is found, build the object.
		if ( true === $force_refresh || false === $category_array ) {
			// Grab the top 10 most commented posts.
			// $category_array = new WP_Query( 'orderby=comment_count&posts_per_page=10' );
			$category_array = get_categories( array(
				'taxonomy' => 'category',
				'parent' => 0,
				'hide_empty' => 0,
			) );

			if ( ! is_wp_error( $category_array ) ) {
				// In this case we don't need a timed cache expiration.
				wp_cache_set( 'italystrap_category_array', $category_array, 'category_array' );
			}
		}
		return $category_array;
	}
}
