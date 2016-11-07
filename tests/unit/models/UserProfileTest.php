<?php

namespace app\tests\unit\models;

use app\tests\fixtures\User as UserFixture;
use app\models\User;

class UserProfileTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/user.php',
             ],
        ]);

    }

    protected function createUser()
    {
        $user = new User();
        $user->username = 'test';
        $user->email = 'test@test.com';
        $user->generateEmailConfirmToken();
        $user->setPassword('test_password');

        expect_that($user->save());
        expect($user)->isInstanceOf('app\models\User');
        expect_that($user->isActive());
        expect_that($user->validatePassword('test_password'));

        return $user;
    }

    public function testUpdate()
    {
        $user = $this->createUser();
        $user->profile->full_name = 'Test';
        $user->profile->birth_day = '2001-01-02';
        expect_that($user->save());

        $user = User::findByEmail($user->email);
        expect($user)->isInstanceOf('app\models\User');
        expect($user->profile->full_name)->equals('Test');
        expect($user->profile->birth_day)->equals('2001-01-02');
    }
}
