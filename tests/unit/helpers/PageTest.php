<?php

namespace app\tests\unit\helpers;

use app\helpers\Page;

class PageTest extends \Codeception\Test\Unit
{
    public function testTitle()
    {
        $title = Page::title('test1', 'test2');

        expect($title)->notEmpty();
        expect($title)->equals('<title>test1 / test2</title>');
    }

    public function testTitleWithOnlyPrefix()
    {
        $title = Page::title(null, 'test2');

        expect($title)->notEmpty();
        expect($title)->equals('<title>test2</title>');
    }
}
