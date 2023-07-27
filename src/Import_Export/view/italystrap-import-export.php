<div class="wrap">
    <div class="metabox-holder">
        <div class="postbox">
            <h3 class="hndle">
                <span>
                    <?php echo $this->i18n['export']['title']; // XSS ok.?>
                </span>
            </h3>
            <div class="inside">
                <form method="post">
                    <?php
                    $this->do_fields('export_settings');
                    wp_nonce_field($this->args['name_action'], $this->args[ "export_nonce" ]);
                    submit_button(__('Export'), 'secondary', 'submit', false);
                    ?>
                </form>
            </div>
        </div>

        <div class="postbox">
            <h3 class="hndle">
                <span>
                    <?php echo $this->i18n['import']['title']; // XSS ok.?>
                </span>
            </h3>
            <div class="inside">
                <form method="post" enctype="multipart/form-data">
                    <?php
                    $this->do_fields('import_file');
                    $this->do_fields('import_settings');
                    wp_nonce_field($this->args['name_action'], $this->args[ "import_nonce" ]);
                    submit_button(__('Import'), 'secondary', 'submit', false); ?>
                </form>
            </div>
        </div>
    </div>
</div>
