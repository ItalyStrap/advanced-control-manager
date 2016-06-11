<?php
/**
 * Template per visualizzare i bottoni sociali
 *
 * Alcune idee https://www.google.it/search?site=&source=hp&q=twitter+url+share&oq=twitter+url+share&gs_l=hp.3.0.0i19l3j0i22i10i30i19j0i22i30i19l6.1898.11624.0.13381.18.18.0.0.0.0.2152.4618.0j13j0j1j1j9-1.16.0....0...1c.1.64.hp..2.15.4160.0.93FpzCXhTCY
 *
 * @package ItalyStrap\Controzzi
 */

$option = get_option( 'wpseo_social' );
$via = ( ! empty( $option['twitter_site'] ) ) ? '&via=' . $option['twitter_site'] : '' ;

?>

<ul class="social-button list-inline">
	<li><span class="font-icon icon-share"></span></li>

	<li><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" target="popup" onclick="window.open('http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>','popup','width=600,height=600'); return false;" rel="nofollow" title="Condividi su Facebook"><span class="font-icon icon-facebook"></span></a></li>

	<li><a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); echo esc_attr( $via ); ?>" target="popup" onclick="window.open('https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); echo esc_attr( $via ); ?>','popup','width=600,height=600'); return false;" rel="nofollow" title="Condividi su Twitter"><span class="font-icon icon-twitter"></span></a></li>

	<li><a href="	
https://plus.google.com/share?url=<?php the_permalink(); ?>"><span class="font-icon icon-gplus" target="popup" onclick="window.open('https://plus.google.com/share?url=<?php the_permalink(); ?>','popup','width=600,height=600'); return false;" rel="nofollow" title="Condividi su Google+"></span></a></li>

	<li><a href="http://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="popup" onclick="window.open('http://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>','popup','width=600,height=600'); return false;" rel="nofollow" title="Condividi su LinkedIn"><span class="font-icon icon-linkedin"></span></a></li>

</ul>
