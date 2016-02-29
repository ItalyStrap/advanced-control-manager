<?php

/**
 * @group admin-bar
 * @group toolbar
 * @group admin
 */
class Tests_AdminBar extends WP_UnitTestCase {
	protected static $editor_id;
	protected static $admin_id;
	protected static $no_role_id;
	protected static $post_id;
	protected static $blog_id;

	protected static $user_ids = array();

	public static function setUpBeforeClass() {
		require_once( ABSPATH . WPINC . '/class-wp-admin-bar.php' );

		parent::setUpBeforeClass();
	}

	public static function wpSetUpBeforeClass( $factory ) {
		self::$user_ids[] = self::$editor_id = $factory->user->create( array( 'role' => 'editor' ) );
		self::$user_ids[] = self::$admin_id = $factory->user->create( array( 'role' => 'administrator' ) );
		self::$user_ids[] = self::$no_role_id = $factory->user->create( array( 'role' => '' ) );
	}

	public static function wpTearDownAfterClass() {
		foreach ( self::$user_ids as $id ) {
			self::delete_user( $id );
		}
	}

	/**
	 * @ticket 21117
	 */
	function test_content_post_type() {
		wp_set_current_user( self::$editor_id );

		register_post_type( 'content', array( 'show_in_admin_bar' => true ) );

		$admin_bar = new WP_Admin_Bar;

		wp_admin_bar_new_content_menu( $admin_bar );

		$nodes = $admin_bar->get_nodes();
		$this->assertFalse( $nodes['new-content']->parent );
		$this->assertEquals( 'new-content', $nodes['add-new-content']->parent );

		_unregister_post_type( 'content' );
	}

	/**
	 * @ticket 21117
	 */
	function test_merging_existing_meta_values() {
		wp_set_current_user( self::$editor_id );

		$admin_bar = new WP_Admin_Bar;

		$admin_bar->add_node( array(
			'id' => 'test-node',
			'meta' => array( 'class' => 'test-class' ),
		) );

		$node1 = $admin_bar->get_node( 'test-node' );
		$this->assertEquals( array( 'class' => 'test-class' ), $node1->meta );

		$admin_bar->add_node( array(
			'id' => 'test-node',
			'meta' => array( 'some-meta' => 'value' ),
		) );

		$node2 = $admin_bar->get_node( 'test-node' );
		$this->assertEquals( array( 'class' => 'test-class', 'some-meta' => 'value' ), $node2->meta );
	}

	/**
	 * @ticket 25162
	 */
	public function test_admin_bar_contains_correct_links_for_users_with_no_role() {
		if ( is_multisite() ) {
			$this->markTestSkipped( 'Test does not run in multisite' );
		}

		$this->assertFalse( user_can( self::$no_role_id, 'read' ) );

		wp_set_current_user( self::$no_role_id );

		$wp_admin_bar = $this->get_standard_admin_bar();

		$node_site_name    = $wp_admin_bar->get_node( 'site-name' );
		$node_my_account   = $wp_admin_bar->get_node( 'my-account' );
		$node_user_info    = $wp_admin_bar->get_node( 'user-info' );
		$node_edit_profile = $wp_admin_bar->get_node( 'edit-profile' );

		// Site menu points to the home page instead of the admin URL
		$this->assertEquals( home_url( '/' ), $node_site_name->href );

		// No profile links as the user doesn't have any permissions on the site
		$this->assertFalse( $node_my_account->href );
		$this->assertFalse( $node_user_info->href );
		$this->assertNull( $node_edit_profile );
	}

	/**
	 * @ticket 25162
	 */
	public function test_admin_bar_contains_correct_links_for_users_with_role() {
		if ( is_multisite() ) {
			$this->markTestSkipped( 'Test does not run in multisite' );
		}

		$this->assertTrue( user_can( self::$editor_id, 'read' ) );

		wp_set_current_user( self::$editor_id );

		$wp_admin_bar = $this->get_standard_admin_bar();

		$node_site_name    = $wp_admin_bar->get_node( 'site-name' );
		$node_my_account   = $wp_admin_bar->get_node( 'my-account' );
		$node_user_info    = $wp_admin_bar->get_node( 'user-info' );
		$node_edit_profile = $wp_admin_bar->get_node( 'edit-profile' );

		// Site menu points to the admin URL
		$this->assertEquals( admin_url( '/' ), $node_site_name->href );

		$profile_url = admin_url( 'profile.php' );

		// Profile URLs point to profile.php
		$this->assertEquals( $profile_url, $node_my_account->href );
		$this->assertEquals( $profile_url, $node_user_info->href );
		$this->assertEquals( $profile_url, $node_edit_profile->href );

	}

