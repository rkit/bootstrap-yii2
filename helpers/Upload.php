<?php

namespace app\helpers;

use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class Upload
{
    /**
     * Create manually UploadedFile instance by file path
     *
     * @param string $path file path
     * @return UploadedFile
     */
    public static function makeUploadedFile($path)
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'app');
        file_put_contents($tmpFile, file_get_contents($path));

        $uploadedFile = new UploadedFile();
        $uploadedFile->name = pathinfo($path, PATHINFO_BASENAME);
        $uploadedFile->tempName = $tmpFile;
        $uploadedFile->type = FileHelper::getMimeType($tmpFile);
        $uploadedFile->size = filesize($tmpFile);
        $uploadedFile->error = 0;

        return $uploadedFile;
    }
}
