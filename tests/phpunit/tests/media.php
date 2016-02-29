<?php

/**
 * @group media
 * @group shortcode
 */
class Tests_Media extends WP_UnitTestCase {
	protected static $large_id;

	public static function wpSetUpBeforeClass( $factory ) {
		$filename = DIR_TESTDATA . '/images/test-image-large.png';
		self::$large_id = $factory->attachment->create_upload_object( $filename );
	}

	public static function wpTearDownAfterClass() {
		wp_delete_attachment( self::$large_id );
	}

	function setUp() {
		parent::setUp();
		$this->caption = 'A simple caption.';
		$this->html_content = <<<CAP
A <strong class='classy'>bolded</strong> <em>caption</em> with a <a href="#">link</a>.
CAP;
		$this->img_content = <<<CAP
<img src="pic.jpg" id='anId' alt="pic"/>
CAP;
		$this->img_name = 'image.jpg';
		$this->img_url = 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . $this->img_name;
		$this->img_html = '<img src="' . $this->img_url . '"/>';
		$this->img_meta = array( 'width' => 100, 'height' => 100, 'sizes' => '' );
	}

	function test_img_caption_shortcode_added() {
		global $shortcode_tags;
		$this->assertEquals( 'img_caption_shortcode', $shortcode_tags['caption'] );
		$this->assertEquals( 'img_caption_shortcode', $shortcode_tags['wp_caption'] );
	}

	function test_img_caption_shortcode_with_empty_params() {
		$result = img_caption_shortcode( array() );
		$this->assertNull( $result );
	}

	function test_img_caption_shortcode_with_bad_attr() {
		$result = img_caption_shortcode( array(), 'content' );
		$this->assertEquals( 'content', 'content' );
	}

	function test_img_caption_shortcode_with_old_format() {
		$result = img_caption_shortcode(
			array( 'width' => 20, 'caption' => $this->caption )
		);
		$this->assertEquals( 2, preg_match_all( '/wp-caption/', $result, $_r ) );
		$this->assertEquals( 1, preg_match_all( '/alignnone/', $result, $_r ) );
		$this->assertEquals( 1, preg_match_all( "/{$this->caption}/", $result, $_r ) );
		$this->assertEquals( 1, preg_match_all( "/width: 30/", $result, $_r ) );
	}

	function test_img_caption_shortcode_with_old_format_id_and_align() {
		$result = img_caption_shortcode(
			array(
				'width' => 20,
				'caption' => $this->caption,
				'id' => '"myId',
				'align' => '&myAlignment'
			)
		);
		$this->assertEquals( 1, preg_match_all( '/wp-caption &amp;myAlignment/', $result, $_r ) );
		$this->assertEquals( 1, preg_match_all( '/id="myId"/', $result, $_r ) );
		$this->assertEquals( 1, preg_match_all( "/{$this->caption}/", $result, $_r ) );
	}

	function test_new_img_caption_shortcode_with_html_caption() {
		$result = img_caption_shortcode(
			array( 'width' => 20, 'caption' => $this->html_content )
		);
		$our_preg = preg_quote( $this->html_content );

		$this->assertEquals( 1, preg_match_all( "~{$our_preg}~", $result, $_r ) );
	}

	function test_new_img_caption_shortcode_new_format() {
		$result = img_caption_shortcode(
			array( 'width' => 20 ),
			$this->img_content . $this->html_content
		);
		$img_preg = preg_quote( $this->img_content );
		$content_preg = preg_quote( $this->html_content );

		$this->assertEquals( 1, preg_match_all( "~{$img_preg}.*wp-caption-text~", $result, $_r ) );
		$this->assertEquals( 1, preg_match_all( "~wp-caption-text.*{$content_preg}~", $result, $_r ) );
	}

	function test_new_img_caption_shortcode_new_format_and_linked_image() {
		$linked_image = "<a href='#'>{$this->img_content}</a>";
		$result = img_caption_shortcode(
			array( 'width' => 20 ),
			$linked_image . $this->html_content
		);
		$img_preg = preg_quote( $linked_image );
		$content_preg = preg_quote( $this->html_content );

		$this->assertEquals( 1, preg_match_all( "~{$img_preg}.*wp-caption-text~", $result, $_r ) );
		$this->assertEquals( 1, preg_match_all( "~wp-caption-text.*{$content_preg}~", $result, $_r ) );
	}

	function test_new_img_caption_shortcode_new_format_and_linked_image_with_newline() {
		$linked_image = "<a href='#'>{$this->img_content}</a>";
		$result = img_caption_shortcode(
			array( 'width' => 20 ),
			$linked_image . "\n\n" . $this->html_content
		);
		$img_preg = preg_quote( $linked_image );
		$content_preg = preg_quote( $this->html_content );

		$this->assertEquals( 1, preg_match_all( "~{$img_preg}.*wp-caption-text~", $result, $_r ) );
		$this->assertEquals( 1, preg_match_all( "~wp-caption-text.*{$content_preg}~", $result, $_r ) );
	}

