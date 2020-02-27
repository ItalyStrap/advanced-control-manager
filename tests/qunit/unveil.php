<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>QUnit basic example</title>
	<link rel="stylesheet" href="https://code.jquery.com/qunit/qunit-2.9.2.css">
</head>
<body>
<div id="qunit"></div>
<div id="qunit-fixture">
	<?php  ?>
	<img
		id="first-image"
		data-lazy-src="https://picsum.photos/200/300.webp"
		src="PLACEHOLDER"
		alt=""
	>
	<img
		id="second-image"
		data-lazy-src="https://picsum.photos/200/300.webp"
		data-lazy-srcset="https://picsum.photos/200/300.webp"
		src="PLACEHOLDER"
	 	alt=""
	>
	<img
		id="third-image"
		data-lazy-src="https://picsum.photos/200/300.webp"
		data-lazy-srcset="https://picsum.photos/200/300.webp"
		data-sizes="(max-width: 600px) 200px, 50vw"
		src="PLACEHOLDER"
		alt=""
	>
</div>
<script src="https://code.jquery.com/qunit/qunit-2.9.2.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="../../js/src/unveil.js"></script>
<script>
	jQuery(document).ready(function($){
		var img = $("img[data-lazy-src]");
		img.unveil(0, function(){
			img.load(function(){
				this.style.opacity = 1;
			});
		});

		/**
		 * Only for testing trigger the unveil callback
		 */
		img.trigger( "unveil" );
	});
</script>
<script>

	QUnit.testStart(function( details ) {
		console.log( "Now running: ", details.module, details.name, details );
	});

	QUnit.test( "Unveil image with only src", function( assert ) {
		assert.expect( 1 );

		var $img = $( "#first-image" );

		assert.equal(
			$img.attr('src'),
			"https://picsum.photos/200/300.webp",
			"Expect src value is not a placeholder"
		);
	});

	QUnit.test( "Unveil image with src and srcset", function( assert ) {
		assert.expect( 3 );

		var $img = $( "#second-image" );

		assert.equal(
			$img.attr('src'),
			"https://picsum.photos/200/300.webp",
			"Expect src value is not a placeholder"
		);
		assert.equal(
			$img.attr('srcset'),
			"https://picsum.photos/200/300.webp",
			"Expect srcset value is " + $img.attr('srcset')
		);
		assert.equal(
			$img.attr('sizes'),
			undefined,
			"Expect sizes value is undefined"
		);
	});

	QUnit.test( "Unveil image with src, srcset and sizes", function( assert ) {
		assert.expect( 3 );

		var $img = $( "#third-image" );

		assert.equal(
			$img.attr('src'),
			"https://picsum.photos/200/300.webp",
			"Expect src value is not a placeholder"
		);

		assert.equal(
			$img.attr('srcset'),
			"https://picsum.photos/200/300.webp",
			"Expect srcset value is " + $img.attr('srcset')
		);

		assert.equal(
			$img.attr('sizes'),
			"(max-width: 600px) 200px, 50vw",
			"Expect sizes value is " + $img.attr('sizes')
		);
	});
</script>
</body>
</html>
