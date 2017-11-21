<?php

namespace app\tests\unit\admin\printers;

use app\modules\admin\printers\FileRulesTextPrinter;

class FileRulesTextPrinterTest extends \Codeception\Test\Unit
{
    public function testAllRules()
    {
        $rules = [
            'imageSize' => ['minWidth' => 300, 'minHeight' => 300],
            'mimeTypes' => ['image/png', 'image/jpg', 'image/jpeg'],
            'extensions' => ['jpg', 'jpeg', 'png'],
            'maxSize' => 1024 * 1024 * 1,
            'tooBig' => 'File size must not exceed 1Mb',
        ];

        $text = (new FileRulesTextPrinter($rules))->__toString();

        expect($text)->contains('Min. size of image: 300x300px');
        expect($text)->contains('Max. file size: 1');
        expect($text)->contains('File types: JPG, JPEG, PNG');
    }

    public function testWithoutImageSize()
    {
        $rules = [
            'mimeTypes' => ['image/png', 'image/jpg', 'image/jpeg'],
            'extensions' => ['jpg', 'jpeg', 'png'],
            'maxSize' => 1024 * 1024 * 1,
            'tooBig' => 'File size must not exceed 1Mb',
        ];

        $text = (new FileRulesTextPrinter($rules))->__toString();

        expect($text)->contains('Max. file size: 1');
        expect($text)->contains('File types: JPG, JPEG, PNG');
    }


    public function testImageWithStrictSize()
    {
        $rules = [
            'imageSize' => [
                'maxWidth'  => 300,
                'maxHeight' => 300,
                'minWidth'  => 300,
                'minHeight' => 300
            ],
        ];

        $text = (new FileRulesTextPrinter($rules))->__toString();

        expect($text)->equals('Image size: 300x300px');
    }

    public function testImageWithMinAndMaxSize()
    {
        $rules = [
            'imageSize' => [
                'maxWidth'  => 300,
                'maxHeight' => 300,
                'minWidth'  => 290,
                'minHeight' => 290
            ]
        ];

        $text = (new FileRulesTextPrinter($rules))->__toString();

        expect($text)->contains('Min. size of image: 290x290px');
        expect($text)->contains('Max. size of image: 300x300px');
    }

    public function testImageWithMinSize()
    {
        $rules = ['imageSize' => ['minWidth' => 300, 'minHeight' => 300]];
        $text = (new FileRulesTextPrinter($rules))->__toString();

        expect($text)->equals('Min. size of image: 300x300px');
    }

    public function testImageWithMaxSize()
    {
        $rules = ['imageSize' => ['maxWidth' => 300, 'maxHeight' => 300]];
        $text = (new FileRulesTextPrinter($rules))->__toString();

        expect($text)->equals('Max. size of image: 300x300px');
    }

    public function testImageWithOnlyMaxWidth()
    {
        $rules = ['imageSize' => ['maxWidth' => 300]];
        $text = (new FileRulesTextPrinter($rules))->__toString();

        expect($text)->equals('Max. width 300px');
    }

    public function testImageWithOnlyMaxHeight()
    {
        $rules = ['imageSize' => ['maxHeight' => 300]];
        $text = (new FileRulesTextPrinter($rules))->__toString();

        expect($text)->equals('Max. height 300px');
    }

    public function testImageWithOnlyMinWidth()
    {
        $rules = ['imageSize' => ['minWidth' => 300]];
        $text = (new FileRulesTextPrinter($rules))->__toString();

        expect($text)->equals('Min. width 300px');
    }

    public function testImageWithOnlyMinHeight()
    {
        $rules = ['imageSize' => ['minHeight' => 300]];
        $text = (new FileRulesTextPrinter($rules))->__toString();

        expect($text)->equals('Min. height 300px');
    }
}
