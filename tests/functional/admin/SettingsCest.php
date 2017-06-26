<?php

namespace app\tests\functional\admin;

use app\tests\fixtures\UserFixture;

class SettingsCest
{
    protected $url = '/admin/settings';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'user' => UserFixture::class,
        ]);
        $I->amLoggedInAs($I->grabFixture('user', 'user-1'));
        $I->amOnRoute($this->url);
    }

    public function openIndexPage($I)
    {
        $I->seeResponseCodeIs(200);
    }
}
