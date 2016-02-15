<?php
/**
 * This is a template content for outputting the vCard Business Widget and Shortcode
 *
 * @package ItalyStrap\Core
 */

?>
<ul itemscope itemtype="http://schema.org/<?php esc_textarea( $instance['schema'] ); ?>" class="list-unstyled schema" id="schema">

<?php if ( $instance['show_logo'] ) : ?>

	<img src="<?php echo esc_url( $instance['logo_url'] );?>" alt="<?php echo esc_textarea( $instance['company_name'] ); ?>" itemprop="logo" />

<?php else : ?>

	<meta  itemprop="logo" content="<?php echo esc_url( $instance['logo_url'] );?>"/>

<?php endif; ?>

<li>
	<strong>
		<a itemprop="url" href="<?php echo home_url( '/' ); ?>">
			<span itemprop="name">
				<?php

				if ( $instance['company_name'] )
					echo esc_textarea( $instance['company_name'] );

				else echo bloginfo( 'name' );

				?>
			</span>
		</a>
	</strong>
</li>
<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
	<li itemprop="streetAddress">
		<?php echo esc_textarea( $instance['street_address'] ); ?>
	</li>
	<li>
		<span itemprop="postalCode"><?php echo esc_textarea( $instance['postal_code'] ) . ' ';?></span>
		<span itemprop="addressLocality"><?php echo esc_textarea( $instance['locality'] ); ?></span>
	</li>
	<li itemprop="addressRegion"><?php echo esc_textarea( $instance['region'] ); ?></li>
	<li itemprop="addressCountry"><?php echo esc_textarea( $instance['country'] ); ?></li>
</div>
<li itemprop="telephone"><?php echo esc_textarea( $instance['tel'] ); ?></li>
<li itemprop="telephone"><?php echo esc_textarea( $instance['mobile'] ); ?></li>
<li itemprop="faxNumber"><?php echo esc_textarea( $instance['fax'] ); ?></li>
<li itemprop="email">
	<a href="mailto:<?php echo antispambot( is_email( $instance['email'], 1 ) ); // XSS ok. ?>"><?php echo antispambot( is_email( $instance['email'] ) ); // XSS ok. ?></a>
</li>
<li itemprop="taxID"><?php echo esc_textarea( $instance['taxID'] ); ?></li>

<?php

if ( $instance['facebook'] )
	echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['facebook'] ) . '"/>';

if ( $instance['twitter'] )
	echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['twitter'] ) . '"/>';

if ( $instance['googleplus'] )
	echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['googleplus'] ) . '"/>';

if ( $instance['pinterest'] )
	echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['pinterest'] ) . '"/>';

if ( $instance['instagram'] )
	echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['instagram'] ) . '"/>';

if ( $instance['youtube'] )
	echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['youtube'] ) . '"/>';

if ( $instance['linkedin'] )
	echo '<meta  itemprop="sameAs" content="' . esc_url( $instance['linkedin'] ) . '"/>';

?>
</ul>
