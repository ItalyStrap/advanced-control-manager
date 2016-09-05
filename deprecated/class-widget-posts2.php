<?php namespace ItalyStrap\Widget;

use \WP_Widget;
use \WP_Query;

/**
 * This class is forked from Ultimate Posts Widget by Boston Dell-Vandenberg
 * http://pomelodesign.com and modify by me with a lot of functionality
 * and schema.org markup.
 * Original plugin here http://wordpress.org/plugins/ultimate-posts-widget/
 */
class Widget_Posts2 extends Widget {

	public function widget_render( $args, $instance ) {}

	/**
	 * Init the post widget
	 */
	function __construct() {

		/**
		 * I don't like this and I have to find a better solution for loading script and style for widgets.
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'upload_scripts' ) );

		$widget_options = array(
			'classname' => 'italystrap_posts_widget',
			'description' => __( 'Displays list of posts with an array of options (DEPRECATED)', 'italystrap' ),
			);

		$control_options = array(
			'width' => 450,
			);

		parent::__construct(
			'sticky-posts',
			__( 'ItalyStrap Posts Widget', 'italystrap' ),
			$widget_options,
			$control_options
		);

		$this->alt_option_name = 'italystrap_posts_widget';

		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );

		// add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin_scripts' ) );

		// add_action( 'admin_enqueue_scripts', array( $this, 'upload_scripts' ) );
		/**
		 * Valutare se inserire uno stile predefinito.
		 * if (apply_filters('italystrap_enqueue_styles', true) && !is_admin()) {
		 * 	add_action('wp_enqueue_scripts', array(&$this, 'enqueue_theme_scripts'));
		 * }
		 */

	}
	/**
	 * Widget method
	 * @param  array $args     Array of widget's arguments.
	 * @param  array $instance Array with widget value.
	 */
	function widget( $args, $instance ) {

		global $post;
		$current_post_id = is_object( $post ) ? $post->ID : '';

		$cache = wp_cache_get( 'italystrap_posts_widget', 'widget' );

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		/**
		 * Da vedere se serve, ma non credo.
		 * ob_start();
		 */
		ob_start();
		foreach ( $args as $key => $value)
			$$key = $value;

		$class = $instance['class'];
		$number = empty( $instance['number'] ) ? -1 : $instance['number'];
		$types = empty( $instance['types'] ) ? 'any' : explode( ',', $instance['types'] );
		$cats = empty( $instance['cats'] ) ? '' : explode( ',', $instance['cats'] );
		$tags = empty( $instance['tags'] ) ? '' : explode( ',', $instance['tags'] );
		$atcat = $instance['atcat'] ? true : false;
		$thumb_size = $instance['thumb_size'];
		$attag = $instance['attag'] ? true : false;
		$excerpt_length = $instance['excerpt_length'];
		$excerpt_readmore = $instance['excerpt_readmore'];
		$sticky = $instance['sticky'];
		$order = $instance['order'];
		$orderby = $instance['orderby'];
		$meta_key = $instance['meta_key'];
		$custom_fields = $instance['custom_fields'];

		/**
		 * Sticky posts.
		 */
		if ( 'only' === $sticky ) {

			$sticky_query = array( 'post__in' => get_option( 'sticky_posts' ) );

		} elseif ( 'hide' === $sticky ) {

			$sticky_query = array( 'post__not_in' => get_option( 'sticky_posts' ) );

		} else {

			$sticky_query = null;

		}

		/**
		 * If $atcat true and in category
		 */
		if ( $atcat && is_category() ) {

			$cats = get_query_var( 'cat' );

		}

		/**
		 * If $atcat true and is single post
		 */
		if ( $atcat && is_single() ) {
			$cats = '';
			foreach ( get_the_category() as $catt ) {
				$cats .= $catt->term_id.' ';
			}
			$cats = str_replace( ' ', ',', trim( $cats ) );
		}

		/**
		 * If $attag true and in tag
		 */
		if ( $attag && is_tag() ) {
			$tags = get_query_var( 'tag_id' );
		}

		/**
		 * If $attag true and is single post
		 */
		if ( $attag && is_single() ) {
			$tags = '';
			$thetags = get_the_tags();
			if ( $thetags ) {
				foreach ( $thetags as $tagg ) {
					$tags .= $tagg->term_id . ' ';
				}
			}
			$tags = str_replace( ' ', ',', trim( $tags ) );
		}

		/**
		 * Excerpt more filter
		 * @var function
		 */
		$new_excerpt_more = create_function( '$more', 'return "...";' );
		add_filter( 'excerpt_more', $new_excerpt_more );

		// Excerpt length filter
		$new_excerpt_length = create_function( '$length', 'return ' . $excerpt_length . ';' );
		if ( $instance['excerpt_length'] > 0 ) add_filter( 'excerpt_length', $new_excerpt_length );

		if ( $class ) {
			$before_widget = str_replace( 'class="', 'class="'. $class . ' ', $before_widget );
		}

		echo $before_widget;

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		if ( $title && $instance['title_link'] )
			echo $before_title . apply_filters( 'italystrap_widget_title_link', '<a href="' . esc_html( $instance['title_link'] ) . '">' . esc_attr( $title ) . '</a>', $instance['title_link'], $title ) . $after_title;
		elseif ( $title && ! $instance['title_link'] )
			echo $before_title . esc_attr( $title ) . $after_title;

		/**
		 * Arguments for WP_Query
		 * @var array
		 */
		$args = array(
			'posts_per_page'	=> $number,
			'order'				=> $order,
			'orderby'			=> $orderby,
			'category__in'		=> $cats,
			'tag__in'			=> $tags,
			'post_type'			=> $types,
			);

		if ( 'meta_value' === $orderby ) {
			$args['meta_key'] = $meta_key;
		}

		if ( ! empty( $sticky_query ) ) {
			$args[ key( $sticky_query ) ] = reset( $sticky_query );
		}

		$args = apply_filters( 'italystrap_wp_query_args', $args, $instance, $this->id_base );

		$widget_post_query = new WP_Query( $args );

		if ( 'custom' === $instance['template'] ) {

			$custom_template_path = apply_filters( 'italystrap_custom_template_path',  '/templates/' . $instance['template_custom'] . '.php', $instance, $this->id_base );

			if ( locate_template( $custom_template_path ) ) {

				include get_stylesheet_directory() . $custom_template_path;

			} else {

				include 'templates/standard.php';

			}
		} elseif ( 'standard' === $instance['template'] ) {

			// include( ITALYSTRAP_PLUGIN_PATH . 'templates/standard.php' );
			include( ITALYSTRAP_PLUGIN_PATH . 'templates/legacy.php' );
			// include get_template( '/templates/content-post.php' );

		} else {

			include 'templates/legacy.php';

		}

		// Reset the global $the_post as this query will have stomped on it.
		wp_reset_postdata();

		echo $after_widget;

		if ( $cache ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
		}
		wp_cache_set( 'italystrap_posts_widget', $cache, 'widget' );
	}

