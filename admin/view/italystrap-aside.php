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