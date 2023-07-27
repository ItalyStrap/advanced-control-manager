<?php

declare(strict_types=1);

use function ItalyStrap\Core\get_file_content;

?>
<div class="wrap">
    <div id="post-body">
        <div class="postbox-container">
            <div class="postbox" style="clear:both; padding: 2rem">
                <h1><?php _e('Welcome to Advanced Control Manager by ItalyStrap', 'italystrap'); ?></h1>
                <p class="about-description"><?php _e('Here you can find some link to get you started', 'italystrap'); ?></p>
                <h3>
                    <?php _e('Get Started', 'italystrap'); ?>
                </h3>
                <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=italystrap-settings')); ?>"
                   class="button button-primary button-hero load-customize hide-if-no-customize"><?php _e('Go to the settings page', 'italystrap'); ?></a>
                <h4 style="font-size:24px;"><?php _e('But first of all you can read the ', 'italystrap'); ?><a
                        href="http://docs.italystrap.com/"
                        target="_blank"><?php _e('documentation', 'italystrap'); ?></a> {Beta}</h4>
            </div>
        </div>
        <div class="postbox-container">
            <div class="postbox">
                <div style="clear:both; padding: 2rem">
                    <?php
                    $parsedown = new Parsedown();
                    $readme = get_file_content(ITALYSTRAP_PLUGIN_PATH . 'README.md');
                    echo wp_kses_post($parsedown->text($readme));
                    ?>
                </div>
            </div>
        </div>
        <div class="postbox-container">
            <div class="postbox">
                <h3 class="hndle"><span><?php _e('Support', 'italystrap'); ?></span></h3>
                <div class="inside">
                    <p><?php _e('If you have any question or need support, please use the following links:', 'italystrap'); ?></p>
                    <ul>
                        <li><a href="https://wordpress.org/support/plugin/advanced-control-manager"
                               target="_blank"><?php _e('WordPress.org support forum', 'italystrap'); ?></a></li>
                        <li><a href="
                            <?php
                            echo esc_url(
                                add_query_arg(
                                    ['utm_source' => 'plugin', 'utm_medium' => 'link', 'utm_campaign' => 'italystrap'],
                                    'https://www.italystrap.com/support/'
                                )
                            );
                            ?>
                            " target="_blank"><?php _e('ItalyStrap support forum', 'italystrap'); ?></a></li>
                        <li><a href="
                            <?php
                            echo esc_url(
                                add_query_arg(
                                    ['utm_source' => 'plugin', 'utm_medium' => 'link', 'utm_campaign' => 'italystrap'],
                                    'https://www.italystrap.com/contact/'
                                )
                            );
                            ?>
                            " target="_blank"><?php _e('Contact me', 'italystrap'); ?></a></li>
                    </ul>

                    <p><?php _e('If you like this plugin, please leave a review on', 'italystrap'); ?> <a
                            href="https://wordpress.org/support/plugin/advanced-control-manager/reviews/?filter=5"
                            target="_blank"><?php _e('WordPress.org', 'italystrap'); ?></a></p>

                </div>
            </div>
        </div>
    </div>
</div>
