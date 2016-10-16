<?php
/**
 * [Short Description (no period for file headers)]
 *
 * [Long Description.]
 *
 * @link [URL]
 * @since [x.x.x (if available)]
 *
 * @package [Plugin/Theme/Etc]
 */

namespace ItalyStrap\Core;

/**
 * Class description
 */
class Pixel {

	/**
	 * [$var description]
	 *
	 * @var null
	 */
	private $var = null;

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( $argument = null ) {
		// Code...
	}

	/**
	 * Render
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function render() {
	
		?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq( 'init', '1660928707515909' );
<?php if ( $nome_pagina == "conversione.php" ): ?>
fbq( 'track', 'Purchase', {value: '10.00', currency:'EUR'} );
<?php else: ?>
fbq( 'track', "PageView" );
<?php endif; ?>
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1660928707515909&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
		<?php
	
	}
}
