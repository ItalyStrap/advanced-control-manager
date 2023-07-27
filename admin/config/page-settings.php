<?php

declare(strict_types=1);

namespace ItalyStrap;

use ItalyStrap\Settings\Page as P;

return [
    P::PARENT       => 'italystrap-dashboard',
    P::PAGE_TITLE   => \__('Settings page for ACM by ItalyStrap', 'italystrap'),
    P::MENU_TITLE   => \__('Settings', 'italystrap'), // Mandatory
    P::SLUG         => 'italystrap-settings', // Mandatory
];
