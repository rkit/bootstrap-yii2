<?php

namespace app\tests\unit\models;

use app\tests\fixtures\UserFixture;

class UserTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => UserFixture::class,
        ]);
    }

    public function testChangePassword()
    {
        $user = $this->tester->grabFixture('user', 'user-2');
        expect_that($user->validatePassword('123123'));

        $user->passwordNew = 'test_new_password';

        expect_that($user->save());
        expect_that($user->validatePassword('test_new_password'));
    }
}