	/**
	 * Update method
	 * @param  array $new_instance Array of value.
	 * @param  array $old_instance Array with old value.
	 * @return array               Return the array updated
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['class'] = strip_tags( $new_instance['class'] );
		$instance['title_link'] = strip_tags( $new_instance['title_link'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['types'] = (isset( $new_instance['types'] )) ? implode( ',', (array) $new_instance['types'] ) : '';
		$instance['cats'] = (isset( $new_instance['cats'] )) ? implode( ',', (array) $new_instance['cats'] ) : '';
		$instance['tags'] = (isset( $new_instance['tags'] )) ? implode( ',', (array) $new_instance['tags'] ) : '';
		$instance['atcat'] = isset( $new_instance['atcat'] );
		$instance['attag'] = isset( $new_instance['attag'] );
		$instance['show_excerpt'] = isset( $new_instance['show_excerpt'] );
		$instance['show_content'] = isset( $new_instance['show_content'] );
		$instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] );
		$instance['show_date'] = isset( $new_instance['show_date'] );
		$instance['date_format'] = strip_tags( $new_instance['date_format'] );
		$instance['show_title'] = isset( $new_instance['show_title'] );
		$instance['show_author'] = isset( $new_instance['show_author'] );
		$instance['show_comments'] = isset( $new_instance['show_comments'] );
		$instance['thumb_size'] = strip_tags( $new_instance['thumb_size'] );
		$instance['show_readmore'] = isset( $new_instance['show_readmore'] );
		$instance['excerpt_length'] = strip_tags( $new_instance['excerpt_length'] );
		$instance['excerpt_readmore'] = strip_tags( $new_instance['excerpt_readmore'] );
		$instance['sticky'] = $new_instance['sticky'];
		$instance['order'] = $new_instance['order'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['meta_key'] = $new_instance['meta_key'];
		$instance['show_cats'] = isset( $new_instance['show_cats'] );
		$instance['show_tags'] = isset( $new_instance['show_tags'] );
		$instance['custom_fields'] = strip_tags( $new_instance['custom_fields'] );
		$instance['template'] = strip_tags( $new_instance['template'] );
		$instance['template_custom'] = strip_tags( $new_instance['template_custom'] );
		$instance['thumb_url'] = sanitize_text_field( $new_instance['thumb_url'] );

		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['before_posts'] = $new_instance['before_posts'];
			$instance['after_posts'] = $new_instance['after_posts'];
		} else {
			$instance['before_posts'] = wp_filter_post_kses( $new_instance['before_posts'] );
			$instance['after_posts'] = wp_filter_post_kses( $new_instance['after_posts'] );
		}

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['italystrap_posts_widget'] ) )
			delete_option( 'italystrap_posts_widget' );

		return $instance;

	}

	/**
	 * Widget from for admin area
	 * @param  array $instance Array of value
	 */
	function form( $instance ) {

		/**
		 * Set default arguments
		 * @var array
		 */
		$instance = wp_parse_args( (array) $instance, array(
			'title'				=> __( 'ItalyStrap Widget Posts', 'italystrap' ),
			'class'				=> '',
			'title_link'		=> '',
			'number'			=> '5',
			'types'				=> 'post',
			'cats'				=> '',
			'tags'				=> '',
			'atcat'				=> false,
			'thumb_size'		=> 'thumbnail',
			'attag'				=> false,
			'excerpt_length'	=> 10,
			'excerpt_readmore'	=> __( 'Read more &rarr;', 'italystrap' ),
			'order'				=> 'DESC',
			'orderby'			=> 'date',
			'meta_key'			=> '',
			'sticky'			=> 'show',
			'show_cats'			=> false,
			'show_tags'			=> false,
			'show_title'		=> true,
			'show_date'			=> true,
			'date_format'		=> get_option( 'date_format' ) . ' ' . get_option( 'time_format' ),
			'show_author'		=> true,
			'show_comments'		=> false,
			'show_excerpt'		=> true,
			'show_content'		=> false,
			'show_readmore'		=> true,
			'show_thumbnail'	=> true,
			'thumb_url'			=> '',
			'custom_fields'		=> '',
			// Set template to 'legacy' if field from UPW < 2.0 is set.
			'template'			=> empty( $instance['morebutton_text'] ) ? 'standard' : 'legacy',
			'template_custom'	=> '',
			'before_posts'		=> '',
			'after_posts'		=> '',
			) );

			// Or use the instance
			$title  = strip_tags( $instance['title'] );
			$class  = strip_tags( $instance['class'] );
			$title_link  = strip_tags( $instance['title_link'] );
			$number = strip_tags( $instance['number'] );
			$types  = $instance['types'];
			$cats = $instance['cats'];
			$tags = $instance['tags'];
			$atcat = $instance['atcat'];
			$thumb_size = $instance['thumb_size'];
			$attag = $instance['attag'];
			$excerpt_length = strip_tags( $instance['excerpt_length'] );
			$excerpt_readmore = strip_tags( $instance['excerpt_readmore'] );
			$order = $instance['order'];
			$orderby = $instance['orderby'];
			$meta_key = $instance['meta_key'];
			$sticky = $instance['sticky'];
			$show_cats = $instance['show_cats'];
			$show_tags = $instance['show_tags'];
			$show_title = $instance['show_title'];
			$show_date = $instance['show_date'];
			$date_format = $instance['date_format'];
			$show_author = $instance['show_author'];
			$show_comments = $instance['show_comments'];
			$show_excerpt = $instance['show_excerpt'];
			$show_content = $instance['show_content'];
			$show_readmore = $instance['show_readmore'];
			$show_thumbnail = $instance['show_thumbnail'];
			$thumb_url = $instance['thumb_url'];
			$custom_fields = strip_tags( $instance['custom_fields'] );
			$template = $instance['template'];
			$template_custom = strip_tags( $instance['template_custom'] );
			$before_posts = format_to_edit( $instance['before_posts'] );
			$after_posts = format_to_edit( $instance['after_posts'] );

		// Let's turn $types, $cats, and $tags into an array if they are set.
		if ( ! empty( $types )) $types = explode( ',', $types );
		if ( ! empty( $cats )) $cats = explode( ',', $cats );
		if ( ! empty( $tags )) $tags = explode( ',', $tags );

		// Count number of post types for select box sizing.
		$cpt_types = get_post_types( array( 'public' => true ), 'names' );
		if ( $cpt_types ) {
			foreach ( $cpt_types as $cpt ) {
				$cpt_ar[] = $cpt;
			}
			$n = count( $cpt_ar );
			if ( $n > 6 ) { $n = 6; }
		} else {
			$n = 3;
		}

		// Count number of categories for select box sizing.
		$cat_list = get_categories( 'hide_empty=0' );
		if ( $cat_list ) {
			foreach ( $cat_list as $cat ) {
				$cat_ar[] = $cat;
			}
			$c = count( $cat_ar );
			if ( $c > 6 ) { $c = 6; }
		} else {
			$c = 3;
		}

		// Count number of tags for select box sizing.
		$tag_list = get_tags( 'hide_empty=0' );
		if ( $tag_list ) {
			foreach ( $tag_list as $tag ) {
				$tag_ar[] = $tag;
			}
			$t = count( $tag_ar );
			if ( $t > 6 ) { $t = 6; }
		} else {
			$t = 3;
		}

?>

<div class="upw-tabs">
<a class="upw-tab-item active" data-toggle="upw-tab-general"><?php esc_attr_e( 'General', 'italystrap' ); ?></a>
<a class="upw-tab-item" data-toggle="upw-tab-display"><?php esc_attr_e( 'Display', 'italystrap' ); ?></a>
<a class="upw-tab-item" data-toggle="upw-tab-filter"><?php esc_attr_e( 'Filter', 'italystrap' ); ?></a>
<a class="upw-tab-item" data-toggle="upw-tab-order"><?php esc_attr_e( 'Order', 'italystrap' ); ?></a>
</div>

<div class="upw-tab upw-tab-general">

<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_attr_e( 'Title', 'italystrap' ); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'title_link' ); ?>"><?php esc_attr_e( 'Title URL', 'italystrap' ); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title_link' ); ?>" name="<?php echo $this->get_field_name( 'title_link' ); ?>" type="text" value="<?php echo $title_link; ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'class' ); ?>"><?php esc_attr_e( 'CSS class', 'italystrap' ); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'class' ); ?>" name="<?php echo $this->get_field_name( 'class' ); ?>" type="text" value="<?php echo $class; ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'before_posts' ); ?>"><?php esc_attr_e( 'Before posts', 'italystrap' ); ?>:</label>
	<textarea class="widefat" id="<?php echo $this->get_field_id( 'before_posts' ); ?>" name="<?php echo $this->get_field_name( 'before_posts' ); ?>" rows="5"><?php echo $before_posts; ?></textarea>
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'after_posts' ); ?>"><?php esc_attr_e( 'After posts', 'italystrap' ); ?>:</label>
	<textarea class="widefat" id="<?php echo $this->get_field_id( 'after_posts' ); ?>" name="<?php echo $this->get_field_name( 'after_posts' ); ?>" rows="5"><?php echo $after_posts; ?></textarea>
