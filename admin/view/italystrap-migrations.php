<?php

/**
 * Template to display the settings page.
 *
 * @package ItalyStrap\Settings
 */

?>
<?php do_action('italystrap_before_settings_page', $this); ?>
<div  id="tabs" class="wrap">
    <div id="post-body">
        <div class="postbox-container">
            <?php // do_action( 'italystrap_before_settings_form', $this ); ?>
            <form action="#" method="post">
                <?php

//                $injector = new \Auryn\Injector();
//                $migrations = $injector->make('\\' . \ItalyStrap\Migrations\ParentThemeMigrations::class);

                // $migrations = new \ItalyStrap\Migrations\ParentThemeMigrations();
//                $migrations->run();
                // $this->create_nav_tab();
                /**
                 * Output nonce, action, and option_page fields for a settings page.
                 */
                // settings_fields( $this->args[ 'options_group' ] );
                /**
                 * Output settings sections and fields
                 */
                // $this->do_settings_sections( $this->args[ 'options_group' ] );
                /**
                 * Output a submit button
                 */
                submit_button('Migrate');
                ?>
            </form>
            <?php // do_action( 'italystrap_after_settings_form', $this ); ?>
        </div>
    </div>
</div>
<div class="clear"></div>
<?php // do_action( 'italystrap_after_settings_page', $this ); ?>
