<?php
/**
 * The template used for displaying the title.
 *
 * This file is still in BETA version.
 *
 * @package ItalyStrap
 * @since 1.0.0
 * @since 4.0.0 Code refactoring.
 */

namespace ItalyStrap;

if ( ! $this->args['show_title'] ) {
	return;
}

$args = array(
	'before'	=> sprintf(
		'<%1$s class="entry-title">%2$s<span itemprop="name">',
		esc_attr( $this->args['entry_title'] ),
		empty( $this->args['add_permalink_wrapper'] ) ? '' : sprintf(
			'<a itemprop="url" href="%1$s" title="%2$s" rel="bookmark">',
			esc_url( get_permalink() ),
			the_title_attribute( 'echo=0' )
		)
	),
	'after'		=> sprintf(
		'</span>%2$s</%1$s>',
		esc_attr( $this->args['entry_title'] ),
		empty( $this->args['add_permalink_wrapper'] ) ? '' : '</a>'
	),
);
the_title( $args['before'], $args['after'] );
