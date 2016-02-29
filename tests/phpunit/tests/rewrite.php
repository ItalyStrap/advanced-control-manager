<?php

/**
 * A set of unit tests for functions in wp-includes/rewrite.php
 *
 * @group rewrite
 */
class Tests_Rewrite extends WP_UnitTestCase {
	private $home_url;

	function setUp() {
		parent::setUp();

		create_initial_taxonomies();

		$this->set_permalink_structure( '/%year%/%monthnum%/%day%/%postname%/' );

		$this->home_url = get_option( 'home' );
	}

	function tearDown() {
		global $wp_rewrite;
		$wp_rewrite->init();

		update_option( 'home', $this->home_url );
		parent::tearDown();
	}

	/**
	 * @ticket 16840
	 */
	public function test_add_rule() {
		global $wp_rewrite;

		$pattern  = 'path/to/rewrite/([^/]+)/?$';
		$redirect = 'index.php?test_var1=$matches[1]&test_var2=1';

		$wp_rewrite->add_rule( $pattern, $redirect );

		$wp_rewrite->flush_rules();

		$rewrite_rules = $wp_rewrite->rewrite_rules();

		$this->assertSame( $redirect, $rewrite_rules[ $pattern ] );
	}

	/**
	 * @ticket 16840
	 */
	public function test_add_rule_redirect_array() {
		global $wp_rewrite;

		$pattern  = 'path/to/rewrite/([^/]+)/?$';
		$redirect = 'index.php?test_var1=$matches[1]&test_var2=1';

		$wp_rewrite->add_rule( $pattern, array(
			'test_var1' => '$matches[1]',
			'test_var2' => '1'
		) );

		$wp_rewrite->flush_rules();

		$rewrite_rules = $wp_rewrite->rewrite_rules();

		$this->assertSame( $redirect, $rewrite_rules[ $pattern ] );
	}

	/**
	 * @ticket 16840
	 */
	public function test_add_rule_top() {
		global $wp_rewrite;

		$pattern  = 'path/to/rewrite/([^/]+)/?$';
		$redirect = 'index.php?test_var1=$matches[1]&test_var2=1';

		$wp_rewrite->add_rule( $pattern, $redirect, 'top' );

		$wp_rewrite->flush_rules();

		$extra_rules_top = $wp_rewrite->extra_rules_top;

		$this->assertContains( $redirect, $extra_rules_top[ $pattern ] );
	}

	function test_url_to_postid() {

		$id = self::factory()->post->create();
		$this->assertEquals( $id, url_to_postid( get_permalink( $id ) ) );

		$id = self::factory()->post->create( array( 'post_type' => 'page' ) );
		$this->assertEquals( $id, url_to_postid( get_permalink( $id ) ) );
	}

	function test_url_to_postid_set_url_scheme_https_to_http() {
		$post_id = self::factory()->post->create();
		$permalink = get_permalink( $post_id );
		$this->assertEquals( $post_id, url_to_postid( set_url_scheme( $permalink, 'https' ) ) );

		$post_id = self::factory()->post->create( array( 'post_type' => 'page' ) );
		$permalink = get_permalink( $post_id );
		$this->assertEquals( $post_id, url_to_postid( set_url_scheme( $permalink, 'https' ) ) );
	}

	function test_url_to_postid_set_url_scheme_http_to_https() {
		// Save server data for cleanup
		$is_ssl = is_ssl();
		$http_host = $_SERVER['HTTP_HOST'];

		$_SERVER['HTTPS'] = 'on';

		$post_id = self::factory()->post->create();
		$permalink = get_permalink( $post_id );
		$this->assertEquals( $post_id, url_to_postid( set_url_scheme( $permalink, 'http' ) ) );

		$post_id = self::factory()->post->create( array( 'post_type' => 'page' ) );
		$permalink = get_permalink( $post_id );
		$this->assertEquals( $post_id, url_to_postid( set_url_scheme( $permalink, 'http' ) ) );

		// Cleanup.
		$_SERVER['HTTPS'] = $is_ssl ? 'on' : 'off';
		$_SERVER['HTTP_HOST'] = $http_host;
	}

