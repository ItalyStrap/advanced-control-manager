<?php

/**
 * @group post
 */
class Tests_Post_Types extends WP_UnitTestCase {
	function test_register_post_type() {
		$this->assertNull( get_post_type_object( 'foo' ) );
		register_post_type( 'foo' );

		$pobj = get_post_type_object( 'foo' );
		$this->assertInstanceOf( 'stdClass', $pobj );
		$this->assertEquals( 'foo', $pobj->name );

		// Test some defaults
		$this->assertFalse( is_post_type_hierarchical( 'foo' ) );
		$this->assertEquals( array(), get_object_taxonomies( 'foo' ) );

		_unregister_post_type( 'foo' );
	}

	/**
	 * @ticket 31134
	 *
	 * @expectedIncorrectUsage register_post_type
	 */
	function test_register_post_type_with_too_long_name() {
		// post type too long
		$this->assertInstanceOf( 'WP_Error', register_post_type( 'abcdefghijklmnopqrstuvwxyz0123456789' ) );
	}

	/**
	 * @ticket 31134
	 *
	 * @expectedIncorrectUsage register_post_type
	 */
	function test_register_post_type_with_empty_name() {
		// post type too short
		$this->assertInstanceOf( 'WP_Error', register_post_type( '' ) );
	}

	function test_register_taxonomy_for_object_type() {
		global $wp_taxonomies;

		register_post_type( 'bar' );
		register_taxonomy_for_object_type( 'post_tag', 'bar' );
		$this->assertEquals( array( 'post_tag' ), get_object_taxonomies( 'bar' ) );
		register_taxonomy_for_object_type( 'category', 'bar' );
		$this->assertEquals( array( 'category', 'post_tag' ), get_object_taxonomies( 'bar' ) );

		$this->assertTrue( is_object_in_taxonomy( 'bar', 'post_tag' ) );
		$this->assertTrue( is_object_in_taxonomy( 'bar', 'post_tag' ) );

		// Clean up. Remove the 'bar' post type from these taxonomies.
		$GLOBALS['wp_taxonomies']['post_tag']->object_type = array( 'post' );
		$GLOBALS['wp_taxonomies']['category']->object_type = array( 'post' );

		$this->assertFalse( is_object_in_taxonomy( 'bar', 'post_tag' ) );
		$this->assertFalse( is_object_in_taxonomy( 'bar', 'post_tag' ) );

		_unregister_post_type( 'bar' );
	}

	function test_post_type_exists() {
		$this->assertFalse( post_type_exists( 'notaposttype' ) );
		$this->assertTrue( post_type_exists( 'post' ) );
	}

	function test_post_type_supports() {
		$this->assertTrue( post_type_supports( 'post', 'post-formats' ) );
		$this->assertFalse( post_type_supports( 'page', 'post-formats' ) );
		$this->assertFalse( post_type_supports( 'notaposttype', 'post-formats' ) );
		$this->assertFalse( post_type_supports( 'post', 'notafeature' ) );
		$this->assertFalse( post_type_supports( 'notaposttype', 'notafeature' ) );
	}

	/**
	 * @ticket 21586
	 */
	function test_post_type_with_no_support() {
		register_post_type( 'foo', array( 'supports' => array() ) );
		$this->assertTrue( post_type_supports( 'foo', 'editor' ) );
		$this->assertTrue( post_type_supports( 'foo', 'title' ) );
		_unregister_post_type( 'foo' );

		register_post_type( 'foo', array( 'supports' => false ) );
		$this->assertFalse( post_type_supports( 'foo', 'editor' ) );
		$this->assertFalse( post_type_supports( 'foo', 'title' ) );
		_unregister_post_type( 'foo' );
	}

	/**
	 * @ticket 23302
	 */
	function test_post_type_with_no_feed() {
		global $wp_rewrite;
		$old_permastruct = get_option( 'permalink_structure' );
		update_option( 'permalink_structure', '%postname%' );
		register_post_type( 'foo', array( 'rewrite' => array( 'feeds' => false ) ) );
		$this->assertFalse( $wp_rewrite->extra_permastructs['foo']['feed'] );
		update_option( 'permalink_structure', $old_permastruct );
		_unregister_post_type( 'foo' );
	}

	/**
	 * @ticket 33543
	 */
	function test_get_post_type_labels_should_fall_back_on_defaults_when_filtered_labels_do_not_contain_the_keys() {
		add_filter( 'post_type_labels_foo', array( $this, 'filter_post_type_labels' ) );
		register_post_type( 'foo' );

		$this->assertObjectHasAttribute( 'featured_image', get_post_type_object( 'foo' )->labels );
		$this->assertObjectHasAttribute( 'set_featured_image', get_post_type_object( 'foo' )->labels );

		_unregister_post_type( 'foo' );
		remove_filter( 'post_type_labels_foo', array( $this, 'filter_post_type_labels' ) );
	}

	public function filter_post_type_labels( $labels ) {
		unset( $labels->featured_image );
		unset( $labels->set_featured_image );
		return $labels;
	}


	/**
	 * @ticket 30013
	 */
	public function test_get_post_type_object_with_non_scalar_values() {
		$this->assertFalse( post_type_exists( 'foo' ) );

		register_post_type( 'foo' );

		$this->assertTrue( post_type_exists( 'foo' ) );

		$this->assertNotNull( get_post_type_object( 'foo' ) );
		$this->assertNull( get_post_type_object( array() ) );
		$this->assertNull( get_post_type_object( array( 'foo' ) ) );
		$this->assertNull( get_post_type_object( new stdClass ) );

		_unregister_post_type( 'foo' );

		$this->assertFalse( post_type_exists( 'foo' ) );
	}

	/**
	 * @ticket 33023
	 */
	public function test_get_post_type_object_casting() {
		register_post_type( 'foo' );

		$before = get_post_type_object( 'foo' )->labels;

		get_post_type_labels( get_post_type_object( 'foo' ) );

		$after = get_post_type_object( 'foo' )->labels;

		$this->assertEquals( $before, $after );

		_unregister_post_type( 'foo' );
	}
}