</p>

</div>

<div class="upw-tab upw-hide upw-tab-display">

<p>
	<label for="<?php echo $this->get_field_id( 'template' ); ?>"><?php esc_attr_e( 'Template', 'italystrap' ); ?>:</label>
	<select name="<?php echo $this->get_field_name( 'template' ); ?>" id="<?php echo $this->get_field_id( 'template' ); ?>" class="widefat">
		<!-- <option value="legacy"<?php if ( 'legacy' === $template ) echo ' selected'; ?>><?php esc_attr_e( 'Legacy', 'italystrap' ); ?></option> -->
		<option value="standard"<?php if ( 'standard' === $template) echo ' selected'; ?>><?php esc_attr_e( 'Standard', 'italystrap' ); ?></option>
		<option value="custom"<?php if ( 'custom' === $template) echo ' selected'; ?>><?php esc_attr_e( 'Custom', 'italystrap' ); ?></option>
	</select>
</p>

<p<?php if ($template !== 'custom') echo ' style="display:none;"'; ?>>
<label for="<?php echo $this->get_field_id( 'template_custom' ); ?>"><?php esc_attr_e( 'Custom Template Name', 'italystrap' ); ?>:</label>
<input class="widefat" id="<?php echo $this->get_field_id( 'template_custom' ); ?>" name="<?php echo $this->get_field_name( 'template_custom' ); ?>" type="text" value="<?php echo $template_custom; ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_attr_e( 'Number of posts', 'italystrap' ); ?>:</label>
<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" value="<?php echo $number; ?>" min="-1" />
</p>

