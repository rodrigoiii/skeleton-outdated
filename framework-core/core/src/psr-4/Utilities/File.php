<?php

namespace Framework\Utilities;

class File
{
    public static function upload($file, $path)
    {
        $type = explode("/", $file->getClientMediaType());
        $extension = $type[1];
        $hash_filename = sha1($file->getClientFilename() . uniqid()) . "." . $extension;

        $file->moveTo("{$path}/$hash_filename");
        return "{$path}/$hash_filename";
    }
}
