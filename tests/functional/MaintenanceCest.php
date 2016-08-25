<?php

namespace app\tests\functional;

use Yii;

class MaintenanceCest
{
    public function testNotEnabled($I)
    {
        $I->amOnRoute('/index/maintenance');
        $I->see('Page not found');
    }

    public function testOn($I)
    {
        Yii::$app->catchAll = ['/index/maintenance'];

        $I->amOnRoute('/index');
        $I->see('Sorry, there are works, we will be back soon');
    }

    public function testOff($I)
    {
        Yii::$app->catchAll = null;

        $I->amOnRoute('/index');
        $I->dontSee('Sorry, there are works, we will be back soon');
    }
}
