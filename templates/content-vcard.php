<?php
/**
 * This is a template content for outputting the vCard Business Widget and Shortcode
 *
 * @package ItalyStrap\Core
 */

?>
<ul itemscope itemtype="http://schema.org/<?php esc_textarea( $instance['schema'] ); ?>" class="list-unstyled schema <?php echo esc_attr( $instance['container_class'] ); ?>" id="schema">

	<?php

	// $html = '';

	// foreach ( (array) $instance as $key => $value ) {

	// 	if ( $value ) {
	// 		$html .= '<li>' . esc_attr( $value ) . '</li>';
	// 	}
	// }
	// echo $html; // XSS ok.

	?>

<?php if ( $instance['show_logo'] && $instance['logo_url'] ) : ?>

	<img src="<?php echo esc_url( $instance['logo_url'] );?>" alt="<?php echo esc_textarea( $instance['company_name'] ); ?>" itemprop="logo" />

<?php elseif( $instance['logo_url'] ) : ?>

	<meta  itemprop="logo" content="<?php echo esc_url( $instance['logo_url'] );?>"/>

<?php endif; ?>

<li>
	<strong>
		<a itemprop="url" href="<?php echo home_url( '/' ); // XSS ok. ?>">
			<span itemprop="name">
				<?php

				if ( $instance['company_name'] ) {
					echo esc_textarea( $instance['company_name'] );
				} else {
					echo bloginfo( 'name' );
				}

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
	<?php if ( $instance['region'] ) : ?>
	<li itemprop="addressRegion"><?php echo esc_textarea( $instance['region'] ); ?></li>
	<?php endif; ?>
	<?php if ( $instance['country'] ) : ?>
	<li itemprop="addressCountry"><?php echo esc_textarea( $instance['country'] ); ?></li>
	<?php endif; ?>
</div>

<?php

if ( $instance['tel'] ) {
	echo '<li class="telephone" itemprop="telephone">' . esc_textarea( $instance['tel'] ) . '</li>';
}

if ( $instance['mobile'] ) {
	echo '<li class="telephone" itemprop="telephone">' . esc_textarea( $instance['mobile'] ) . '</li>';
}

if ( $instance['fax'] ) {
	echo '<li class="faxNumber" itemprop="faxNumber">' . esc_textarea( $instance['fax'] ) . '</li>';
}

if ( $instance['email'] ) {
	echo '<li class="email" itemprop="email"><a href="mailto:' . antispambot( is_email( $instance['email'], 1 ) ) . '">' . antispambot( is_email( $instance['email'] ) ) . '</a></li>'; // XSS ok.
}

if ( $instance['taxID'] ) {
	echo '<li class="taxID" itemprop="taxID">' . esc_textarea( $instance['taxID'] ) . '</li>';
}

if ( $instance['facebook'] ) {
	echo '<meta itemprop="sameAs" content="' . esc_url( $instance['facebook'] ) . '"/>';
}

if ( $instance['twitter'] ) {
	echo '<meta itemprop="sameAs" content="' . esc_url( $instance['twitter'] ) . '"/>';
}

if ( $instance['googleplus'] ) {
	echo '<meta itemprop="sameAs" content="' . esc_url( $instance['googleplus'] ) . '"/>';
}

if ( $instance['pinterest'] ) {
	echo '<meta itemprop="sameAs" content="' . esc_url( $instance['pinterest'] ) . '"/>';
}

if ( $instance['instagram'] ) {
	echo '<meta itemprop="sameAs" content="' . esc_url( $instance['instagram'] ) . '"/>';
}

if ( $instance['youtube'] ) {
	echo '<meta itemprop="sameAs" content="' . esc_url( $instance['youtube'] ) . '"/>';
}

if ( $instance['linkedin'] ) {
	echo '<meta itemprop="sameAs" content="' . esc_url( $instance['linkedin'] ) . '"/>';
}

?>
</ul>