	function test_url_to_postid_custom_post_type() {
		delete_option( 'rewrite_rules' );

		$post_type = rand_str( 12 );
		register_post_type( $post_type, array( 'public' => true ) );

		$id = self::factory()->post->create( array( 'post_type' => $post_type ) );
		$this->assertEquals( $id, url_to_postid( get_permalink( $id ) ) );

		_unregister_post_type( $post_type );
	}

	function test_url_to_postid_hierarchical() {

		$parent_id = self::factory()->post->create( array( 'post_title' => 'Parent', 'post_type' => 'page' ) );
		$child_id = self::factory()->post->create( array( 'post_title' => 'Child', 'post_type' => 'page', 'post_parent' => $parent_id ) );

		$this->assertEquals( $parent_id, url_to_postid( get_permalink( $parent_id ) ) );
		$this->assertEquals( $child_id, url_to_postid( get_permalink( $child_id ) ) );
	}

	function test_url_to_postid_hierarchical_with_matching_leaves() {

		$parent_id = self::factory()->post->create( array(
			'post_name' => 'parent',
			'post_type' => 'page',
		) );
		$child_id_1 = self::factory()->post->create( array(
			'post_name'   => 'child1',
			'post_type'   => 'page',
			'post_parent' => $parent_id,
		) );
		$child_id_2 = self::factory()->post->create( array(
			'post_name'   => 'child2',
			'post_type'   => 'page',
			'post_parent' => $parent_id,
		) );
		$grandchild_id_1 = self::factory()->post->create( array(
			'post_name'   => 'grandchild',
			'post_type'   => 'page',
			'post_parent' => $child_id_1,
		) );
		$grandchild_id_2 = self::factory()->post->create( array(
			'post_name'   => 'grandchild',
			'post_type'   => 'page',
			'post_parent' => $child_id_2,
		) );

		$this->assertEquals( home_url( 'parent/child1/grandchild/' ), get_permalink( $grandchild_id_1 ) );
		$this->assertEquals( home_url( 'parent/child2/grandchild/' ), get_permalink( $grandchild_id_2 ) );
		$this->assertEquals( $grandchild_id_1, url_to_postid( get_permalink( $grandchild_id_1 ) ) );
		$this->assertEquals( $grandchild_id_2, url_to_postid( get_permalink( $grandchild_id_2 ) ) );
	}

	function test_url_to_postid_home_has_path() {

		update_option( 'home', home_url( '/example/' ) );

		$id = self::factory()->post->create( array( 'post_title' => 'Hi', 'post_type' => 'page', 'post_name' => 'examp' ) );
		$this->assertEquals( $id, url_to_postid( get_permalink( $id ) ) );
		$this->assertEquals( $id, url_to_postid( site_url('/example/examp' ) ) );
		$this->assertEquals( $id, url_to_postid( '/example/examp/' ) );
		$this->assertEquals( $id, url_to_postid( '/example/examp' ) );

		$this->assertEquals( 0, url_to_postid( site_url( '/example/ex' ) ) );
		$this->assertEquals( 0, url_to_postid( '/example/ex' ) );
		$this->assertEquals( 0, url_to_postid( '/example/ex/' ) );
		$this->assertEquals( 0, url_to_postid( '/example-page/example/' ) );
		$this->assertEquals( 0, url_to_postid( '/example-page/ex/' ) );
	}

	/**
	 * @ticket 30438
	 */
	function test_parse_request_home_path() {
		$home_url = home_url( '/path/' );
		update_option( 'home', $home_url );

		$this->go_to( $home_url );
		$this->assertEquals( array(), $GLOBALS['wp']->query_vars );

		$this->go_to( $home_url . 'page' );
		$this->assertEquals( array( 'page' => '', 'pagename' => 'page' ), $GLOBALS['wp']->query_vars );
	}

