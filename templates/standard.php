<?php
/**
 * Standard ultimate posts widget template
 *
 * @package ItalyStrap
 */

?>

<?php if ( isset( $this->args['before_posts'] ) ) : ?>
	<div class="post-widget-before">
		<?php echo wpautop( esc_attr( $this->args['before_posts'] ) ); // XSS ok.?>
	</div>
<?php endif; ?>

<section class="post-widget hfeed <?php echo esc_attr( $this->args['container_class'] ); ?>" itemscope itemtype="http://schema.org/CollectionPage">

	<?php

	if ( $this->query->have_posts() ) :

		while ( $this->query->have_posts() ) :

			$this->query->the_post();

			if ( isset( $this->posts_to_exclude[0] ) && in_array( get_the_ID(), $this->posts_to_exclude ) ) {
				continue;
			}

			/**
			 * Ad "active" css class to current post
			 *
			 * @var string
			 */
			$current_post = ( $this->post->ID === $current_post_id && is_single() ) ? ' active' : '';

			$post_class = ( ! empty( $this->args['post_class'] ) ) ? ' ' . $this->args['post_class'] : '' ;

			$classes = 'post-number-' . $this->query->current_post . $current_post . esc_attr( $post_class );

			?>

			<article id="widget-post-<?php the_ID(); ?>"  <?php post_class( $classes ); ?>>

				<?php
				if ( current_theme_supports( 'post-thumbnails' ) && $this->args['show_thumbnail'] && has_post_thumbnail() ) : ?>
					<figure class="entry-image">
						<a href="<?php the_permalink(); ?>" rel="bookmark">
							<?php the_post_thumbnail( $this->args['thumb_size'] ); ?>
						</a>
					</figure>
				<?php elseif ( $this->args['show_thumbnail'] && $this->args['thumb_url'] ) :?>
					<figure class="entry-image">
						<a href="<?php the_permalink(); ?>" rel="bookmark">
							<img src="<?php echo esc_html( $this->args['thumb_url'] ); ?>">
						</a>
					</figure>
				<?php endif; ?>

				<section class="entry-body">
					<header class="entry-header">
						<?php if ( get_the_title() && $this->args['show_title'] ) : ?>
						<<?php echo esc_attr( $this->args['entry_title'] ); ?> class="entry-title">
							<a itemprop="url" href="<?php the_permalink(); ?>" rel="bookmark">
								<span itemprop="name">
									<?php the_title(); ?>
								</span>
							</a>
						</<?php echo esc_attr( $this->args['entry_title'] ); ?>>
						<?php endif; ?>
					
						<?php if ( $this->args['show_date'] || $this->args['show_author'] || $this->args['show_comments_number'] ) : ?>
					
						<div class="entry-meta">
					
							<?php
							if ( $this->args['show_date'] ) : ?>
								<time class="published" datetime="<?php echo get_the_time( 'c' ); ?>" itemprop="datePublished"><?php echo get_the_time( $this->args['date_format'] ); ?></time>
							<?php
							endif;

							if ( $this->args['show_date'] && $this->args['show_author'] ) : ?>
							<span class="sep"><?php esc_attr_e( '|', 'ItalyStrap' ); ?></span>
							<?php
							endif; ?>
					
							<?php if ( $this->args['show_author'] ) : ?>
								<span class="author vcard" itemprop="author">
									<?php esc_attr_e( 'By', 'ItalyStrap' ); ?>
									<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" class="fn">
										<?php echo get_the_author(); ?>
									</a>
								</span>
							<?php endif; ?>
					
							<?php if ( $this->args['show_author'] && $this->args['show_comments_number'] ) : ?>
								<span class="sep"><?php esc_attr_e( '|', 'ItalyStrap' ); ?></span>
							<?php endif; ?>
					
							<?php if ( $this->args['show_comments_number'] ) : ?>
								<a class="comments" href="<?php comments_link(); ?>">
									<?php comments_number( __( 'No comments', 'ItalyStrap' ), __( 'One comment', 'ItalyStrap' ), __( '% comments', 'ItalyStrap' ) ); ?>
								</a>
							<?php endif; ?>
					
						</div>
					
						<?php endif; ?>
					
					</header>
					
					<?php if ( $this->args['show_excerpt'] ) : ?>
						<div class="entry-summary">
							<p itemprop="text">
								<?php

								echo get_the_excerpt();
								// echo esc_attr( wp_trim_words( get_the_content(), $this->args['excerpt_length'], '' ) );
								?>
								<?php if ( $this->args['show_readmore'] ) : ?>
								<a href="<?php the_permalink(); ?>" class="more-link"><?php echo esc_attr( $this->args['excerpt_readmore'] ); ?></a>
								<?php endif; ?>
							</p>
						</div>
					<?php elseif ( $this->args['show_content'] ) : ?>
						<div class="entry-content" itemprop="text">
							<?php the_content() ?>
						</div>
					<?php endif; ?>
					
					<footer class="entry-footer">
					
						<?php
						$categories = get_the_term_list( $this->post->ID, 'category', '', ', ' );
						if ( $this->args['show_cats'] && $categories ) :
							?>
						<div class="entry-categories">
							<strong class="entry-cats-label"><?php esc_attr_e( 'Posted in', 'ItalyStrap' ); ?>:</strong>
							<span class="entry-cats-list"><?php echo esc_attr( $categories ); ?></span>
						</div>
						<?php endif; ?>
					
						<?php
						$tags = get_the_term_list( $this->post->ID, 'post_tag', '', ', ' );
						// var_dump($tags);
						if ( $this->args['show_tags'] && $tags ) :
						?>
						<div class="entry-tags">
							<strong class="entry-tags-label"><?php esc_attr_e( 'Tagged', 'ItalyStrap' ); ?>:</strong>
							<span class="entry-tags-list" itemprop="keywords"><?php echo esc_attr( $tags ); ?></span>
						</div>
						<?php endif; ?>
					
						<?php

						// global $product;
						// var_dump($product);
						// var_dump($product->get_price_html() );
						// var_dump($product->get_weight() );
						// var_dump( $product->get_weight() . ' ' . esc_attr( get_option('woocommerce_weight_unit' ) ) );

						// $_product = wc_get_product( $this->post->ID );

						// var_dump($_product->get_regular_price());

						if ( $this->args['custom_fields'] ) :

							$custom_field_name = explode( ',', $this->args['custom_fields'] ); ?>

							<div class="entry-custom-fields">
								<?php
								foreach ( $custom_field_name as $name ) :

									$name = trim( $name );
									$custom_field_values = get_post_meta( $this->post->ID, $name, true );

									if ( $custom_field_values ) : ?>
										<div class="custom-field custom-field-<?php echo esc_attr( str_replace( '_', '-', ltrim( $name, '_' ) ) ); ?>">
											<?php
											if ( ! is_array( $custom_field_values ) ) {
												echo esc_attr( $custom_field_values );
											} else {
												$last_value = end( $custom_field_values );
												foreach ( $custom_field_values as $value ) {
													echo esc_attr( $value );
													if ( $value !== $last_value ) echo ', ';
												}
											}
											?>
										</div>
								<?php endif;
								endforeach; ?>

							</div>
						<?php endif; ?>
						<?php do_action( 'italystrap_widget_posts_content_footer', $this->post ); ?>
					</footer>
				</section>
			<?php do_action( 'italystrap_widget_posts_content_article', $this->post ); ?>
		</article>

		<?php endwhile; ?>

		<?php else : ?>

			<p class="post-widget-not-found">
				<?php esc_attr_e( 'No posts found.', 'ItalyStrap' ); ?>
			</p>

		<?php endif; ?>

</section>

<?php if ( isset( $this->args['after_posts'] ) ) : ?>
	<div class="post-widget-after">
		<?php echo wpautop( esc_attr( $this->args['after_posts'] ) ); // XSS ok. ?>
	</div>
<?php endif;
