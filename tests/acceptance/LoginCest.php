<?php

namespace app\tests\acceptance;

use yii\helpers\Url as Url;

class LoginCest
{
    public function ensureThatLoginWorks($I)
    {
        $I->amOnPage(Url::toRoute('/index/login'));
        $I->see('Login');
        $I->amGoingTo('try to login with correct fields');
        $I->fillField('input[name="LoginForm[email]"]', 'user-2@example.com');
        $I->fillField('input[name="LoginForm[password]"]', '123123');
        $I->click('login-button');
        $I->wait(2);
        $I->expectTo('see user info');
        $I->see('Logout');
    }
}
