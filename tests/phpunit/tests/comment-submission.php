<?php

/**
 * @group comment
 */
class Tests_Comment_Submission extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();
		require_once ABSPATH . WPINC . '/class-phpass.php';
	}

	public function test_submitting_comment_to_invalid_post_returns_error() {
		$error = 'comment_id_not_found';

		$this->assertSame( 0, did_action( $error ) );

		$data = array(
			'comment_post_ID' => 0,
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertSame( 1, did_action( $error ) );
		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

	public function test_submitting_comment_to_post_with_closed_comments_returns_error() {

		$error = 'comment_closed';

		$this->assertSame( 0, did_action( $error ) );

		$post = self::factory()->post->create_and_get( array(
			'comment_status' => 'closed',
		) );
		$data = array(
			'comment_post_ID' => $post->ID,
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertSame( 1, did_action( $error ) );
		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

	public function test_submitting_comment_to_trashed_post_returns_error() {

		$error = 'comment_on_trash';

		$this->assertSame( 0, did_action( $error ) );

		$post = self::factory()->post->create_and_get();
		wp_trash_post( $post );
		$data = array(
			'comment_post_ID' => $post->ID,
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertSame( 1, did_action( $error ) );
		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

	public function test_submitting_comment_to_draft_post_returns_error() {

		$error = 'comment_on_draft';

		$this->assertSame( 0, did_action( $error ) );

		$post = self::factory()->post->create_and_get( array(
			'post_status' => 'draft',
		) );
		$data = array(
			'comment_post_ID' => $post->ID,
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertSame( 1, did_action( $error ) );
		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

	public function test_submitting_comment_to_scheduled_post_returns_error() {

		// Same error as commenting on a draft
		$error = 'comment_on_draft';

		$this->assertSame( 0, did_action( $error ) );

		$post = self::factory()->post->create_and_get( array(
			'post_date' => date( 'Y-m-d H:i:s', strtotime( '+1 day' ) ),
		) );

		$this->assertSame( 'future', $post->post_status );

		$data = array(
			'comment_post_ID' => $post->ID,
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertSame( 1, did_action( $error ) );
		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

	public function test_submitting_comment_to_password_required_post_returns_error() {

		$error = 'comment_on_password_protected';

		$this->assertSame( 0, did_action( $error ) );

		$post = self::factory()->post->create_and_get( array(
			'post_password' => 'password',
		) );
		$data = array(
			'comment_post_ID' => $post->ID,
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertSame( 1, did_action( $error ) );
		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

	public function test_submitting_comment_to_password_protected_post_succeeds() {

		$password = 'password';
		$hasher   = new PasswordHash( 8, true );

		$_COOKIE['wp-postpass_' . COOKIEHASH] = $hasher->HashPassword( $password );

		$post = self::factory()->post->create_and_get( array(
			'post_password' => $password,
		) );
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment',
			'author'          => 'Comment Author',
			'email'           => 'comment@example.org',
		);
		$comment = wp_handle_comment_submission( $data );

		unset( $_COOKIE['wp-postpass_' . COOKIEHASH] );

		$this->assertNotWPError( $comment );
		$this->assertInstanceOf( 'WP_Comment', $comment );

	}

	public function test_submitting_valid_comment_as_logged_in_user_succeeds() {

		$user = self::factory()->user->create_and_get( array(
			'user_url' => 'http://user.example.org'
		) );

		wp_set_current_user( $user->ID );

		$post = self::factory()->post->create_and_get();
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment',
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertNotWPError( $comment );
		$this->assertInstanceOf( 'WP_Comment', $comment );

		$this->assertSame( 'Comment', $comment->comment_content);
		$this->assertSame( $user->display_name, $comment->comment_author );
		$this->assertSame( $user->user_email, $comment->comment_author_email );
		$this->assertSame( $user->user_url, $comment->comment_author_url );
		$this->assertSame( $user->ID, intval( $comment->user_id ) );

	}

	public function test_submitting_valid_comment_anonymously_succeeds() {

		$post = self::factory()->post->create_and_get();
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment',
			'author'          => 'Comment Author',
			'email'           => 'comment@example.org',
			'url'             => 'user.example.org'
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertNotWPError( $comment );
		$this->assertInstanceOf( 'WP_Comment', $comment );

		$this->assertSame( 'Comment', $comment->comment_content);
		$this->assertSame( 'Comment Author', $comment->comment_author );
		$this->assertSame( 'comment@example.org', $comment->comment_author_email );
		$this->assertSame( 'http://user.example.org', $comment->comment_author_url );
		$this->assertSame( '0', $comment->user_id );

	}

	/**
	 * wp_handle_comment_submission() expects un-slashed data.
	 *
	 * @group slashes
	 */
	public function test_submitting_comment_handles_slashes_correctly_handles_slashes() {

		$post = self::factory()->post->create_and_get();
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment with 1 slash: \\',
			'author'          => 'Comment Author with 1 slash: \\',
			'email'           => 'comment@example.org',
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertNotWPError( $comment );
		$this->assertInstanceOf( 'WP_Comment', $comment );

		$this->assertSame( 'Comment with 1 slash: \\', $comment->comment_content);
		$this->assertSame( 'Comment Author with 1 slash: \\', $comment->comment_author );
		$this->assertSame( 'comment@example.org', $comment->comment_author_email );

	}

	public function test_submitting_comment_anonymously_to_private_post_returns_error() {

		$error = 'not_logged_in';

		$post = self::factory()->post->create_and_get( array(
			'post_status' => 'private',
		) );
		$data = array(
			'comment_post_ID' => $post->ID,
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertFalse( is_user_logged_in() );
		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

	public function test_submitting_comment_to_own_private_post_succeeds() {

		$user = self::factory()->user->create_and_get();

		wp_set_current_user( $user->ID );

		$post = self::factory()->post->create_and_get( array(
			'post_status' => 'private',
			'post_author' => $user->ID,
		) );
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment',
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertTrue( current_user_can( 'read_post', $post->ID ) );
		$this->assertNotWPError( $comment );
		$this->assertInstanceOf( 'WP_Comment', $comment );

	}

	public function test_submitting_comment_to_accessible_private_post_succeeds() {

		$author = self::factory()->user->create_and_get( array(
			'role' => 'author',
		) );
		$user = self::factory()->user->create_and_get( array(
			'role' => 'editor',
		) );

		wp_set_current_user( $user->ID );

		$post = self::factory()->post->create_and_get( array(
			'post_status' => 'private',
			'post_author' => $author->ID,
		) );
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment',
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertTrue( current_user_can( 'read_post', $post->ID ) );
		$this->assertNotWPError( $comment );
		$this->assertInstanceOf( 'WP_Comment', $comment );

	}

	public function test_anonymous_user_cannot_comment_unfiltered_html() {

		$post = self::factory()->post->create_and_get();
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment <script>alert(document.cookie);</script>',
			'author'          => 'Comment Author',
			'email'           => 'comment@example.org',
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertNotWPError( $comment );
		$this->assertInstanceOf( 'WP_Comment', $comment );
		$this->assertNotContains( '<script', $comment->comment_content );

	}

	public function test_unprivileged_user_cannot_comment_unfiltered_html() {

		$user = self::factory()->user->create_and_get( array(
			'role' => 'author',
		) );
		wp_set_current_user( $user->ID );

		$this->assertFalse( current_user_can( 'unfiltered_html' ) );

		$post = self::factory()->post->create_and_get();
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment <script>alert(document.cookie);</script>',
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertNotWPError( $comment );
		$this->assertInstanceOf( 'WP_Comment', $comment );
		$this->assertNotContains( '<script', $comment->comment_content );

	}

	public function test_unprivileged_user_cannot_comment_unfiltered_html_even_with_valid_nonce() {

		$user = self::factory()->user->create_and_get( array(
			'role' => 'author',
		) );
		wp_set_current_user( $user->ID );

		$this->assertFalse( current_user_can( 'unfiltered_html' ) );

		$post   = self::factory()->post->create_and_get();
		$action = 'unfiltered-html-comment_' . $post->ID;
		$nonce  = wp_create_nonce( $action );

		$this->assertNotEmpty( wp_verify_nonce( $nonce, $action ) );

		$data = array(
			'comment_post_ID'             => $post->ID,
			'comment'                     => 'Comment <script>alert(document.cookie);</script>',
			'_wp_unfiltered_html_comment' => $nonce,
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertNotWPError( $comment );
		$this->assertInstanceOf( 'WP_Comment', $comment );
		$this->assertNotContains( '<script', $comment->comment_content );

	}

	public function test_privileged_user_can_comment_unfiltered_html_with_valid_nonce() {

		$this->assertFalse( defined( 'DISALLOW_UNFILTERED_HTML' ) );

		$user = self::factory()->user->create_and_get( array(
			'role' => 'editor',
		) );

		if ( is_multisite() ) {
			// In multisite, only Super Admins can post unfiltered HTML
			$this->assertFalse( user_can( $user->ID, 'unfiltered_html' ) );
			grant_super_admin( $user->ID );
		}

		wp_set_current_user( $user->ID );

		$this->assertTrue( current_user_can( 'unfiltered_html' ) );

		$post   = self::factory()->post->create_and_get();
		$action = 'unfiltered-html-comment_' . $post->ID;
		$nonce  = wp_create_nonce( $action );

		$this->assertNotEmpty( wp_verify_nonce( $nonce, $action ) );

		$data = array(
			'comment_post_ID'             => $post->ID,
			'comment'                     => 'Comment <script>alert(document.cookie);</script>',
			'_wp_unfiltered_html_comment' => $nonce,
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertNotWPError( $comment );
		$this->assertInstanceOf( 'WP_Comment', $comment );
		$this->assertContains( '<script', $comment->comment_content );

	}

	public function test_privileged_user_cannot_comment_unfiltered_html_without_valid_nonce() {

		$user = self::factory()->user->create_and_get( array(
			'role' => 'editor',
		) );

		if ( is_multisite() ) {
			// In multisite, only Super Admins can post unfiltered HTML
			$this->assertFalse( user_can( $user->ID, 'unfiltered_html' ) );
			grant_super_admin( $user->ID );
		}

		wp_set_current_user( $user->ID );

		$this->assertTrue( current_user_can( 'unfiltered_html' ) );

		$post   = self::factory()->post->create_and_get();
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment <script>alert(document.cookie);</script>',
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertNotWPError( $comment );
		$this->assertInstanceOf( 'WP_Comment', $comment );
		$this->assertNotContains( '<script', $comment->comment_content );

	}

	public function test_submitting_comment_as_anonymous_user_when_registration_required_returns_error() {

		$error = 'not_logged_in';

		$_comment_registration = get_option( 'comment_registration' );
		update_option( 'comment_registration', '1' );

		$post = self::factory()->post->create_and_get();
		$data = array(
			'comment_post_ID' => $post->ID,
		);
		$comment = wp_handle_comment_submission( $data );

		update_option( 'comment_registration', $_comment_registration );

		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

	public function test_submitting_comment_with_no_name_when_name_email_required_returns_error() {

		$error = 'require_name_email';

		$_require_name_email = get_option( 'require_name_email' );
		update_option( 'require_name_email', '1' );

		$post = self::factory()->post->create_and_get();
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment',
			'email'           => 'comment@example.org',
		);
		$comment = wp_handle_comment_submission( $data );

		update_option( 'require_name_email', $_require_name_email );

		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

	public function test_submitting_comment_with_no_email_when_name_email_required_returns_error() {

		$error = 'require_name_email';

		$_require_name_email = get_option( 'require_name_email' );
		update_option( 'require_name_email', '1' );

		$post = self::factory()->post->create_and_get();
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment',
			'author'          => 'Comment Author',
		);
		$comment = wp_handle_comment_submission( $data );

		update_option( 'require_name_email', $_require_name_email );

		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

	public function test_submitting_comment_with_invalid_email_when_name_email_required_returns_error() {

		$error = 'require_valid_email';

		$_require_name_email = get_option( 'require_name_email' );
		update_option( 'require_name_email', '1' );

		$post = self::factory()->post->create_and_get();
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => 'Comment',
			'author'          => 'Comment Author',
			'email'           => 'not_an_email',
		);
		$comment = wp_handle_comment_submission( $data );

		update_option( 'require_name_email', $_require_name_email );

		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

	public function test_submitting_comment_with_no_comment_content_returns_error() {

		$error = 'require_valid_comment';

		$post = self::factory()->post->create_and_get();
		$data = array(
			'comment_post_ID' => $post->ID,
			'comment'         => '',
			'author'          => 'Comment Author',
			'email'           => 'comment@example.org',
		);
		$comment = wp_handle_comment_submission( $data );

		$this->assertWPError( $comment );
		$this->assertSame( $error, $comment->get_error_code() );

	}

}
