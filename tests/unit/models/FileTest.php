<?php

namespace app\tests\unit\models;

use app\models\File;

class FileTest extends \Codeception\Test\Unit
{
    public function testGenerateName()
    {
        $file = new File();
        $file->generateName('png');

        $pathinfo = pathinfo($file->name);

        expect($pathinfo)->hasKey('basename');
        expect($pathinfo)->hasKey('filename');
        expect($pathinfo)->hasKey('extension');
        expect($pathinfo['extension'])->equals('png');
    }
}