<p>
<input class="checkbox" id="<?php echo $this->get_field_id( 'show_title' ); ?>" name="<?php echo $this->get_field_name( 'show_title' ); ?>" type="checkbox" <?php checked( (bool) $show_title, true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php esc_attr_e( 'Show title', 'italystrap' ); ?></label>
</p>

<p>
<input class="checkbox" id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" type="checkbox" <?php checked( (bool) $show_date, true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php esc_attr_e( 'Show published date', 'italystrap' ); ?></label>
</p>

<p<?php if ( ! $show_date) echo ' style="display:none;"'; ?>>
<label for="<?php echo $this->get_field_id( 'date_format' ); ?>"><?php esc_attr_e( 'Date format', 'italystrap' ); ?>:</label>
<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'date_format' ); ?>" name="<?php echo $this->get_field_name( 'date_format' ); ?>" value="<?php echo $date_format; ?>" />
</p>

<p>
<input class="checkbox" id="<?php echo $this->get_field_id( 'show_author' ); ?>" name="<?php echo $this->get_field_name( 'show_author' ); ?>" type="checkbox" <?php checked( (bool) $show_author, true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_author' ); ?>"><?php esc_attr_e( 'Show post author', 'italystrap' ); ?></label>
</p>

<p>
<input class="checkbox" id="<?php echo $this->get_field_id( 'show_comments' ); ?>" name="<?php echo $this->get_field_name( 'show_comments' ); ?>" type="checkbox" <?php checked( (bool) $show_comments, true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_comments' ); ?>"><?php esc_attr_e( 'Show comments count', 'italystrap' ); ?></label>
</p>

<p>
<input class="checkbox" id="<?php echo $this->get_field_id( 'show_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" type="checkbox" <?php checked( (bool) $show_excerpt, true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_excerpt' ); ?>"><?php esc_attr_e( 'Show excerpt', 'italystrap' ); ?></label>
</p>

<p<?php if ( ! $show_excerpt) echo ' style="display:none;"'; ?>>
<label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php esc_attr_e( 'Excerpt length (in words)', 'italystrap' ); ?>:</label>
<input class="widefat" type="number" id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" value="<?php echo $excerpt_length; ?>" min="-1" />
</p>

<p>
<input class="checkbox" id="<?php echo $this->get_field_id( 'show_content' ); ?>" name="<?php echo $this->get_field_name( 'show_content' ); ?>" type="checkbox" <?php checked( (bool) $show_content, true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_content' ); ?>"><?php esc_attr_e( 'Show content', 'italystrap' ); ?></label>
</p>

<p<?php if ( ! $show_excerpt && ! $show_content) echo ' style="display:none;"'; ?>>
<label for="<?php echo $this->get_field_id( 'show_readmore' ); ?>">
<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'show_readmore' ); ?>" name="<?php echo $this->get_field_name( 'show_readmore' ); ?>"<?php checked( (bool) $show_readmore, true ); ?> />
<?php esc_attr_e( 'Show read more link', 'italystrap' ); ?>
</label>
</p>

<p<?php if ( ! $show_readmore  || ( ! $show_excerpt && ! $show_content)) echo ' style="display:none;"'; ?>>
<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'excerpt_readmore' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_readmore' ); ?>" value="<?php echo $excerpt_readmore; ?>" />
</p>

<?php if ( function_exists( 'the_post_thumbnail' ) && current_theme_supports( 'post-thumbnails' ) ) : ?>

<?php $sizes = get_intermediate_image_sizes(); ?>

<p>
	<input class="checkbox" id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" type="checkbox" <?php checked( (bool) $show_thumbnail, true ); ?> />

	<label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>"><?php esc_attr_e( 'Show thumbnail', 'italystrap' ); ?></label>
</p>

<p<?php if ( ! $show_thumbnail) echo ' style="display:none;"'; ?>>
	<select id="<?php echo $this->get_field_id( 'thumb_size' ); ?>" name="<?php echo $this->get_field_name( 'thumb_size' ); ?>" class="widefat">
		<?php foreach ( $sizes as $size ) : ?>
		<option value="<?php echo $size; ?>"<?php if ($thumb_size === $size) echo ' selected'; ?>><?php echo $size; ?></option>
		<?php endforeach; ?>
		<option value="full"<?php if ($thumb_size == $size) echo ' selected'; ?>><?php esc_attr_e( 'full' ); ?></option>
	</select>
<br>
<br>
	<label for="<?php esc_attr_e( $this->get_field_id( 'thumb_url' ) ); ?>" class="widefat">
		<?php echo esc_attr_e( 'Load fall-back thumbnail', 'italystrap' ); ?>
	</label>
	<input id="<?php esc_attr_e( $this->get_field_id( 'thumb_url' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'thumb_url' ) ); ?>" type="text" value="<?php  echo ${'thumb_url'}; ?>" placeholder="<?php echo esc_attr_e( 'Load fall-back thumbnail', 'italystrap' ); ?>" class="widefat">
	<input class="upload_image_button button button-primary widefat" type="button" value="Upload Image" />
</p>

<?php endif; ?>

<p>
<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'show_cats' ); ?>" name="<?php echo $this->get_field_name( 'show_cats' ); ?>" <?php checked( (bool) $show_cats, true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_cats' ); ?>"> <?php esc_attr_e( 'Show post categories', 'italystrap' ); ?></label>
</p>

<p>
<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'show_tags' ); ?>" name="<?php echo $this->get_field_name( 'show_tags' ); ?>" <?php checked( (bool) $show_tags, true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_tags' ); ?>"> <?php esc_attr_e( 'Show post tags', 'italystrap' ); ?></label>
</p>

<p>
<label for="<?php echo $this->get_field_id( 'custom_fields' ); ?>"><?php esc_attr_e( 'Show custom fields (comma separated)', 'italystrap' ); ?>:</label>
<input class="widefat" id="<?php echo $this->get_field_id( 'custom_fields' ); ?>" name="<?php echo $this->get_field_name( 'custom_fields' ); ?>" type="text" value="<?php echo $custom_fields; ?>" />
</p>

</div>

<div class="upw-tab upw-hide upw-tab-filter">

<p>
	<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'atcat' ); ?>" name="<?php echo $this->get_field_name( 'atcat' ); ?>" <?php checked( (bool) $atcat, true ); ?> />
	<label for="<?php echo $this->get_field_id( 'atcat' ); ?>"> <?php esc_attr_e( 'Show posts only from current category', 'italystrap' );?></label>
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'cats' ); ?>"><?php esc_attr_e( 'Categories', 'italystrap' ); ?>:</label>
	<select name="<?php echo $this->get_field_name( 'cats' ); ?>[]" id="<?php echo $this->get_field_id( 'cats' ); ?>" class="widefat" style="height: auto;" size="<?php echo $c ?>" multiple>
		<option value="" <?php if (empty( $cats )) echo 'selected="selected"'; ?>><?php esc_attr_e( '&ndash; Show All &ndash;' ) ?></option>
		<?php
		$categories = get_categories( 'hide_empty=0' );
		foreach ( $categories as $category ) { ?>
		<option value="<?php echo $category->term_id; ?>" <?php if (is_array( $cats ) && in_array( $category->term_id, $cats )) echo 'selected="selected"'; ?>><?php echo $category->cat_name;?></option>
		<?php } ?>
	</select>