	function test_add_remove_oembed_provider() {
		wp_oembed_add_provider( 'http://foo.bar/*', 'http://foo.bar/oembed' );
		$this->assertTrue( wp_oembed_remove_provider( 'http://foo.bar/*' ) );
		$this->assertFalse( wp_oembed_remove_provider( 'http://foo.bar/*' ) );
	}

	/**
	 * @ticket 23776
	 */
	function test_autoembed_empty() {
		global $wp_embed;

		$content = '';

		$result = $wp_embed->autoembed( $content );
		$this->assertEquals( $content, $result );
	}

	/**
	 * @ticket 23776
	 */
	function test_autoembed_no_paragraphs_around_urls() {
		global $wp_embed;

		$content = <<<EOF
$ my command
First line.

http://example.com/1/
http://example.com/2/
Last line.

<pre>http://some.link/
http://some.other.link/</pre>
EOF;

		$result = $wp_embed->autoembed( $content );
		$this->assertEquals( $content, $result );
	}

	function test_wp_prepare_attachment_for_js() {
		// Attachment without media
		$id = wp_insert_attachment(array(
			'post_status' => 'publish',
			'post_title' => 'Prepare',
			'post_content_filtered' => 'Prepare',
			'post_type' => 'post'
		));
		$post = get_post( $id );

		$prepped = wp_prepare_attachment_for_js( $post );
		$this->assertInternalType( 'array', $prepped );
		$this->assertEquals( 0, $prepped['uploadedTo'] );
		$this->assertEquals( '', $prepped['mime'] );
		$this->assertEquals( '', $prepped['type'] );
		$this->assertEquals( '', $prepped['subtype'] );
		// #21963, there will be a guid always, so there will be a URL
		$this->assertNotEquals( '', $prepped['url'] );
		$this->assertEquals( site_url( 'wp-includes/images/media/default.png' ), $prepped['icon'] );

		// Fake a mime
		$post->post_mime_type = 'image/jpeg';
		$prepped = wp_prepare_attachment_for_js( $post );
		$this->assertEquals( 'image/jpeg', $prepped['mime'] );
		$this->assertEquals( 'image', $prepped['type'] );
		$this->assertEquals( 'jpeg', $prepped['subtype'] );

		// Fake a mime without a slash. See #WP22532
		$post->post_mime_type = 'image';
		$prepped = wp_prepare_attachment_for_js( $post );
		$this->assertEquals( 'image', $prepped['mime'] );
		$this->assertEquals( 'image', $prepped['type'] );
		$this->assertEquals( '', $prepped['subtype'] );
	}

	/**
	 * @ticket 19067
	 * @expectedDeprecated wp_convert_bytes_to_hr
	 */
	function test_wp_convert_bytes_to_hr() {
		$kb = 1024;
		$mb = $kb * 1024;
		$gb = $mb * 1024;
		$tb = $gb * 1024;

		// test if boundaries are correct
		$this->assertEquals( '1TB', wp_convert_bytes_to_hr( $tb ) );
		$this->assertEquals( '1GB', wp_convert_bytes_to_hr( $gb ) );
		$this->assertEquals( '1MB', wp_convert_bytes_to_hr( $mb ) );
		$this->assertEquals( '1kB', wp_convert_bytes_to_hr( $kb ) );

		$this->assertEquals( '1 TB', size_format( $tb ) );
		$this->assertEquals( '1 GB', size_format( $gb ) );
		$this->assertEquals( '1 MB', size_format( $mb ) );
		$this->assertEquals( '1 kB', size_format( $kb ) );

		// now some values around
		$hr = wp_convert_bytes_to_hr( $tb + $tb / 2 + $mb );
		$this->assertTrue( abs( 1.50000095367 - (float) str_replace( ',', '.', $hr ) ) < 0.0001 );

		$hr = wp_convert_bytes_to_hr( $tb - $mb - $kb );
		$this->assertTrue( abs( 1023.99902248 - (float) str_replace( ',', '.', $hr ) ) < 0.0001 );

		$hr = wp_convert_bytes_to_hr( $gb + $gb / 2 + $mb );
		$this->assertTrue( abs( 1.5009765625 - (float) str_replace( ',', '.', $hr ) ) < 0.0001 );

		$hr = wp_convert_bytes_to_hr( $gb - $mb - $kb );
		$this->assertTrue( abs( 1022.99902344 - (float) str_replace( ',', '.', $hr ) ) < 0.0001 );

		// edge
		$this->assertEquals( '-1B', wp_convert_bytes_to_hr( -1 ) );
		$this->assertEquals( '0B', wp_convert_bytes_to_hr( 0 ) );
	}

	/**
	 * @ticket 22960
	 */
	function test_get_attached_images() {
		$post_id = self::factory()->post->create();
		$attachment_id = self::factory()->attachment->create_object( $this->img_name, $post_id, array(
			'post_mime_type' => 'image/jpeg',
			'post_type' => 'attachment'
		) );

		$images = get_attached_media( 'image', $post_id );
		$this->assertEquals( $images, array( $attachment_id => get_post( $attachment_id ) ) );
	}

