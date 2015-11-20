<?php
/**
 * Standard ultimate posts widget template
 *
 * @package ItalyStrap
 */

?>

<?php if ( $instance['before_posts'] ) : ?>
	<div class="post-widget-before">
		<?php echo wpautop( esc_attr( $instance['before_posts'] ) ); ?>
	</div>
<?php endif; ?>

<section class="post-widget hfeed" itemscope itemtype="http://schema.org/CollectionPage">

	<?php

	if ( $widget_post_query->have_posts() ) :

		while ( $widget_post_query->have_posts() ) :

			$widget_post_query->the_post();

			$current_post = ( $post->ID === $current_post_id && is_single() ) ? 'active' : ''; ?>

			<article <?php post_class( $current_post ); ?>>

				<?php
				if ( current_theme_supports( 'post-thumbnails' ) && $instance['show_thumbnail'] && has_post_thumbnail() ) : ?>
					<figure class="entry-image">
						<a href="<?php the_permalink(); ?>" rel="bookmark">
							<?php the_post_thumbnail( $instance['thumb_size'] ); ?>
						</a>
					</figure>
				<?php elseif ( $instance['show_thumbnail'] && $instance['thumb_url'] ) :?>
					<figure class="entry-image">
						<a href="<?php the_permalink(); ?>" rel="bookmark">
							<img src="<?php echo esc_html( $instance['thumb_url'] ); ?>">
						</a>
					</figure>
				<?php endif; ?>

				<section class="entry-body">
					<header class="entry-header">
						<?php if ( get_the_title() && $instance['show_title'] ) : ?>
						<h4 class="entry-title">
							<a itemprop="url" href="<?php the_permalink(); ?>" rel="bookmark">
								<span itemprop="name">
									<?php the_title(); ?>
								</span>
							</a>
						</h4>
						<?php endif; ?>
					
						<?php if ( $instance['show_date'] || $instance['show_author'] || $instance['show_comments'] ) : ?>
					
						<div class="entry-meta">
					
							<?php
							if ( $instance['show_date'] ) : ?>
								<time class="published" datetime="<?php echo get_the_time( 'c' ); ?>" itemprop="datePublished"><?php echo get_the_time( $instance['date_format'] ); ?></time>
							<?php
							endif;

							if ( $instance['show_date'] && $instance['show_author'] ) : ?>
							<span class="sep"><?php esc_attr_e( '|', 'ItalyStrap' ); ?></span>
							<?php
							endif; ?>
					
							<?php if ( $instance['show_author'] ) : ?>
								<span class="author vcard" itemprop="author">
									<?php esc_attr_e( 'By', 'ItalyStrap' ); ?>
									<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" class="fn">
										<?php echo get_the_author(); ?>
									</a>
								</span>
							<?php endif; ?>
					
							<?php if ( $instance['show_author'] && $instance['show_comments'] ) : ?>
								<span class="sep"><?php esc_attr_e( '|', 'ItalyStrap' ); ?></span>
							<?php endif; ?>
					
							<?php if ( $instance['show_comments'] ) : ?>
								<a class="comments" href="<?php comments_link(); ?>">
									<?php comments_number( __( 'No comments', 'ItalyStrap' ), __( 'One comment', 'ItalyStrap' ), __( '% comments', 'ItalyStrap' ) ); ?>
								</a>
							<?php endif; ?>
					
						</div>
					
						<?php endif; ?>
					
					</header>
					
					<?php if ( $instance['show_excerpt'] ) : ?>
						<div class="entry-summary">
							<p itemprop="text">
								<?php echo get_the_excerpt(); ?>
								<?php if ( $instance['show_readmore'] ) : ?>
								<a href="<?php the_permalink(); ?>" class="more-link"><?php echo esc_attr( $instance['excerpt_readmore'] ); ?></a>
								<?php endif; ?>
							</p>
						</div>
					<?php elseif ( $instance['show_content'] ) : ?>
						<div class="entry-content" itemprop="text">
							<?php the_content() ?>
						</div>
					<?php endif; ?>
					
					<footer class="entry-footer">
					
						<?php
						$categories = get_the_term_list( $post->ID, 'category', '', ', ' );
						if ( $instance['show_cats'] && $categories ) :
							?>
						<div class="entry-categories">
							<strong class="entry-cats-label"><?php esc_attr_e( 'Posted in', 'ItalyStrap' ); ?>:</strong>
							<span class="entry-cats-list"><?php echo esc_attr( $categories ); ?></span>
						</div>
					<?php endif; ?>
					
					<?php
					$tags = get_the_term_list( $post->ID, 'post_tag', '', ', ' );
					if ( $instance['show_tags'] && $tags ) :
						?>
					<div class="entry-tags">
						<strong class="entry-tags-label"><?php esc_attr_e( 'Tagged', 'ItalyStrap' ); ?>:</strong>
						<span class="entry-tags-list" itemprop="keywords"><?php echo esc_attr( $tags ); ?></span>
					</div>
					<?php endif; ?>
					
						<?php

						if ( $custom_fields ) :

							$custom_field_name = explode( ',', $custom_fields ); ?>

							<div class="entry-custom-fields">
								<?php
								foreach ( $custom_field_name as $name ) :

									$name = trim( $name );
									$custom_field_values = get_post_meta( $post->ID, $name, true );

									if ( $custom_field_values ) : ?>
										<div class="custom-field custom-field-<?php echo esc_attr( $name ); ?>">
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
					</footer>
				</section>

		</article>

		<?php endwhile; ?>

		<?php else : ?>

			<p class="post-widget-not-found">
				<?php esc_attr_e( 'No posts found.', 'ItalyStrap' ); ?>
			</p>

		<?php endif; ?>

</section>

<?php if ( $instance['after_posts'] ) : ?>
	<div class="post-widget-after">
		<?php echo wpautop( esc_attr( $instance['after_posts'] ) ); ?>
	</div>
<?php endif;