</p>

<?php if ( $tag_list ) : ?>
<p>
	<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'attag' ); ?>" name="<?php echo $this->get_field_name( 'attag' ); ?>" <?php checked( (bool) $attag, true ); ?> />
	<label for="<?php echo $this->get_field_id( 'attag' ); ?>"> <?php esc_attr_e( 'Show posts only from current tag', 'italystrap' );?></label>
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php esc_attr_e( 'Tags', 'italystrap' ); ?>:</label>
	<select name="<?php echo $this->get_field_name( 'tags' ); ?>[]" id="<?php echo $this->get_field_id( 'tags' ); ?>" class="widefat" style="height: auto;" size="<?php echo $t ?>" multiple>
		<option value="" <?php if (empty( $tags )) echo 'selected="selected"'; ?>><?php esc_attr_e( '&ndash; Show All &ndash;' ) ?></option>
		<?php
		foreach ( $tag_list as $tag ) { ?>
		<option value="<?php echo $tag->term_id; ?>" <?php if (is_array( $tags ) && in_array( $tag->term_id, $tags )) echo 'selected="selected"'; ?>><?php echo $tag->name;?></option>
		<?php } ?>
	</select>
</p>
<?php endif; ?>

<p>
<label for="<?php echo $this->get_field_id( 'types' ); ?>"><?php esc_attr_e( 'Post types', 'italystrap' ); ?>:</label>
<select name="<?php echo $this->get_field_name( 'types' ); ?>[]" id="<?php echo $this->get_field_id( 'types' ); ?>" class="widefat" style="height: auto;" size="<?php echo $n ?>" multiple>
	<option value="" <?php if (empty( $types )) echo 'selected="selected"'; ?>><?php esc_attr_e( '&ndash; Show All &ndash;' ) ?></option>
	<?php
	$args = array( 'public' => true );
	$post_types = get_post_types( $args, 'names' );
	foreach ( $post_types as $post_type ) { ?>
	<option value="<?php echo $post_type; ?>" <?php if ( is_array( $types ) && in_array( $post_type, $types ) ) { echo 'selected="selected"'; } ?>><?php echo $post_type;?></option>
	<?php } ?>
