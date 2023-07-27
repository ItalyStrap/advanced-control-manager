<?php

/**
 * Template to display the options page.
 *
 * @package ItalyStrap\Admin
 */

?>
<div  id="tabs" class="wrap">
    <div id="post-body">
        <div class="postbox-container">
            <form action='options.php' method='post'>
                <?php
                $this->create_nav_tab();
                /**
                 * Output nonce, action, and option_page fields for a settings page.
                 */
                settings_fields($this->args[ 'options_group' ]);
                /**
                 * Output settings sections and fields
                 */
                $this->do_settings_sections($this->args[ 'options_group' ]);
                /**
                 * Output a submit button
                 */
                submit_button();
                ?>

            </form>
            <aside>
                <h3 class="hndle">
                    <span>
                        <?php esc_attr_e('italystrap', 'italystrap'); ?>
                    </span>
                </h3>
                <div class="inside">
                    <a href="http://www.italystrap.it/"><img src="<?php echo ITALYSTRAP_PLUGIN_URL; ?>admin/img/logo.jpg" alt="Logo ItalyStrap" width="296px" heght="130px" class="img-responsive center-block"></a>
                    <p><a href="http://docs.italystrap.com/" target="_blank"><?php _e('Documentation', 'italystrap'); ?></a></p>
                </div>
                <div class="inside">
                    <h4><?php _e('Difficulty level color explanation', 'italystrap'); ?></h4>
                    <p class="easy">
                        <?php
                        printf(
                            '<strong>%s</strong> %s',
                            __('Easy:', 'italystrap'),
                            __('Easy to use, no code needed.', 'italystrap')
                        ) ?>
                    </p>
                    <p class="medium">
                        <?php
                        printf(
                            '<strong>%s</strong> %s',
                            __('Medium:', 'italystrap'),
                            __('Medium difficulty, it may need to code.', 'italystrap')
                        ) ?>
                    </p>
                    <p class="hard">
                        <?php
                        printf(
                            '<strong>%s</strong> %s',
                            __('Hard:', 'italystrap'),
                            __('Hard to use, you have to add code.', 'italystrap')
                        ) ?>
                    </p>
                </div>
            </aside>
        </div>
    </div>
</div>
<div class="clear"></div>
<!-- <div class="wrap">
    <div class="metabox-holder">
        <div class="postbox">
            <h3 class="hndle"><span><?php _e('Export Settings', 'italystrap'); ?></span></h3>
            <div class="inside">
                <p><?php _e('Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'italystrap'); ?></p>
                <form method="post">
                    <p><input type="hidden" name="italystrap_action" value="export_settings" /></p>
                    <p>
                        <?php wp_nonce_field('italystrap_export_nonce', 'italystrap_export_nonce'); ?>
                        <?php submit_button(__('Export'), 'secondary', 'submit', false); ?>
                    </p>
                </form>
            </div>
        </div>

        <div class="postbox">
            <h3 class="hndle"><span><?php _e('Import Settings', 'italystrap'); ?></span></h3>
            <div class="inside">
                <p><?php _e('Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'italystrap'); ?></p>
                <form method="post" enctype="multipart/form-data">
                    <p>
                        <input type="file" name="italystrap_import_file"/>
                    </p>
                    <p>
                        <input type="hidden" name="italystrap_action" value="import_settings" />
                        <?php wp_nonce_field('italystrap_import_nonce', 'italystrap_import_nonce'); ?>
                        <?php submit_button(__('Import'), 'secondary', 'submit', false); ?>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div> -->