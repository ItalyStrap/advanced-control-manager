<?php

declare(strict_types=1);

namespace ItalyStrap\Migrations;

class ParentThemeMigrations
{
    private ?RenameDirectory $rename = null;
    private ?Update_File $update = null;

    public function __construct(RenameDirectory $rename, Update_File $update)
    {
        $this->rename = $rename;
        $this->update = $update;
    }

    public function run()
    {

        printf(
            '<h2>%s</h2><p>%s</p><p>Option template name: %s</p><p>Option stylesheet name: %s</p>',
            __('Migration page for ItalyStrap theme framework', 'italystrap'),
            __('Before upgrading to new version of the ItalyStrap theme framework (4.x) you need to migrate the old settings and rename the old directory of the theme to the new format "italystrap", update the style.css in your child theme to point to the correct parent directory and update the option "template" to the new format name.<br>To do so you can use the below migrate button.<br>Make a backup of all files and database before migration and than click on "Migrate" button, this will run the migration functionality.', 'italystrap'),
            esc_attr(get_option('template')),
            esc_attr(get_option('stylesheet'))
        );

        echo "<h3>Directory founded</h3>";

        foreach (search_theme_directories() as $key => $value) {
            echo "<pre>";
            print_r($key);
            echo "</pre>";
        }

        if (! isset($_POST['submit'])) {
            return null;
        }

        $this->convert_data();
        $this->upgrade_parent();
    }

    /**
     * Convert data.
     */
    public function convert_data()
    {

        $settings_converter = new SettingsMigration();


        $old_data = (array) get_option('italystrap_theme_settings');
        $pattern = [
            // 'old'    => 'new',
            'default_404'   => 'default_404',
            'default_image' => 'default_image',
            'logo'          => 'logo',
        ];
        $settings_converter->dataToThemeMod($pattern, $old_data);


        $pattern = ['favicon'       => 'site_icon'];
        $settings_converter->dataToOption($pattern, $old_data);


        $pattern = ['analytics'     => 'google_analytics_id'];
        $new_options = get_option('italystrap_settings');

        $settings_converter->dataToOptions($pattern, $old_data, $new_options, 'italystrap_settings');


        $old_data = (array) get_theme_mods();
        $old_data['display_navbar_logo_image'] = 'display_image';
        $pattern = [
            // 'old'    => 'new',
            'display_navbar_logo_image' => 'display_navbar_brand',
        ];
        $settings_converter->dataToThemeMod($pattern, $old_data);
    }

    /**
     * Upgrade for new parent version 4.0.0
     */
    public function upgrade_parent()
    {

        $old_name = 'ItalyStrap';
        $new_name = 'italystrap';

        // $old_name = 'roots';
        // $new_name = 'ROOTS';

        // $old_name = 'ROOTS';
        // $new_name = 'roots';

        // $old_name = 'ROOTS';
        // $new_name = 'antani';

        // $old_name = 'antani';
        // $new_name = 'ROOTS';

        $template_path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR;
        $old_parent_dir = $template_path . $old_name;
        $new_parent_dir = $template_path . $new_name;

        /**
         * Rename the old parent directory
         */
        $this->rename->rename($old_parent_dir, $new_parent_dir);

        /**
         * Then update option 'template'
         */
        if ($old_name === get_option('template')) {
            update_option('template', $new_name);
        }

        /**
         * Then update option 'stylesheet' only if is installed only the parent.
         */
        if ($old_name === get_option('stylesheet')) {
            update_option('stylesheet', $new_name);
        }

        /**
         * Updates the stylesheet only in child theme.
         */
        if ($new_name !== get_option('stylesheet')) {
            $child_path = $template_path . get_option('stylesheet');

            /**
             * Change the template string in child with the new parent dir name.
             */
            $data = $this->update->getContentFile($child_path . '/style.css');
            $data = $this->update->replaceContentFile($old_name, $new_name, $data);
            $this->update->putContentFile($child_path . '/style.css', $data);
        }

        printf(
            '<div class="%1$s"><p>Option "template": <strong>%2$s</strong></p><p>Options "stylesheet": <strong>%3$s</strong></p><p>The parent of <strong>%4$s</strong> now is <strong>%5$s</strong></p></div>',
            'notice notice-success',
            get_option('template'),
            get_option('stylesheet'),
            // wp_get_theme()->display( 'Theme Name' ),
            get_option('stylesheet'),
            wp_get_theme()->display('Template')
        );
    }
}
