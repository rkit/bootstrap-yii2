<?php

namespace app\tests\unit\helpers;

use Yii;
use yii\helpers\FileHelper;
use app\helpers\Upload;

class UploadTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        FileHelper::removeDirectory(Yii::getAlias('@tests/_tmp/files'));
        FileHelper::copyDirectory(
            Yii::getAlias('@tests/_data/files'),
            Yii::getAlias('@tests/_tmp/files')
        );
    }

    public function testMakeUploadedFile()
    {
        $path = Yii::getAlias('@tests/_data/files/300x300.png');
        $uploadedFile = Upload::makeUploadedFile($path);

        expect($uploadedFile->name)->equals('300x300.png');
        expect($uploadedFile->tempName)->notEmpty();
        expect($uploadedFile->type)->notEmpty('image/png');
        expect($uploadedFile->size)->equals(filesize($path));
        expect($uploadedFile->error)->equals(0);
    }
}
