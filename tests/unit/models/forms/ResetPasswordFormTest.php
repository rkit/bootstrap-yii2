<?php

namespace app\tests\unit\models\forms;

use app\tests\fixtures\User as UserFixture;
use app\models\User;
use app\models\forms\ResetPasswordForm;

class ResetPasswordFormTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => UserFixture::class,
        ]);
    }

    public function testEmptyPassword()
    {
        $user = $this->tester->grabFixture('user', 'user-1');

        $form = new ResetPasswordForm();
        $form->password = '';
        expect_that($form->validateToken($user->password_reset_token));
        expect_not($form->validate());
    }

    public function testTooShortPassword()
    {
        $user = $this->tester->grabFixture('user', 'user-1');

        $form = new ResetPasswordForm();
        $form->password = 'qwe';
        expect_that($form->validateToken($user->password_reset_token));
        expect_not($form->validate());
    }

    public function testWrongToken()
    {
        $form = new ResetPasswordForm();
        expect_not($form->validateToken('notexistingtoken_1391882543'));
    }

    public function testEmptyToken()
    {
        $form = new ResetPasswordForm();
        expect_not($form->validateToken(''));
    }

    public function testSuccess()
    {
        $user = $this->tester->grabFixture('user', 'user-1');

        $form = new ResetPasswordForm();
        $form->password = 'password-new';
        expect_that($form->validateToken($user->password_reset_token));
        expect_that($form->resetPassword());

        $user = User::findByEmail($user->email);
        expect($user->password_reset_token)->isEmpty();
        expect_that($user->validatePassword('password-new'));
    }
}
