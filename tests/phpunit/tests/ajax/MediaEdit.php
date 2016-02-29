<?php
/**
 * Admin ajax functions to be tested
 */
require_once( ABSPATH . 'wp-admin/includes/ajax-actions.php' );

/**
 * Testing ajax media editing
 *
 * @package    WordPress
 * @subpackage UnitTests
 * @since      3.5.0
 * @group      ajax
 */
class Tests_Ajax_MediaEdit extends WP_Ajax_UnitTestCase {

	/**
	 * Tear down the test fixture.
	 */
	public function tearDown() {
		// Cleanup
		$this->remove_added_uploads();
		parent::tearDown();
	}

	/**
	 * @ticket 22985
	 */
	public function testCropImageThumbnail() {
		include_once( ABSPATH . 'wp-admin/includes/image-edit.php' );

		$filename = DIR_TESTDATA . '/images/canola.jpg';
		$contents = file_get_contents($filename);

		$upload = wp_upload_bits(basename($filename), null, $contents);
		$id = $this->_make_attachment($upload);

		$_REQUEST['action'] = 'image-editor';
		$_REQUEST['context'] = 'edit-attachment';
		$_REQUEST['postid'] = $id;
		$_REQUEST['target'] = 'thumbnail';
		$_REQUEST['do'] = 'save';
		$_REQUEST['history'] = '[{"c":{"x":5,"y":8,"w":289,"h":322}}]';

		$media_meta = wp_get_attachment_metadata($id);
		$this->assertArrayHasKey('sizes', $media_meta, 'attachment should have size data');
		$this->assertArrayHasKey('medium', $media_meta['sizes'], 'attachment should have data for medium size');
		$ret = wp_save_image($id);

		$media_meta = wp_get_attachment_metadata($id);
		$this->assertArrayHasKey('sizes', $media_meta, 'cropped attachment should have size data');
		$this->assertArrayHasKey('medium', $media_meta['sizes'], 'cropped attachment should have data for medium size');
	}
}
