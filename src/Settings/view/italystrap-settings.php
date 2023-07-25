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
            <?php do_action('italystrap_before_settings_form', $this); ?>
            <form action="options.php" method="post" id="italystrap_options">
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
                <img class="loading-gif" src="<?php echo includes_url(); ?>images/spinner.gif">
                <div id="saveResult"></div>
            </form>
            <?php do_action('italystrap_after_settings_form', $this); ?>
        </div>
    </div>
</div>
<div class="clear"></div>
<?php
/**
 * https://www.wpoptimus.com/434/save-plugin-theme-setting-options-ajax-wordpress/
 */
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#italystrap_options').submit(function() {
            jQuery( '.saveResult' ).empty();
            jQuery( '.loading-gif' ).fadeIn();
            jQuery(this).ajaxSubmit({
                success: function(){
                    jQuery('#saveResult').html("<div id='saveMessage' class='successModal'></div>");
                    jQuery('#saveMessage').append("<div class=\"updated\"><p><?php echo htmlentities(__('Settings Saved Successfully', 'wp'), ENT_QUOTES); ?></p></div>").show();
                    jQuery( '.loading-gif' ).fadeOut();
                }, 
                timeout: 5000
            }); 
            setTimeout("jQuery('#saveMessage').hide('slow');", 5000);
            return false; 
        });
    });
</script>
<?php do_action('italystrap_after_settings_page', $this); ?>
