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

namespace ItalyStrap\Facebook;

/**
 * Class description
 */
class Pixel
{
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
    function __construct($argument = null)
    {
        // Code...
    }

    /**
     * Render
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function render()
    {

        ?>
$nome_pagina = null;
        $nome_pagina = null;
        <!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq( 'init', 'xxxxxxxxxxxxxxx' );
        <?php if ($nome_pagina == "conversione.php") : ?>
fbq( 'track', 'Purchase', {value: '10.00', currency:'EUR'} );
        <?php else : ?>
fbq( 'track', "PageView" );
        <?php endif; ?>
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=xxxxxxxxxxxxxxx&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', 'xxxxxxxxxxxxxxxxxx');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=xxxxxxxxxxxxxxxxxx&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<!-- Standard Events -->
<!-- Add standard events to your code to track specific types of actions on your website. Copy the code for the type of event you want to measure, and paste it below the pixel code on the relevant page within a -->
</script>
<!-- tag. Learn more about conversion tracking.

ViewContent
Track key page views (ex: product page, landing page or article)
fbq('track', 'ViewContent');


Search
Track searches on your website (ex. product searches)
fbq('track', 'Search');


AddToCart
Track when items are added to a shopping cart (ex. click/landing page on Add to Cart button)
fbq('track', 'AddToCart');


AddToWishlist
Track when items are added to a wishlist (ex. click/landing page on Add to Wishlist button)
fbq('track', 'AddToWishlist');


InitiateCheckout
Track when people enter the checkout flow (ex. click/landing page on checkout button)
fbq('track', 'InitiateCheckout');


AddPaymentInfo
Track when payment information is added in the checkout flow (ex. click/landing page on billing info)
fbq('track', 'AddPaymentInfo');


Purchase
Track purchases or checkout flow completions (ex. landing on "Thank You" or confirmation page)
fbq('track', 'Purchase', {value: '1.00', currency: 'USD'});


Lead
Track when a user expresses interest in your offering (ex. form submission, sign up for trial, landing on pricing page)
fbq('track', 'Lead');


CompleteRegistration
Track when a registration form is completed (ex. complete subscription, sign up for a service)
fbq('track', 'CompleteRegistration'); -->

        <?php
    }
}