	/**
	 * @ticket 22960
	 */
	function test_post_galleries_images() {
		$ids1 = array();
		$ids1_srcs = array();
		foreach ( range( 1, 3 ) as $i ) {
			$attachment_id = self::factory()->attachment->create_object( "image$i.jpg", 0, array(
				'post_mime_type' => 'image/jpeg',
				'post_type' => 'attachment'
			) );
			$metadata = array_merge( array( "file" => "image$i.jpg" ), $this->img_meta );
			wp_update_attachment_metadata( $attachment_id, $metadata );
			$ids1[] = $attachment_id;
			$ids1_srcs[] = 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . "image$i.jpg";
		}

		$ids2 = array();
		$ids2_srcs = array();
		foreach ( range( 4, 6 ) as $i ) {
			$attachment_id = self::factory()->attachment->create_object( "image$i.jpg", 0, array(
				'post_mime_type' => 'image/jpeg',
				'post_type' => 'attachment'
			) );
			$metadata = array_merge( array( "file" => "image$i.jpg" ), $this->img_meta );
			wp_update_attachment_metadata( $attachment_id, $metadata );
			$ids2[] = $attachment_id;
			$ids2_srcs[] = 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . "image$i.jpg";
		}

		$ids1_joined = join( ',', $ids1 );
		$ids2_joined = join( ',', $ids2 );

		$blob =<<<BLOB
[gallery ids="$ids1_joined"]

[gallery ids="$ids2_joined"]
BLOB;
		$post_id = self::factory()->post->create( array( 'post_content' => $blob ) );
		$srcs = get_post_galleries_images( $post_id );
		$this->assertEquals( $srcs, array( $ids1_srcs, $ids2_srcs ) );
	}

	/**
	 * @ticket 22960
	 */
	function test_post_gallery_images() {
		$ids1 = array();
		$ids1_srcs = array();
		foreach ( range( 1, 3 ) as $i ) {
			$attachment_id = self::factory()->attachment->create_object( "image$i.jpg", 0, array(
				'post_mime_type' => 'image/jpeg',
				'post_type' => 'attachment'
			) );
			$metadata = array_merge( array( "file" => "image$i.jpg" ), $this->img_meta );
			wp_update_attachment_metadata( $attachment_id, $metadata );
			$ids1[] = $attachment_id;
			$ids1_srcs[] = 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . "image$i.jpg";
		}

		$ids2 = array();
		$ids2_srcs = array();
		foreach ( range( 4, 6 ) as $i ) {
			$attachment_id = self::factory()->attachment->create_object( "image$i.jpg", 0, array(
				'post_mime_type' => 'image/jpeg',
				'post_type' => 'attachment'
			) );
			$metadata = array_merge( array( "file" => "image$i.jpg" ), $this->img_meta );
			wp_update_attachment_metadata( $attachment_id, $metadata );
			$ids2[] = $attachment_id;
			$ids2_srcs[] = 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . "image$i.jpg";
		}

		$ids1_joined = join( ',', $ids1 );
		$ids2_joined = join( ',', $ids2 );

		$blob =<<<BLOB
[gallery ids="$ids1_joined"]

[gallery ids="$ids2_joined"]
BLOB;
		$post_id = self::factory()->post->create( array( 'post_content' => $blob ) );
		$srcs = get_post_gallery_images( $post_id );
		$this->assertEquals( $srcs, $ids1_srcs );
	}

	function test_get_media_embedded_in_content() {
		$object =<<<OBJ
<object src="this" data="that">
	<param name="value"/>
</object>
OBJ;
		$embed =<<<EMBED
<embed src="something.mp4"/>
EMBED;
		$iframe =<<<IFRAME
<iframe src="youtube.com" width="7000" />
IFRAME;
		$audio =<<<AUDIO
<audio preload="none">
	<source />
</audio>
AUDIO;
		$video =<<<VIDEO
<video preload="none">
	<source />
</video>
VIDEO;

		$content =<<<CONTENT
This is a comment
$object

This is a comment
$embed

This is a comment
$iframe

This is a comment
$audio

This is a comment
$video

This is a comment
CONTENT;

		$types = array( 'object', 'embed', 'iframe', 'audio', 'video' );
		$contents = array_values( compact( $types ) );

		$matches = get_media_embedded_in_content( $content, 'audio' );
		$this->assertEquals( array( $audio ), $matches );

		$matches = get_media_embedded_in_content( $content, 'video' );
		$this->assertEquals( array( $video ), $matches );

		$matches = get_media_embedded_in_content( $content, 'object' );
		$this->assertEquals( array( $object ), $matches );

		$matches = get_media_embedded_in_content( $content, 'embed' );
		$this->assertEquals( array( $embed ), $matches );

		$matches = get_media_embedded_in_content( $content, 'iframe' );
		$this->assertEquals( array( $iframe ), $matches );

		$matches = get_media_embedded_in_content( $content, $types );
		$this->assertEquals( $contents, $matches );
	}

