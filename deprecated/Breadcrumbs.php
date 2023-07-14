<?php
/**
 * Abstract Asset Class API
 *
 * Handle the CSS and JS regiter and enque
 *
 * @since 2.0.0
 *
 * @package ItalyStrap\Core
 */

namespace ItalyStrap\Deprecated;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

/**
 * This class handles the Breadcrumbs generation and display
 * It use Schema.og markup for Google rich snippets
 *
 * @since 2.1.0
 * @param array $args {
 * 		Optional. An array of HTML tags
 * 		BC = BreadCrumbs
 *
 * 		@type string 'home' BC home. Default '$this->bloginfo_name'. Accepts 'string', 'HTML tags'.
 *
 * 		@type string 'open_wrapper' Open wrapper tag of BC. Default '<ol class="breadcrumb"  itemscope itemtype="https://schema.org/BreadcrumbList">'. Accepts 'string', 'HTML tags'.
 *
 * 		@type string 'closed_wrapper' Close wrapper tag of BC. Default '</ol>'. Accepts 'string', 'HTML tags'.
 *
 * 		@type string 'before_element' Tag before single element. Default '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'. Accepts 'string', 'HTML tags'.
 *
 * 		@type string 'before_element_active' Tag before single element active. Default '<li class="active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'. Accepts 'string', 'HTML tags'.
 *
 * 		@type string 'after_element' Tag after single element. Default '</li>'. Accepts 'string', 'HTML tags'.
 *
 * 		@type string 'wrapper_name' Wrapper of element name. Default '<span itemprop="name">'. Accepts 'string', 'HTML tags'.
 *
 * 		@type string 'close_wrapper_name' Close wrapper of element name. Default '</span>'. Accepts 'string', 'HTML tags'.
 * }
 * @return string
 *
 * @example
 * 		Example of how tu use
 * 		Add below code where you want to show in template files
 *
 *		$defaults = array(
 *			'home'    =>  '<span class="glyphicon glyphicon-home" aria-hidden="true"></span>'
 *			);
 *
 *		new ItalyStrapBreadcrumbs( $defaults );
 *
 * @package ItalyStrap
 * @author Enea Overclokk
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
class Breadcrumbs {

	/**
	 * Counter for Schema.org position of breadcrumbs itemlist
	 *
	 * @var int
	 */
	private $config;

	/**
	 * Counter for Schema.org position of breadcrumbs itemlist
	 *
	 * @var int
	 */
	private $count;

	/**
	 * The blog info name
	 *
	 * @var string
	 */
	protected $bloginfo_name;

	protected $template = [];

	private $args = [];

	/**
	 * Dispay breadcrumbs when a class is instantiated
	 *
	 * @param array
	 */
	public function __construct() {

		$this->bloginfo_name = \get_option( 'blogname' );
		$this->home_url = \get_home_url( null, '/' );
	}

	/**
	 * Retrieve the breadcrumbs.
	 *
	 * @since 2.1.0
	 *
	 * @param array $args Optional. See up description.
	 * @return string the breadcrumbs.
	 */
	public function get_the_breadcrumbs( $args = array() ) {

		return \ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make( $args, 'html' );

		/**
		 * Default argument for method get_the_breadcrumbs()
		 *
		 * @var string
		 * @uses wp_parse_args() Parsifica $args in entrata in un array e lo combina con l'array di default
		 * @link http://codex.wordpress.org/it:Riferimento_funzioni/wp_parse_args
		 */
		$default = array(
			'home'					=> $this->bloginfo_name,
			'open_wrapper'			=> '<ol class="breadcrumb"  itemscope itemtype="https://schema.org/BreadcrumbList">',
			'closed_wrapper'		=> '</ol>',
			'before_element'		=> '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">',
			'before_element_active'	=> '<li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">',
			'after_element'			=> '</li>',
			'wrapper_name'			=> '<span itemprop="name">',
			'close_wrapper_name'	=> '</span>',
		);

		$args = apply_filters( 'italystrap_breadcrumbs_args', array_merge( $default, $args, $this->get_template() ) );

		/**
		* Dichiara ogni elemento in $args come sua propria variabile, es. $home, $delimiter.
		*/
		extract( $args, EXTR_SKIP );

		/**
		 * Breadcrums variable
		 *
		 * @var string
		 */
		$breadcrumb = $open_wrapper;

		/**
		 * Get the first element of breadcrumbs
		 */
		if ( ( is_home() && is_front_page() ) || is_front_page() ) {

			/**
			 * This will display only in home page, static or not
			 */
			// $breadcrumb .= $before_element_active . $home . '<meta itemprop="name" content="' . $this->bloginfo_name . '" /><meta itemprop="position" content="1" />'. $after_element;

			$breadcrumb .= sprintf(
				'%s%s<meta itemprop="name" content="%s" /><meta itemprop="position" content="1" />%s',
				$before_element_active,
				$home,
				$this->bloginfo_name,
				$after_element
			);

		} else if ( is_home() && ! is_front_page() ) {

			/**
			 * This will display only in blog static page
			 */
			$breadcrumb .= $before_element .'<a itemprop="item" href="' . esc_attr( $this->home_url ) . '" title="' . $this->bloginfo_name . '">' . $home . '<meta itemprop="name" content="' . $this->bloginfo_name . '" /></a><meta itemprop="position" content="1" />'. $after_element;

			$breadcrumb .= $before_element_active . $wrapper_name . get_the_title( get_option( 'page_for_posts' ) ) . $close_wrapper_name . '<meta itemprop="position" content="2" />' . $after_element;

		} else {

			$breadcrumb .= $before_element .'<a itemprop="item" href="' . esc_attr( $this->home_url ) . '" title="' . $this->bloginfo_name . '">' . $home . '<meta itemprop="name" content="' . $this->bloginfo_name . '" /></a><meta itemprop="position" content="1" />'. $after_element;

		}

		/**
		 * Get the rest of breadcrumbs for every content type
		 */
		if ( is_attachment() ) {

			/**
			 * ID of attachemnt's parent
			 *
			 * @var int
			 */
			$parent_id = wp_get_post_parent_id( get_the_ID() );

			/**
			 * If parent post id $parent_id exist return parent post item
			 */
			if ( $parent_id ) {

				/**
				 * A WP_Post object from parent ID
				 *
				 * @var Object
				 */
				$get_post = get_post( $parent_id );
				$breadcrumb .= $before_element . '<a itemprop="item" href="' . get_permalink( $parent_id ) . '" title="' . $get_post->post_title . '">' . $wrapper_name . $get_post->post_title . $close_wrapper_name . '</a>' . '<meta itemprop="position" content="2" />' . $after_element;

				$breadcrumb .= $before_element_active . $wrapper_name . get_the_title() . $close_wrapper_name . '<meta itemprop="position" content="3" />' . $after_element;

			} else {

				$breadcrumb .= $before_element_active . $wrapper_name . get_the_title() . $close_wrapper_name . '<meta itemprop="position" content="2" />' . $after_element;
			}
		} elseif ( is_single() && ! is_attachment() ) {

			/**
			 * If article has category and has parents category too
			 */
			if ( get_the_category() && get_post_type() === 'post' ) {

				$category = get_the_category();
				$breadcrumb .= $this->get_tax_parents( $category[0], $before_element, $after_element );
			}

			/**
			 * Da fare, se non ha la categoria inserire i post format se ci sono
			 * Volendo posso aggiungere un parametro alla classe per abilitare
			 * questa funzione direttamente nello snippet
			 * !is_singular() è da togliere, non serve al massimo testarla prima
			 */
			// elseif ( has_post_format() && !is_singular() ) {
			// echo get_post_format_string( get_post_format() );
			// }
			/**
			 * Se è un custom post type stampo 2 nel tag meta
			 * altrimenti stampo il numero dalla funzione meta
			 *
			 * Da fare:
			 * Creare la gerarchie se ci sono pagine genitore per
			 * i custom post type
			 */
			if ( get_post_type() !== 'post' ) {

				/**
				 * Get post type object from CPT files
				 *
				 * @var Object
				 */
				$post_type = get_post_type_object( get_post_type() );
				/**
				 * Title of Post
				 *
				 * @var string
				 */
				$title = $post_type->labels->singular_name;
				/**
				 * Slug of post
				 *
				 * @var string
				 */
				$slug = $post_type->rewrite;

				$breadcrumb .= $before_element . '<a itemprop="item" href="' . esc_attr( $this->home_url ) . $slug['slug'] . '/' . '" title="' . $title . '">' . $wrapper_name . $title . $close_wrapper_name . '</a>' . $this->meta( $this->count, 2 ) . $after_element;

				/**
				 * Get array of all anchestor ID
				 *
				 * @var array
				 */
				$anchestor = array_reverse( get_post_ancestors( get_the_ID() ) );

				/**
				 * If there is a hierarchy of page then add post anchestor
				 */
				if ( $anchestor ) {

					$this->count = 3;

					foreach ( $anchestor as $anchestor_id ) {

						/**
						 * Single anchestor ID
						 *
						 * @var int
						 */
						$post_anchestor_id = get_post( $anchestor_id );

						$breadcrumb .= $before_element . '<a itemprop="item" href="' . get_permalink( $post_anchestor_id ) . '" title="' . $post_anchestor_id->post_title . '">' . $wrapper_name . $post_anchestor_id->post_title . $close_wrapper_name . '</a>' . $this->meta( $this->count ) . $after_element;

						$this->count++;
					}

					$breadcrumb .= $before_element_active . $wrapper_name . get_the_title() . $close_wrapper_name . $this->meta( $this->count ) . $after_element;
				} else {

					$get_object_taxonomies = get_object_taxonomies( get_post() );

					/**
					 * https://developer.wordpress.org/reference/functions/get_the_terms/
					 */
					$get_the_terms = get_the_terms( get_the_ID(), $get_object_taxonomies[0] );

					$count = 3;

					if ( $get_the_terms && ! is_wp_error( $get_the_terms ) ) {

						$sorted_terms = array();
						$this->sort_terms_hierarchically( $get_the_terms, $sorted_terms );

						$breadcrumb .= $this->sorted_term_html_output( $sorted_terms, $args, $get_object_taxonomies, $count );
					}

					/**
				 * Da fare:
				 * Verificare che il custom post sia inserito in categoria standard
				 * Se si stampare la categoria e aggiornare la posizione itemprop
				 *
				 * Verificare se esistono custom taxonomy abbinate al CPT
				 * Se si stampare le CT e aggiornare la posizione itemprop
				 */
					// if ( get_the_category() ) {
					// $category = get_the_category();
					// $breadcrumb .= $this->get_tax_parents( $category[0], $before_element, $after_element);
					// }
					$breadcrumb .= $before_element_active . $wrapper_name . get_the_title() . $close_wrapper_name . '<meta itemprop="position" content="' . $count . '" />' . $after_element;
				}

			} else {

				$breadcrumb .= $before_element_active . $wrapper_name . get_the_title() . $close_wrapper_name . $this->meta( $this->count, 1 ) . $after_element;
			}

		} elseif ( is_page() && ( ! is_front_page() ) ) {

			/**
			 * Get array of all anchestor ID
			 *
			 * @var array
			 */
			$anchestor = array_reverse( get_post_ancestors( get_the_ID() ) );

			$this->count = 2;

			foreach ( $anchestor as $anchestor_id ) {

				/**
				 * Single anchestor ID
				 *
				 * @var int
				 */
				$post_anchestor_id = get_post( $anchestor_id );

				$breadcrumb .= $before_element . '<a itemprop="item" href="' . get_permalink( $post_anchestor_id ) . '" title="' . $post_anchestor_id->post_title . '">' . $wrapper_name . $post_anchestor_id->post_title . $close_wrapper_name . '</a>' . $this->meta( $this->count ) . $after_element;

				$this->count++;
			}

			/**
			 * If is page and not front page add page title
			 */
			$breadcrumb .= $before_element_active . $wrapper_name . get_the_title() . $close_wrapper_name . $this->meta( $this->count ) . $after_element;

		} elseif ( is_tax() ) {

			$queried_object = get_queried_object();

			if ( $queried_object instanceof \WP_Term ) {

				/**
				 * If is category (default archive.php) add Category name
				 * If category has child add category child too
				 * Nota per me: togliere solo link su categoria nipote
				 * e aggiungere &before_element_active
				 */
				$breadcrumb .= $this->get_tax_parents( $queried_object->term_id, $before_element, $after_element, array(), $queried_object->taxonomy );

			}

		}  elseif ( is_category() ) {

			/**
			 * If is category (default archive.php) add Category name
			 * If category has child add category child too
			 * Nota per me: togliere solo link su categoria nipote
			 * e aggiungere &before_element_active
			 */
			$breadcrumb .= $this->get_tax_parents( get_query_var( 'cat' ), $before_element, $after_element );

		} elseif ( is_tag() ) {

			/**
			 * If is tag (default archive.php) add tag title
			 */
			$breadcrumb .= $before_element_active . $wrapper_name . __( 'Tag: ', 'italystrap' ) . single_tag_title( '', false ) . $close_wrapper_name . '<meta itemprop="position" content="2" />' . $after_element;

		} elseif ( is_post_type_archive() ) {

			/**
			 * If is Custom Post Type's archive (default archive.php)
			 * add Post Type Archive Title
			 */
			$breadcrumb .= $before_element_active . $wrapper_name . post_type_archive_title( '', false ) . $close_wrapper_name . '<meta itemprop="position" content="2" />' . $after_element;

		} elseif ( is_year() ) {

			/**
			 * If is year (default archive.php) add year
			 */
			$breadcrumb .= $before_element_active . $wrapper_name . __( 'Yearly archive: ', 'italystrap' ) . get_the_time( 'Y' ) . $close_wrapper_name . '<meta itemprop="position" content="2" />' . $after_element;

		} elseif ( is_month() ) {

			/**
			 * If is month (default archive.php) add year with link and month name
			 *
			 * Get the year time
			 *
			 * @var int
			 */
			$year = get_the_time( 'Y' );

			$breadcrumb .= $before_element . '<a itemprop="item" href="' . get_year_link( $year ) . '" title="' . $year . '">' . $wrapper_name . $year . $close_wrapper_name . '</a>' . '<meta itemprop="position" content="2" />' . $after_element;

			$breadcrumb .= $before_element_active . $wrapper_name . __( 'Monthly archive: ', 'italystrap' ) . get_the_time( 'F' ) . $close_wrapper_name . '<meta itemprop="position" content="3" />' . $after_element;

		} elseif ( is_day() ) {

			/**
			 * If is day (default archive.php) add year with link,
			 * month with link and day number
			 *
			 * Get the year time
			 *
			 * @var int
			 */
			$year = get_the_time( 'Y' );
			/**
			 * Get the month time
			 *
			 * @var int
			 */
			$month = get_the_time( 'm' );

			$breadcrumb .= $before_element . '<a itemprop="item" href="' . get_year_link( $year ) . '" title="' . $year . '">' . $wrapper_name . $year . $close_wrapper_name . '</a><meta itemprop="position" content="2" />' . $after_element;

			$breadcrumb .= $before_element . '<a itemprop="item" href="' . get_month_link( $year, $month ) . '" title="' . $month . '">' . $wrapper_name . $month . $close_wrapper_name . '</a><meta itemprop="position" content="3" />' . $after_element;

			$breadcrumb .= $before_element_active . $wrapper_name . __( 'Daily archive: ', 'italystrap' ) . get_the_time( 'd' ) . $close_wrapper_name . '<meta itemprop="position" content="4" />' . $after_element;

		} elseif ( is_author() ) {

			/**
			 * If is author (default archive.php) add author name
			 */
			$breadcrumb .= $before_element_active . $wrapper_name . __( 'Author Archives: ', 'italystrap' ) . get_the_author() . $close_wrapper_name . '<meta itemprop="position" content="2" />' . $after_element;

		} elseif ( is_search() ) {

			/**
			 * If is search (default search.php) add search query
			 */
			$breadcrumb .= $before_element_active . $wrapper_name . __( 'Search Results: ', 'italystrap' ) . get_search_query() . $close_wrapper_name . '<meta itemprop="position" content="2" />' . $after_element;

		} elseif ( is_404() ) {
			/**
			 * If is 404
			 */
			$breadcrumb .= $before_element_active . $wrapper_name . __( 'Not Found', 'Italystrap' ) . $close_wrapper_name . '<meta itemprop="position" content="2" />' . $after_element;
		}

		/**
		 * If is paginated page add (Page n°) at the end of breadcrumb
		 * This has <small> tag
		 */
		if ( get_query_var( 'paged' ) ) {

			$breadcrumb .= ' - <small>(' . __( 'Page', 'italystrap' ) . ' ' . get_query_var( 'paged' ) . ')</small>';

		}

		$breadcrumb .= $closed_wrapper;

		/**
		 * The breadcrumb
		 *
		 * @since 2.1
		 *
		 * @param string $breadcrumb the breadcrumb to be displayed.
		 */
		return $breadcrumb;
	}

	/**
	 * Display the breadcrumbs.
	 *
	 * @since 2.1.0
	 *
	 * @param array $args Optional. Content to prepend to the breadcrumbs.
	 * @see  See description at the top of this page
	 */
	public function render( $args = array(), $echo = true ) {

		$output = $this->get_the_breadcrumbs( $args );

		if ( ! $echo ) {
			return $output;
		}

		echo $output;
	}

	/**
	 * Get the correct position for current breadcrumbs Schema.org markup
	 *
	 * @since 2.1.0
	 *
	 * @param  int $count A counter variable.
	 * @param  int $num Optional, a number for coming count position.
	 * @return string A meta tag for Schema.org markup
	 */
	private function meta( $count, $num = 0 ) {

		$count = (int) $count + (int) $num;
		return '<meta itemprop="position" content="' . $count . '" />';
	}

	/**
	 * Retrieve category parents.
	 *
	 * Da fare per le performance:
	 * Modificare il metodo italystrap_get_tax_parents e creare un loop normale
	 * la ricorsione è una martellata sui maroni
	 * ma è comunque abbastanza veloce :-P
	 *
	 * @since 2.1.0 (From WP core 1.2.0)
	 *
	 * @see get_tax_parents
	 * @link https://core.trac.wordpress.org/browser/tags/4.1/src/wp-includes/category-template.php#L42 Original function
	 *
	 * @param int    $id Category ID.
	 * @param string $before_element Open HTML tag for element.
	 * @param string $after_element Close HTML tag for element.
	 * @param array  $visited Optional. Already linked to categories to prevent duplicates.
	 * @return string|WP_Error A list of category parents on success, WP_Error on failure.
	 */
	private function get_tax_parents( $id, $before_element, $after_element, $visited = array(), $tax = 'category' ) {

		$chain = '';
		$parent = get_term( $id, $tax );

		if ( is_wp_error( $parent ) ) {
			return $parent;
		}

		$name = $parent->name;

		/**
		 * Static var for counting the number of time a recursive
		 * function is called
		 *
		 * @link http://stackoverflow.com/questions/17082608/counting-the-number-of-times-a-recursive-function-is-called
		 *
		 * @var integer
		 */
		static $i = 2;

		if ( $parent->parent && ( $parent->parent != $parent->term_id ) && ! in_array( $parent->parent, $visited, true ) ) {

			$visited[] = $parent->parent;

			// http://devzone.zend.com/283/recursion-in-php-tapping-unharnessed-power/
			$chain .= $this->get_tax_parents( $parent->parent, $before_element, $after_element, $visited, $tax );
			$i++;
		}

		$this->count = $i;

		if ( get_category_link( $parent->term_id ) ) {
			$chain .= $before_element . '<a itemprop="item" href="' . esc_url( get_category_link( $parent->term_id ) ) . '" title="' . $name . '"><span itemprop="name">'. $name .'</span></a><meta itemprop="position" content="' . $i . '" />'. $after_element;
		} else {
			$chain .= $before_element . $name . $after_element;
		}

			return $chain;
	}

	/**
	 * Display the breadcrumbs.
	 *
	 * @since 2.1.0
	 *
	 * @deprecated [<version>] Use the new method CLASS::render()
	 */
	public function the_breadcrumbs( $args = array() ) {
		_deprecated_function( __METHOD__, '2.0.0', '::render()' );
		$this->render( $args, true );
	}

	/**
	 * Set template
	 */
	public function set_template( array $template = [] ) {
		$this->template = $template;
	}

	/**
	 * Set template
	 */
	private function get_template() {
		return $this->template;
	}

	/**
	 * https://wordpress.stackexchange.com/a/239935
	 */
	private function sort_terms_hierarchically( array &$terms, array &$into, $parent_id = 0 ) {
		foreach ( $terms as $i => $term ) {
			if ( $term->parent === $parent_id ) {
				$into[ $term->term_id ] = $term;
				unset( $terms[ $i ] );
			}
		}

		foreach ( $into as $top_term ) {
			$top_term->children = array();
			$this->sort_terms_hierarchically( $terms, $top_term->children, $top_term->term_id );
		}

	}

	/**
	 * Get_term_flat
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	private function get_terms_flat( $terms ) {

		static $new_terms = [];

		foreach ( $terms as $term ) {
			$this->get_terms_flat( $term->children );
			$new_terms[ $term->term_id ] = $term;
		}

		return $new_terms;
	}

	/**
	 * Sorted term html
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function sorted_term_html_output( array $terms = [], array $args = [], $get_object_taxonomies = null, $count = 0 ) {

		$breadcrumb = '';

		$terms = $this->get_terms_flat( $terms );
		$terms = array_reverse( $terms );

		foreach ( $terms as $term ) {
			$term_link = get_term_link( $term, $get_object_taxonomies[0] );

			$breadcrumb .= sprintf(
				'%s<a href="%s">%s%s%s%s</a>%s',
				$args['before_element'],
				esc_url( $term_link ),
				$args['wrapper_name'],
				$term->name,
				$args['close_wrapper_name'],
				$this->meta( $count ),
				$args['after_element']
			);

			$count++;
		}

		return $breadcrumb;

	}
}
