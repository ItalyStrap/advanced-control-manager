<?php 
/**
 * Roadmap for new code
 * @since 1.8.3
 * In this file there is some code that I will add with future releases (maybe :-))
 *
 *
 */
// http://wpsnipp.com/index.php/functions-php/require-featured-image-can-publish-post/
// https://plus.google.com/u/0/112897312156913513724/posts/Ub5r3T8qgqd
// add_action('save_post', 'wpds_check_thumbnail');
// add_action('admin_notices', 'wpds_thumbnail_error');
// function wpds_check_thumbnail($post_id) {
//     // change to any custom post type
//     if(get_post_type($post_id) != 'post')
//         return;
//     if ( !has_post_thumbnail( $post_id ) ) {
//         // set a transient to show the users an admin message
//         set_transient( "has_post_thumbnail", "no" );
//         // unhook this function so it doesn't loop infinitely
//         remove_action('save_post', 'wpds_check_thumbnail');
//         // update the post set it to draft
//         wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
//         add_action('save_post', 'wpds_check_thumbnail');
//     } else {
//         delete_transient( "has_post_thumbnail" );
//     }
// }
// function wpds_thumbnail_error()
// {
//     // check if the transient is set, and display the error message
//     if ( get_transient( "has_post_thumbnail" ) == "no" ) {
//         echo "<div id='message' class='error'><p><strong>You must select Featured Image. Your Post is saved but it can not be published.</strong></p></div>";
//         delete_transient( "has_post_thumbnail" );
//     }



//http://www.emoticode.net/php/add-async-and-defer-to-script-on-wordpress.html
// function defer_parsing_of_js ( $url ) {
	// if ( FALSE === strpos( $url, '.js' ) ) return $url;
	// if ( strpos( $url, 'jquery.js' ) ) return $url;
	// return "'$url' async defer";
// }
	// add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
// }