	function test_get_media_embedded_in_content_order() {
		$audio =<<<AUDIO
<audio preload="none">
	<source />
</audio>
AUDIO;
		$video =<<<VIDEO
<video preload="none">
	<source />
</video>
VIDEO;
		$content = $audio . $video;

		$matches1 = get_media_embedded_in_content( $content, array( 'audio', 'video' ) );
		$this->assertEquals( array( $audio, $video ), $matches1 );

		$reversed = $video . $audio;
		$matches2 = get_media_embedded_in_content( $reversed, array( 'audio', 'video' ) );
		$this->assertEquals( array( $video, $audio ), $matches2 );
	}

	/**
	 * Test [video] shortcode processing
	 *
	 */
	function test_video_shortcode_body() {
		$width = 720;
		$height = 480;

		$post_id = get_post() ? get_the_ID() : 0;

		$video =<<<VIDEO
[video width="720" height="480" mp4="http://domain.tld/wp-content/uploads/2013/12/xyz.mp4"]
<!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
<source type="video/webm" src="myvideo.webm" />
<!-- Ogg/Vorbis for older Firefox and Opera versions -->
<source type="video/ogg" src="myvideo.ogv" />
<!-- Optional: Add subtitles for each language -->
<track kind="subtitles" src="subtitles.srt" srclang="en" />
<!-- Optional: Add chapters -->
<track kind="chapters" src="chapters.srt" srclang="en" />
[/video]
VIDEO;

		$w = empty( $GLOBALS['content_width'] ) ? 640 : $GLOBALS['content_width'];
		$h = ceil( ( $height * $w ) / $width );

		$content = apply_filters( 'the_content', $video );

		$expected = '<div style="width: ' . $w . 'px; " class="wp-video">' .
			"<!--[if lt IE 9]><script>document.createElement('video');</script><![endif]-->\n" .
			'<video class="wp-video-shortcode" id="video-' . $post_id . '-1" width="' . $w . '" height="' . $h . '" preload="metadata" controls="controls">' .
			'<source type="video/mp4" src="http://domain.tld/wp-content/uploads/2013/12/xyz.mp4?_=1" />' .
			'<!-- WebM/VP8 for Firefox4, Opera, and Chrome --><source type="video/webm" src="myvideo.webm" />' .
			'<!-- Ogg/Vorbis for older Firefox and Opera versions --><source type="video/ogg" src="myvideo.ogv" />' .
			'<!-- Optional: Add subtitles for each language --><track kind="subtitles" src="subtitles.srt" srclang="en" />' .
			'<!-- Optional: Add chapters --><track kind="chapters" src="chapters.srt" srclang="en" />' .
			'<a href="http://domain.tld/wp-content/uploads/2013/12/xyz.mp4">' .
			"http://domain.tld/wp-content/uploads/2013/12/xyz.mp4</a></video></div>\n";

		$this->assertEquals( $expected, $content );
	}

	/**
	 * @ticket 26768
	 */
	function test_add_image_size() {
		global $_wp_additional_image_sizes;

		if ( ! isset( $_wp_additional_image_sizes ) ) {
			$_wp_additional_image_sizes = array();
		}

		$this->assertArrayNotHasKey( 'test-size', $_wp_additional_image_sizes );
		add_image_size( 'test-size', 200, 600 );
		$this->assertArrayHasKey( 'test-size', $_wp_additional_image_sizes );
		$this->assertEquals( 200, $_wp_additional_image_sizes['test-size']['width'] );
		$this->assertEquals( 600, $_wp_additional_image_sizes['test-size']['height'] );

		// Clean up
		remove_image_size( 'test-size' );
	}

	/**
	 * @ticket 26768
	 */
	function test_remove_image_size() {
		add_image_size( 'test-size', 200, 600 );
		$this->assertTrue( has_image_size( 'test-size' ) );
		remove_image_size( 'test-size' );
		$this->assertFalse( has_image_size( 'test-size' ) );
	}

	/**
	 * @ticket 26951
	 */
	function test_has_image_size() {
		add_image_size( 'test-size', 200, 600 );
		$this->assertTrue( has_image_size( 'test-size' ) );

		// Clean up
		remove_image_size( 'test-size' );
	}

	/**
	 * @ticket 30346
	 */
	function test_attachment_url_to_postid() {
		$image_path = '2014/11/' . $this->img_name;
		$attachment_id = self::factory()->attachment->create_object( $image_path, 0, array(
			'post_mime_type' => 'image/jpeg',
			'post_type'      => 'attachment',
		) );

		$image_url  = 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . $image_path;
		$this->assertEquals( $attachment_id, attachment_url_to_postid( $image_url ) );
	}

