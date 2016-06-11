<?php
/**
 * Root relative URLs
 *
 * WordPress likes to use absolute URLs on everything - let's clean that up.
 * Inspired by http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
 *
 * You can enable/disable this feature in config.php:
 * current_theme_supports('root-relative-urls');
 *
 * @author Scott Walkinshaw <scott.walkinshaw@gmail.com>
 *
 * @param string $input The input to make relative link.
 */
function roots_root_relative_url( $input ) {

	preg_match( '|https?://([^/]+)(/.*)|i', $input, $matches );

	if ( isset( $matches[1] ) && isset( $matches[2] ) && $matches[1] === $_SERVER['SERVER_NAME'] ) {
		return wp_make_link_relative( $input );
	} else {
		return $input;
	}
}

/**
 * [roots_enable_root_relative_urls description]
 *
 * @return bool Return true if is not admin
 */
function roots_enable_root_relative_urls() {
  return (bool) ! ( is_admin() || in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ) && $options['do_relative_link'];
}

if ( roots_enable_root_relative_urls() ) {
	$root_rel_filters = array(
		'bloginfo_url',
		'the_permalink',
		'wp_list_pages',
		'wp_list_categories',
		'roots_wp_nav_menu_item',
		'the_content_more_link',
		'the_tags',
		'get_pagenum_link',
		'get_comment_link',
		'month_link',
		'day_link',
		'year_link',
		'tag_link',
		'the_author_posts_link',
		'script_loader_src',
		'style_loader_src',
	  );

	foreach ( $root_rel_filters as $key => $value ) {
		add_filter( $value, 'roots_root_relative_url' );
	}
}