	/**
	 * @ticket 25162
	 * @group multisite
	 */
	public function test_admin_bar_contains_correct_links_for_users_with_no_role_on_blog() {
		if ( ! is_multisite() ) {
			$this->markTestSkipped( 'Test only runs in multisite' );
		}

		$blog_id = self::factory()->blog->create( array(
			'user_id' => self::$admin_id,
		) );

		$this->assertTrue( user_can( self::$admin_id, 'read' ) );
		$this->assertTrue( user_can( self::$editor_id, 'read' ) );

		$this->assertTrue( is_user_member_of_blog( self::$admin_id, $blog_id ) );
		$this->assertFalse( is_user_member_of_blog( self::$editor_id, $blog_id ) );

		wp_set_current_user( self::$editor_id );

		switch_to_blog( $blog_id );

		$wp_admin_bar = $this->get_standard_admin_bar();

		$node_site_name    = $wp_admin_bar->get_node( 'site-name' );
		$node_my_account   = $wp_admin_bar->get_node( 'my-account' );
		$node_user_info    = $wp_admin_bar->get_node( 'user-info' );
		$node_edit_profile = $wp_admin_bar->get_node( 'edit-profile' );

		// get primary blog
		$primary = get_active_blog_for_user( self::$editor_id );
		$this->assertInternalType( 'object', $primary );

		// No Site menu as the user isn't a member of this blog
		$this->assertNull( $node_site_name );

		$primary_profile_url = get_admin_url( $primary->blog_id, 'profile.php' );

		// Ensure the user's primary blog is not the same as the main site
		$this->assertNotEquals( $primary_profile_url, admin_url( 'profile.php' ) );

		// Profile URLs should go to the user's primary blog
		$this->assertEquals( $primary_profile_url, $node_my_account->href );
		$this->assertEquals( $primary_profile_url, $node_user_info->href );
		$this->assertEquals( $primary_profile_url, $node_edit_profile->href );

		restore_current_blog();
	}

	/**
	 * @ticket 25162
	 * @group multisite
	 */
	public function test_admin_bar_contains_correct_links_for_users_with_no_role_on_network() {
		if ( ! is_multisite() ) {
			$this->markTestSkipped( 'Test only runs in multisite' );
		}

		$this->assertTrue( user_can( self::$admin_id, 'read' ) );
		$this->assertFalse( user_can( self::$no_role_id, 'read' ) );

		$blog_id = self::factory()->blog->create( array(
			'user_id' => self::$admin_id,
		) );

		$this->assertTrue( is_user_member_of_blog( self::$admin_id, $blog_id ) );
		$this->assertFalse( is_user_member_of_blog( self::$no_role_id, $blog_id ) );
		$this->assertTrue( is_user_member_of_blog( self::$no_role_id, get_current_blog_id() ) );

		// Remove `$nobody` from the current blog, so they're not a member of any blog
		$removed = remove_user_from_blog( self::$no_role_id, get_current_blog_id() );

		$this->assertTrue( $removed );
		$this->assertFalse( is_user_member_of_blog( self::$no_role_id, get_current_blog_id() ) );

		wp_set_current_user( self::$no_role_id );

		switch_to_blog( $blog_id );

		$wp_admin_bar = $this->get_standard_admin_bar();

		$node_site_name    = $wp_admin_bar->get_node( 'site-name' );
		$node_my_account   = $wp_admin_bar->get_node( 'my-account' );
		$node_user_info    = $wp_admin_bar->get_node( 'user-info' );
		$node_edit_profile = $wp_admin_bar->get_node( 'edit-profile' );

		// get primary blog
		$primary = get_active_blog_for_user( self::$no_role_id );
		$this->assertNull( $primary );

		// No Site menu as the user isn't a member of this site
		$this->assertNull( $node_site_name );

		$user_profile_url = user_admin_url( 'profile.php' );

		$this->assertNotEquals( $user_profile_url, admin_url( 'profile.php' ) );

		// Profile URLs should go to the user's primary blog
		$this->assertEquals( $user_profile_url, $node_my_account->href );
		$this->assertEquals( $user_profile_url, $node_user_info->href );
		$this->assertEquals( $user_profile_url, $node_edit_profile->href );

		restore_current_blog();
	}

	protected function get_standard_admin_bar() {
		global $wp_admin_bar;

		_wp_admin_bar_init();

		$this->assertTrue( is_admin_bar_showing() );
		$this->assertInstanceOf( 'WP_Admin_Bar', $wp_admin_bar );

		do_action_ref_array( 'admin_bar_menu', array( &$wp_admin_bar ) );

		return $wp_admin_bar;
	}

}