	function test_attachment_url_to_postid_schemes() {
		$image_path = '2014/11/' . $this->img_name;
		$attachment_id = self::factory()->attachment->create_object( $image_path, 0, array(
			'post_mime_type' => 'image/jpeg',
			'post_type'      => 'attachment',
		) );

		/**
		 * @ticket 33109 Testing protocols not matching
		 */
		$image_url  = 'https://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . $image_path;
		$this->assertEquals( $attachment_id, attachment_url_to_postid( $image_url ) );
	}

	function test_attachment_url_to_postid_filtered() {
		$image_path = '2014/11/' . $this->img_name;
		$attachment_id = self::factory()->attachment->create_object( $image_path, 0, array(
			'post_mime_type' => 'image/jpeg',
			'post_type'      => 'attachment',
		) );

		add_filter( 'upload_dir', array( $this, '_upload_dir' ) );
		$image_url = 'http://192.168.1.20.com/wp-content/uploads/' . $image_path;
		$this->assertEquals( $attachment_id, attachment_url_to_postid( $image_url ) );
		remove_filter( 'upload_dir', array( $this, '_upload_dir' ) );
	}

	function _upload_dir( $dir ) {
		$dir['baseurl'] = 'http://192.168.1.20.com/wp-content/uploads';
		return $dir;
	}

	/**
	 * @ticket 31044
	 */
	function test_attachment_url_to_postid_with_empty_url() {
		$post_id = attachment_url_to_postid( '' );
		$this->assertInternalType( 'int', $post_id );
		$this->assertEquals( 0, $post_id );
	}

	/**
	 * @ticket 22768
	 */
	public function test_media_handle_upload_sets_post_excerpt() {
		$iptc_file = DIR_TESTDATA . '/images/test-image-iptc.jpg';

		// Make a copy of this file as it gets moved during the file upload
		$tmp_name = wp_tempnam( $iptc_file );

		copy( $iptc_file, $tmp_name );

		$_FILES['upload'] = array(
			'tmp_name' => $tmp_name,
			'name'     => 'test-image-iptc.jpg',
			'type'     => 'image/jpeg',
			'error'    => 0,
			'size'     => filesize( $iptc_file )
		);

		$post_id = media_handle_upload( 'upload', 0, array(), array( 'action' => 'test_iptc_upload', 'test_form' => false ) );

		unset( $_FILES['upload'] );

		$post = get_post( $post_id );

		// Clean up.
		wp_delete_attachment( $post_id );

		$this->assertEquals( 'This is a comment. / Это комментарий. / Βλέπετε ένα σχόλιο.', $post->post_excerpt );
	}

	/**
	 * @ticket 33016
	 */
	function test_multiline_cdata() {
		global $wp_embed;

		$content = <<<EOF
<script>// <![CDATA[
_my_function('data');
// ]]>
</script>
EOF;

		$result = $wp_embed->autoembed( $content );
		$this->assertEquals( $content, $result );
	}

	/**
	 * @ticket 33016
	 */
	function test_multiline_comment() {
		global $wp_embed;

		$content = <<<EOF
<script><!--
my_function();
// --> </script>
EOF;

		$result = $wp_embed->autoembed( $content );
		$this->assertEquals( $content, $result );
	}


