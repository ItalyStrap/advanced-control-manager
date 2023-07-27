<?php

declare(strict_types=1);

namespace ItalyStrap\Migrations;

class Update_File
{
    public function getContentFile(string $path): string
    {
        return file_get_contents($path);
    }

    public function putContentFile(string $path, $data)
    {
        return file_put_contents($path, $data);
    }

    public function replaceContentFile($old_string, $new_string, $data)
    {
        return \str_replace($old_string, $new_string, $data);
    }
}