	/**
	 * @ticket 30438
	 */
	function test_parse_request_home_path_with_regex_character() {
		$home_url = home_url( '/ma.ch/' );
		$not_a_home_url = home_url( '/match/' );
		update_option( 'home', $home_url );

		$this->go_to( $home_url );
		$this->assertEquals( array(), $GLOBALS['wp']->query_vars );

		$this->go_to( $home_url . 'page' );
		$this->assertEquals( array( 'page' => '', 'pagename' => 'page' ), $GLOBALS['wp']->query_vars );

		$this->go_to( $not_a_home_url . 'page' );
		$this->assertNotEquals( array( 'page' => '', 'pagename' => 'page' ), $GLOBALS['wp']->query_vars );
		$this->assertEquals( array( 'page' => '', 'pagename' => 'match/page' ), $GLOBALS['wp']->query_vars );
	}

	/**
	 * @ticket 30018
	 */
	function test_parse_request_home_path_non_public_type() {
		register_post_type( 'foo', array( 'public' => false ) );

		$url = add_query_arg( 'foo', '1', home_url() );

		$this->go_to( $url );

		_unregister_post_type( 'foo' );

		$this->assertEquals( array(), $GLOBALS['wp']->query_vars );
	}

	function test_url_to_postid_dupe_path() {
		update_option( 'home', home_url('/example/') );

		$id = self::factory()->post->create( array( 'post_title' => 'Hi', 'post_type' => 'page', 'post_name' => 'example' ) );

		$this->assertEquals( $id, url_to_postid( get_permalink( $id ) ) );
		$this->assertEquals( $id, url_to_postid( site_url( '/example/example/' ) ) );
		$this->assertEquals( $id, url_to_postid( '/example/example/' ) );
		$this->assertEquals( $id, url_to_postid( '/example/example' ) );
	}

	/**
	 * Reveals bug introduced in WP 3.0
	 */
	function test_url_to_postid_home_url_collision() {
		update_option( 'home', home_url( '/example' ) );

		self::factory()->post->create( array( 'post_title' => 'Collision', 'post_type' => 'page', 'post_name' => 'collision' ) );

		// This url should NOT return a post ID
		$badurl = site_url( '/example-collision' );
		$this->assertEquals( 0, url_to_postid( $badurl ) );
	}

	/**
	 * Reveals bug introduced in WP 3.0
	 *
	 * Run tests using multisite `phpunit -c multisite`
	 */
	function test_url_to_postid_ms_home_url_collision() {

		if ( ! is_multisite() ) {
			$this->markTestSkipped( 'test_url_to_postid_ms_home_url_collision requires multisite' );
			return false;
		}

		$blog_id = self::factory()->blog->create( array( 'path' => '/example' ) );
		switch_to_blog( $blog_id );

		self::factory()->post->create( array( 'post_title' => 'Collision ', 'post_type' => 'page' ) );

		// This url should NOT return a post ID
		$badurl = network_home_url( '/example-collision' );
		$this->assertEquals( 0, url_to_postid( $badurl ) );

		restore_current_blog();
	}

	/**
	 * @ticket 21970
	 */
	function test_url_to_postid_with_post_slug_that_clashes_with_a_trashed_page() {
		$this->set_permalink_structure( '/%postname%/' );

		$page_id = self::factory()->post->create( array( 'post_type' => 'page', 'post_status' => 'trash' ) );
		$post_id = self::factory()->post->create( array( 'post_title' => get_post( $page_id )->post_title ) );

		$this->assertEquals( $post_id, url_to_postid( get_permalink( $post_id ) ) );

		$this->set_permalink_structure();
	}

	/**
	 * @ticket 21970
	 */
	function test_parse_request_with_post_slug_that_clashes_with_a_trashed_page() {
		$this->set_permalink_structure( '/%postname%/' );

		$page_id = self::factory()->post->create( array( 'post_type' => 'page', 'post_status' => 'trash' ) );
		$post_id = self::factory()->post->create( array( 'post_title' => get_post( $page_id )->post_title ) );

		$this->go_to( get_permalink( $post_id ) );

		$this->assertTrue( is_single() );
		$this->assertFalse( is_404() );

		$this->set_permalink_structure();
	}

}
