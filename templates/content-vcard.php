<?php

/**
 * Template for vCard Business Widget and Shortcode
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core;

/**
 * HTML attribute for ul container
 *
 * @var array
 */
$ul_attr = ['id'    => 'schema', 'class' => 'list-unstyled schema vCard' . esc_attr($this->args['container_class']), 'itemtype'  => 'http://schema.org/' . esc_textarea($this->args['schema']), 'itemscope' => true];

?>

<ul <?php get_attr('vcard_container', $ul_attr, true); ?>>

<?php if ($this->args['show_logo'] && $this->args['logo_id']) : ?>
    <li class='image'>
        <figure class="vcard-logo">
            <?php
            $attr = ['itemprop'  => 'image', 'class'     => esc_attr($this->args['logo_class']), 'alt'       => $this->get_company_name()];
            $the_post_thumbnail = wp_get_attachment_image($this->args['logo_id'], $this->args['logo_size'], false, $attr);
            echo apply_filters('italystrap_vcard_logo', $the_post_thumbnail); // XSS ok.
            ?>
        </figure>
    </li>

<?php elseif ($this->args['logo_id']) : ?>
    <?php $logo_url = wp_get_attachment_image_src($this->args['logo_id']); ?>

    <meta  itemprop="logo" content="<?php echo esc_url($logo_url[0]);?>"/>

<?php endif; ?>

<li class="name">
    <strong>
        <a itemprop="url" href="<?php echo home_url('/'); // XSS ok. ?>">
            <span itemprop="name"><?php $this->get_company_name(true); ?></span>
        </a>
    </strong>
</li>
<div class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
    <li itemprop="streetAddress">
        <?php echo esc_textarea($this->args['street_address']); ?>
    </li>
    <li>
        <span itemprop="postalCode"><?php echo esc_textarea($this->args['postal_code']) . ' ';?></span>
        <span itemprop="addressLocality"><?php echo esc_textarea($this->args['locality']); ?></span>
    </li>

    <?php if ($this->args['region']) : ?>
        <li itemprop="addressRegion"><?php echo esc_textarea($this->args['region']); ?></li>
    <?php endif; ?>

    <?php if ($this->args['country']) : ?>
        <li itemprop="addressCountry"><?php echo esc_textarea($this->args['country']); ?></li>
    <?php endif; ?>

</div>

<?php

$this->get_itemprop_contacts(true);

$this->get_itemprop_sameas(true);

?>
</ul>
