<?php

namespace app\tests\unit\models;

use app\tests\fixtures\User as UserFixture;
use app\tests\fixtures\UserProfile as UserProfileFixture;

class UserProfileTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => UserFixture::className(),
             'profile' => UserProfileFixture::className(),
        ]);
    }

    public function testGetUser()
    {
        $user = $this->tester->grabFixture('user', 'user-2');
        expect($user->profile->user)->isInstanceOf('app\models\User');
    }

    public function testGetFiles()
    {
        $user = $this->tester->grabFixture('user', 'user-2');
        $files = $user->profile->files;
        expect_that(is_array($files));
    }

    public function testUpdate()
    {
        $user = $this->tester->grabFixture('user', 'user-2');
        $user->profile->full_name = 'Test';
        $user->profile->birth_day = '2001-01-02';
        expect_that($user->save());

        $user = $this->tester->grabFixture('user', 'user-2');
        expect($user)->isInstanceOf('app\models\User');
        expect($user->profile->full_name)->equals('Test');
        expect($user->profile->birth_day)->equals('2001-01-02');
    }
}
