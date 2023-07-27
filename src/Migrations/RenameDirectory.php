<?php

/**
 * Rename directory APi
 *
 * This class handle the renaming of directories
 *
 * @link www.italystrap.it
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Migrations;

/**
 * Rename_Directory Class.
 */
class RenameDirectory
{
    /**
     * Check the right permission for the directory.
     *
     * @param string $new_parent_dir The new name for the directory.
     * @param string $permissions           Permissions for the controller. Default '0755'.
     *
     * @return bool                          Retur true if the permissions are 755
     */
    private function isPermissionRight(string $new_parent_dir, string $permissions = '0755'): bool
    {
        return $permissions === substr(sprintf('%o', fileperms($new_parent_dir)), - 4);
    }

    /**
     * Rename the directory
     *
     * @param string $old_parent_dir The name and path of the old directory.
     * @param string $new_parent_dir The name and path of the new directory.
     *
     * @return bool                          Return true if success.
     */
    public function rename(string $old_parent_dir, string $new_parent_dir)
    {

        // if ( ! is_dir( $old_parent_dir ) ) {
        //  mkdir( $old_parent_dir, 0755 );
        // }

        if (is_dir($new_parent_dir)) {
            echo "The parent already renamed!";
            return;
        }

        if (! is_writable($old_parent_dir)) {
            throw new ErrorException('La cartella non è scrivibile.');
        }

        $renamed = rename($old_parent_dir, $new_parent_dir);

        if (! $this->isPermissionRight($new_parent_dir)) {
            chmod($new_parent_dir, 0755);
        }

        return $renamed;
    }
}
