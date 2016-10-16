<?php
/**
 * The template for displaying thumbnail
 *
 * @package ItalyStrap
 * @since 1.0.0
 */

namespace ItalyStrap\Core;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

if ( empty( $this->args['show_thumbnail'] ) ) {
	return;
}

/**
 * Thumbnail size
 *
 * @var string
 */
$thumb_size = $this->args['thumb_size'];

if ( 0 === $this->query->current_post && $this->args['show_first_thumbnail_bigger'] ) {
	$thumb_size = $this->args['first_thumb_size'];
}

if ( current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail() ) : ?>
	<figure class="entry-image">
		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<?php the_post_thumbnail(
				$thumb_size,
				array(
					'class' => 'attachment-' . $thumb_size . ' size-' . $thumb_size . ' ' . $this->args['image_class'],
					'alt'   => trim( strip_tags( get_post_meta( get_post_thumbnail_id( $this->query->post->ID ), '_wp_attachment_image_alt', true ) ) ),
					'itemprop'	=> 'image',
				)
			); ?>
		</a>
	</figure>
<?php elseif ( $this->args['thumb_id'] ) :?>
	<figure class="entry-image">
		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<?php
			$attr = array(
				'itemprop'	=> 'image',
				'class'		=> $this->args['image_class'],
			);
			$the_post_thumbnail = wp_get_attachment_image( $this->args['thumb_id'] , $thumb_size, false, $attr );
			echo apply_filters( 'italystrap_widget_the_post_thumbnail', $the_post_thumbnail ); // XSS ok.
			?>
		</a>
	</figure>
<?php endif; ?>