	/**
	 * @ticket 33016
	 */
	function test_multiline_comment_with_embeds() {
		$content = <<<EOF
Start.
[embed]http://www.youtube.com/embed/TEST01YRHA0[/embed]
<script><!--
my_function();
// --> </script>
http://www.youtube.com/embed/TEST02YRHA0
[embed]http://www.example.com/embed/TEST03YRHA0[/embed]
http://www.example.com/embed/TEST04YRHA0
Stop.
EOF;

		$expected = <<<EOF
<p>Start.<br />
https://youtube.com/watch?v=TEST01YRHA0<br />
<script><!--
my_function();
// --> </script><br />
https://youtube.com/watch?v=TEST02YRHA0<br />
<a href="http://www.example.com/embed/TEST03YRHA0">http://www.example.com/embed/TEST03YRHA0</a><br />
http://www.example.com/embed/TEST04YRHA0<br />
Stop.</p>

EOF;

		$result = apply_filters( 'the_content', $content );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * @ticket 33016
	 */
	function filter_wp_embed_shortcode_custom( $content, $url ) {
		if ( 'https://www.example.com/?video=1' == $url ) {
			$content = '@embed URL was replaced@';
		}
		return $content;
	}

	/**
	 * @ticket 33016
	 */
	function test_oembed_explicit_media_link() {
		global $wp_embed;
		add_filter( 'embed_maybe_make_link', array( $this, 'filter_wp_embed_shortcode_custom' ), 10, 2 );

		$content = <<<EOF
https://www.example.com/?video=1
EOF;

		$expected = <<<EOF
@embed URL was replaced@
EOF;

		$result = $wp_embed->autoembed( $content );
		$this->assertEquals( $expected, $result );

		$content = <<<EOF
<a href="https://www.example.com/?video=1">https://www.example.com/?video=1</a>
<script>// <![CDATA[
_my_function('data');
myvar = 'Hello world
https://www.example.com/?video=1
do not break this';
// ]]>
</script>
EOF;

		$result = $wp_embed->autoembed( $content );
		$this->assertEquals( $content, $result );

		remove_filter( 'embed_maybe_make_link', array( $this, 'filter_wp_embed_shortcode_custom' ), 10 );
	}

	/**
	 * @ticket 33878
	 */
	function test_wp_get_attachment_image_url() {
		$this->assertFalse( wp_get_attachment_image_url( 0 ) );

		$post_id = self::factory()->post->create();
		$attachment_id = self::factory()->attachment->create_object( $this->img_name, $post_id, array(
			'post_mime_type' => 'image/jpeg',
			'post_type' => 'attachment',
		) );

		$image = wp_get_attachment_image_src( $attachment_id, 'thumbnail', false );

		$this->assertEquals( $image[0], wp_get_attachment_image_url( $attachment_id ) );
	}

	/**
	 * Helper function to get image size array from size "name"
	 */
	function _get_image_size_array_from_name( $size_name ) {
		switch ( $size_name ) {
			case 'thumbnail':
				return array( 150, 150 );
			case 'medium':
				return array( 300, 225 );
			case 'medium_large':
				return array( 768, 576 );
			case 'large':
				return array( 1024, 768 );
			case 'full':
				return array( 1600, 1200 ); // actual size of ../data/images/test-image-large.png
			default:
				return array( 800, 600 ); // soft-resized image
		}
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_calculate_image_srcset() {
		$year_month = date('Y/m');
		$image_meta = wp_get_attachment_metadata( self::$large_id );
		$uploads_dir_url = 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/';

		$expected = $uploads_dir_url . $year_month . '/' . $image_meta['sizes']['medium']['file'] . ' ' . $image_meta['sizes']['medium']['width'] . 'w, ' .
				$uploads_dir_url . $year_month . '/' . $image_meta['sizes']['medium_large']['file'] . ' ' . $image_meta['sizes']['medium_large']['width'] . 'w, ' .
				$uploads_dir_url . $year_month . '/' . $image_meta['sizes']['large']['file'] . ' ' . $image_meta['sizes']['large']['width'] . 'w, ' .
				$uploads_dir_url . $image_meta['file'] . ' ' . $image_meta['width'] . 'w';

		// Set up test cases for all expected size names.
		$sizes = array( 'medium', 'medium_large', 'large', 'full' );

		foreach ( $sizes as $size ) {
			$image_url = wp_get_attachment_image_url( self::$large_id, $size );
			$size_array = $this->_get_image_size_array_from_name( $size );
			$this->assertSame( $expected, wp_calculate_image_srcset( $size_array, $image_url, $image_meta ) );
		}
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_calculate_image_srcset_no_date_uploads() {
		// Save the current setting for uploads folders
		$uploads_use_yearmonth_folders = get_option( 'uploads_use_yearmonth_folders' );

		// Disable date organized uploads
		update_option( 'uploads_use_yearmonth_folders', 0 );

		// Make an image.
		$filename = DIR_TESTDATA . '/images/test-image-large.png';
		$id = self::factory()->attachment->create_upload_object( $filename );

		$image_meta = wp_get_attachment_metadata( $id );
		$uploads_dir_url = 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/';

		$expected = $uploads_dir_url . $image_meta['sizes']['medium']['file'] . ' ' . $image_meta['sizes']['medium']['width'] . 'w, ' .
				$uploads_dir_url . $image_meta['sizes']['medium_large']['file'] . ' ' . $image_meta['sizes']['medium_large']['width'] . 'w, ' .
				$uploads_dir_url . $image_meta['sizes']['large']['file'] . ' ' . $image_meta['sizes']['large']['width'] . 'w, ' .
				$uploads_dir_url . $image_meta['file'] . ' ' . $image_meta['width'] . 'w';

		// Set up test cases for all expected size names.
		$sizes = array( 'medium', 'medium_large', 'large', 'full' );

		foreach ( $sizes as $size ) {
			$size_array = $this->_get_image_size_array_from_name( $size );
			$image_url = wp_get_attachment_image_url( $id, $size );
			$this->assertSame( $expected, wp_calculate_image_srcset( $size_array, $image_url, $image_meta ) );
		}

		// Remove the attachment
		wp_delete_attachment( $id );

		// Leave the uploads option the way you found it.
		update_option( 'uploads_use_yearmonth_folders', $uploads_use_yearmonth_folders );
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_calculate_image_srcset_with_edits() {
		// For this test we're going to mock metadata changes from an edit.
		// Start by getting the attachment metadata.
		$image_meta = wp_get_attachment_metadata( self::$large_id );
		$image_url = wp_get_attachment_image_url( self::$large_id, 'medium' );
		$size_array = $this->_get_image_size_array_from_name( 'medium' );

		// Copy hash generation method used in wp_save_image().
		$hash = 'e' . time() . rand(100, 999);

		$filename_base = basename( $image_meta['file'], '.png' );

		// Add the hash to the image URL
		$image_url = str_replace( $filename_base, $filename_base . '-' . $hash, $image_url );

		// Replace file paths for full and medium sizes with hashed versions.
		$image_meta['file'] = str_replace( $filename_base, $filename_base . '-' . $hash, $image_meta['file'] );
		$image_meta['sizes']['medium']['file'] = str_replace( $filename_base, $filename_base . '-' . $hash, $image_meta['sizes']['medium']['file'] );
		$image_meta['sizes']['medium_large']['file'] = str_replace( $filename_base, $filename_base . '-' . $hash, $image_meta['sizes']['medium_large']['file'] );
		$image_meta['sizes']['large']['file'] = str_replace( $filename_base, $filename_base . '-' . $hash, $image_meta['sizes']['large']['file'] );

		// Calculate a srcset array.
		$sizes = explode( ', ', wp_calculate_image_srcset( $size_array, $image_url, $image_meta ) );

		// Test to confirm all sources in the array include the same edit hash.
		foreach ( $sizes as $size ) {
			$this->assertTrue( false !== strpos( $size, $hash ) );
		}
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_calculate_image_srcset_false() {
		$sizes = wp_calculate_image_srcset( array( 400, 300 ), 'file.png', array() );

		// For canola.jpg we should return
		$this->assertFalse( $sizes );
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_calculate_image_srcset_no_width() {
		$file = get_attached_file( self::$large_id );
		$image_url = wp_get_attachment_image_url( self::$large_id, 'medium' );
		$image_meta = wp_generate_attachment_metadata( self::$large_id, $file );

		$size_array = array(0, 0);

		$srcset = wp_calculate_image_srcset( $size_array, $image_url, $image_meta );

		// The srcset should be false.
		$this->assertFalse( $srcset );
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_get_attachment_image_srcset() {
		$image_meta = wp_get_attachment_metadata( self::$large_id );
		$size_array = array( 1600, 1200 ); // full size

		$srcset = wp_get_attachment_image_srcset( self::$large_id, $size_array, $image_meta );

		$year_month = date('Y/m');

		$expected = 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . $year_month = date('Y/m') . '/'
			. $image_meta['sizes']['medium']['file'] . ' ' . $image_meta['sizes']['medium']['width'] . 'w, ';
		$expected .= 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . $year_month = date('Y/m') . '/'
			. $image_meta['sizes']['medium_large']['file'] . ' ' . $image_meta['sizes']['medium_large']['width'] . 'w, ';
		$expected .= 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . $year_month = date('Y/m') . '/'
			. $image_meta['sizes']['large']['file'] . ' ' . $image_meta['sizes']['large']['width'] . 'w, ';
		$expected .= 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . $image_meta['file'] . ' ' . $image_meta['width'] .'w';

		$this->assertSame( $expected, $srcset );
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_get_attachment_image_srcset_single_srcset() {
		$image_meta = wp_get_attachment_metadata( self::$large_id );
		$size_array = array( 150, 150 );
		/*
		 * In our tests, thumbnails will only return a single srcset candidate,
		 * so we shouldn't return a srcset value in order to avoid unneeded markup.
		 */
		$sizes = wp_get_attachment_image_srcset( self::$large_id, $size_array, $image_meta );

		$this->assertFalse( $sizes );
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_get_attachment_image_srcset_invalidsize() {
		$image_meta = wp_get_attachment_metadata( self::$large_id );
		$invalid_size = 'nailthumb';
		$original_size = array( 1600, 1200 );

		$srcset = wp_get_attachment_image_srcset( self::$large_id, $invalid_size, $image_meta );

		// Expect a srcset for the original full size image to be returned.
		$expected = wp_get_attachment_image_srcset( self::$large_id, $original_size, $image_meta );

		$this->assertSame( $expected, $srcset );
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_get_attachment_image_sizes() {
		// Test sizes against the default WP sizes.
		$intermediates = array('thumbnail', 'medium', 'medium_large', 'large');

		foreach( $intermediates as $int_size ) {
			$image = wp_get_attachment_image_src( self::$large_id, $int_size );

			$expected = '(max-width: ' . $image[1] . 'px) 100vw, ' . $image[1] . 'px';
			$sizes = wp_get_attachment_image_sizes( self::$large_id, $int_size );

			$this->assertSame( $expected, $sizes );
		}
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_calculate_image_sizes() {
		// Test sizes against the default WP sizes.
		$intermediates = array('thumbnail', 'medium', 'medium_large', 'large');
		$image_meta = wp_get_attachment_metadata( self::$large_id );

		foreach( $intermediates as $int_size ) {
			$size_array = $this->_get_image_size_array_from_name( $int_size );
			$image_src = $image_meta['sizes'][$int_size]['file'];
			list( $width, $height ) = $size_array;

			$expected = '(max-width: ' . $width . 'px) 100vw, ' . $width . 'px';
			$sizes = wp_calculate_image_sizes( $size_array, $image_src, $image_meta );

			$this->assertSame( $expected, $sizes );
		}
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_make_content_images_responsive() {
		$image_meta = wp_get_attachment_metadata( self::$large_id );
		$size_array = $this->_get_image_size_array_from_name( 'medium' );

		$srcset = sprintf( 'srcset="%s"', wp_get_attachment_image_srcset( self::$large_id, $size_array, $image_meta ) );
		$sizes = sprintf( 'sizes="%s"', wp_get_attachment_image_sizes( self::$large_id, $size_array, $image_meta ) );

		// Function used to build HTML for the editor.
		$img = get_image_tag( self::$large_id, '', '', '', 'medium' );
		$img_no_size_in_class = str_replace( 'size-', '', $img );
		$img_no_width_height = str_replace( ' width="' . $size_array[0] . '"', '', $img );
		$img_no_width_height = str_replace( ' height="' . $size_array[1] . '"', '', $img_no_width_height );
		$img_no_size_id = str_replace( 'wp-image-', 'id-', $img );
		$img_with_sizes_attr = str_replace( '<img ', '<img sizes="99vw" ', $img );

		// Manually add srcset and sizes to the markup from get_image_tag();
		$respimg = preg_replace( '|<img ([^>]+) />|', '<img $1 ' . $srcset . ' ' . $sizes . ' />', $img );
		$respimg_no_size_in_class = preg_replace( '|<img ([^>]+) />|', '<img $1 ' . $srcset . ' ' . $sizes . ' />', $img_no_size_in_class );
		$respimg_no_width_height = preg_replace( '|<img ([^>]+) />|', '<img $1 ' . $srcset . ' ' . $sizes . ' />', $img_no_width_height );
		$respimg_with_sizes_attr = preg_replace('|<img ([^>]+) />|', '<img $1 ' . $srcset . ' />', $img_with_sizes_attr );

		$content = '
			<p>Image, standard. Should have srcset and sizes.</p>
			%1$s

			<p>Image, no size class. Should have srcset and sizes.</p>
			%2$s

			<p>Image, no width and height attributes. Should have srcset and sizes (from matching the file name).</p>
			%3$s

			<p>Image, no attachment ID class. Should NOT have srcset and sizes.</p>
			%4$s

			<p>Image, with sizes attribute. Should NOT have two sizes attributes.</p>
			%5$s';

		$content_unfiltered = sprintf( $content, $img, $img_no_size_in_class, $img_no_width_height, $img_no_size_id, $img_with_sizes_attr );
		$content_filtered = sprintf( $content, $respimg, $respimg_no_size_in_class, $respimg_no_width_height, $img_no_size_id, $respimg_with_sizes_attr );

		$this->assertSame( $content_filtered, wp_make_content_images_responsive( $content_unfiltered ) );
	}

	/**
	 * @ticket 33641
	 */
	function test_wp_make_content_images_responsive_with_preexisting_srcset() {
		// Generate HTML and add a dummy srcset attribute.
		$image_html = get_image_tag( self::$large_id, '', '', '', 'medium' );
		$image_html = preg_replace('|<img ([^>]+) />|', '<img $1 ' . 'srcset="image2x.jpg 2x" />', $image_html );

		// The content filter should return the image unchanged.
		$this->assertSame( $image_html, wp_make_content_images_responsive( $image_html ) );
	}

	/**
	 * @ticket 33641
	 * @ticket 34528
	 */
	function test_wp_calculate_image_srcset_animated_gifs() {
		// Mock meta for an animated gif.
		$image_meta = array(
			'width' => 1200,
			'height' => 600,
			'file' => 'animated.gif',
			'sizes' => array(
				'thumbnail' => array(
					'file' => 'animated-150x150.gif',
					'width' => 150,
					'height' => 150,
					'mime-type' => 'image/gif'
				),
				'medium' => array(
					'file' => 'animated-300x150.gif',
					'width' => 300,
					'height' => 150,
					'mime-type' => 'image/gif'
				),
				'large' => array(
					'file' => 'animated-1024x512.gif',
					'width' => 1024,
					'height' => 512,
					'mime-type' => 'image/gif'
				),
			)
		);

		$full_src  = 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . $image_meta['file'];
		$large_src = 'http://' . WP_TESTS_DOMAIN . '/wp-content/uploads/' . $image_meta['sizes']['large']['file'];

		// Test with soft resized size array.
		$size_array = array(900, 450);

		// Full size GIFs should not return a srcset.
		$this->assertFalse( wp_calculate_image_srcset( $size_array, $full_src, $image_meta ) );
		// Intermediate sized GIFs should not include the full size in the srcset.
		$this->assertFalse( strpos( wp_calculate_image_srcset( $size_array, $large_src, $image_meta ), $full_src ) );
	}
}