</select>
</p>

<p>
<label for="<?php echo $this->get_field_id( 'sticky' ); ?>"><?php esc_attr_e( 'Sticky posts', 'italystrap' ); ?>:</label>
<select name="<?php echo $this->get_field_name( 'sticky' ); ?>" id="<?php echo $this->get_field_id( 'sticky' ); ?>" class="widefat">
	<option value="show"<?php if ( $sticky === 'show') echo ' selected'; ?>><?php esc_attr_e( 'Show All Posts', 'italystrap' ); ?></option>
	<option value="hide"<?php if ( $sticky == 'hide') echo ' selected'; ?>><?php esc_attr_e( 'Hide Sticky Posts', 'italystrap' ); ?></option>
	<option value="only"<?php if ( $sticky == 'only') echo ' selected'; ?>><?php esc_attr_e( 'Show Only Sticky Posts', 'italystrap' ); ?></option>
</select>
</p>

</div>

<div class="upw-tab upw-hide upw-tab-order">

<p>
	<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php esc_attr_e( 'Order by', 'italystrap' ); ?>:</label>
	<select name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_id( 'orderby' ); ?>" class="widefat">
		<option value="date"<?php if ( $orderby == 'date') echo ' selected'; ?>><?php esc_attr_e( 'Published Date', 'italystrap' ); ?></option>
		<option value="title"<?php if ( $orderby == 'title') echo ' selected'; ?>><?php esc_attr_e( 'Title', 'italystrap' ); ?></option>
		<option value="comment_count"<?php if ( $orderby == 'comment_count') echo ' selected'; ?>><?php esc_attr_e( 'Comment Count', 'italystrap' ); ?></option>
		<option value="rand"<?php if ( $orderby == 'rand') echo ' selected'; ?>><?php esc_attr_e( 'Random' ); ?></option>
		<option value="meta_value"<?php if ( $orderby == 'meta_value') echo ' selected'; ?>><?php esc_attr_e( 'Custom Field', 'italystrap' ); ?></option>
		<option value="menu_order"<?php if ( $orderby == 'menu_order') echo ' selected'; ?>><?php esc_attr_e( 'Menu Order', 'italystrap' ); ?></option>
	</select>
