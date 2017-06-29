<?php

namespace app\helpers;

class Generator
{
    /**
     * Generate a file name
     *
     * @param string $extension The file extension
     * @return string
     */
    public static function fileName(string $extension): string
    {
        $name = date('YmdHis') . substr(md5(microtime() . uniqid()), 0, 10);
        return $name . '.' . $extension;
    }
}