</p>

<p<?php if ($orderby !== 'meta_value') echo ' style="display:none;"'; ?>>
<label for="<?php echo $this->get_field_id( 'meta_key' ); ?>"><?php esc_attr_e( 'Custom field', 'italystrap' ); ?>:</label>
<input class="widefat" id="<?php echo $this->get_field_id( 'meta_key' ); ?>" name="<?php echo $this->get_field_name( 'meta_key' ); ?>" type="text" value="<?php echo $meta_key; ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php esc_attr_e( 'Order', 'italystrap' ); ?>:</label>
<select name="<?php echo $this->get_field_name( 'order' ); ?>" id="<?php echo $this->get_field_id( 'order' ); ?>" class="widefat">
	<option value="DESC"<?php if ( $order == 'DESC') echo ' selected'; ?>><?php esc_attr_e( 'Descending', 'italystrap' ); ?></option>
	<option value="ASC"<?php if ( $order == 'ASC') echo ' selected'; ?>><?php esc_attr_e( 'Ascending', 'italystrap' ); ?></option>
</select>
</p>

</div>

<?php if ( $instance ) { ?>

<script>

jQuery(document).ready(function($){

var show_excerpt = $("#<?php echo $this->get_field_id( 'show_excerpt' ); ?>");
var show_content = $("#<?php echo $this->get_field_id( 'show_content' ); ?>");
var show_readmore = $("#<?php echo $this->get_field_id( 'show_readmore' ); ?>");
var show_readmore_wrap = $("#<?php echo $this->get_field_id( 'show_readmore' ); ?>").parents('p');
var show_thumbnail = $("#<?php echo $this->get_field_id( 'show_thumbnail' ); ?>");
var show_date = $("#<?php echo $this->get_field_id( 'show_date' ); ?>");
var date_format = $("#<?php echo $this->get_field_id( 'date_format' ); ?>").parents('p');
var excerpt_length = $("#<?php echo $this->get_field_id( 'excerpt_length' ); ?>").parents('p');
var excerpt_readmore_wrap = $("#<?php echo $this->get_field_id( 'excerpt_readmore' ); ?>").parents('p');
var thumb_size_wrap = $("#<?php echo $this->get_field_id( 'thumb_size' ); ?>").parents('p');
var order = $("#<?php echo $this->get_field_id( 'orderby' ); ?>");
var meta_key_wrap = $("#<?php echo $this->get_field_id( 'meta_key' ); ?>").parents('p');
var template = $("#<?php echo $this->get_field_id( 'template' ); ?>");
var template_custom = $("#<?php echo $this->get_field_id( 'template_custom' ); ?>").parents('p');

var toggleReadmore = function() {
	if (show_excerpt.is(':checked') || show_content.is(':checked')) {
		show_readmore_wrap.show('fast');
	} else {
		show_readmore_wrap.hide('fast');
	}
	toggleExcerptReadmore();
}

var toggleExcerptReadmore = function() {
	if ((show_excerpt.is(':checked') || show_content.is(':checked')) && show_readmore.is(':checked')) {
		excerpt_readmore_wrap.show('fast');
	} else {
		excerpt_readmore_wrap.hide('fast');
	}
}

        // Toggle read more option
        show_excerpt.click(function() {
        	toggleReadmore();
        });

        // Toggle read more option
        show_content.click(function() {
        	toggleReadmore();
        });

        // Toggle excerpt length on click
        show_excerpt.click(function(){
        	excerpt_length.toggle('fast');
        });

        // Toggle excerpt length on click
        show_readmore.click(function(){
        	toggleExcerptReadmore();
        });

        // Toggle date format on click
        show_date.click(function(){
        	date_format.toggle('fast');
        });

        // Toggle excerpt length on click
        show_thumbnail.click(function(){
        	thumb_size_wrap.toggle('fast');
        });

        // Show or hide custom field meta_key value on order change
        order.change(function(){
        	if ($(this).val() === 'meta_value') {
        		meta_key_wrap.show('fast');
        	} else {
        		meta_key_wrap.hide('fast');
        	}
        });

        // Show or hide custom template field
        template.change(function(){
        	if ($(this).val() === 'custom') {
        		template_custom.show('fast');
        	} else {
        		template_custom.hide('fast');
        	}
        });

    });

</script>

<?php

}

	}


	function flush_widget_cache() {

		wp_cache_delete( 'italystrap_posts_widget', 'widget' );

	}

} // end class
