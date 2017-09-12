<?php

namespace app\tests\unit\models\forms;

use app\tests\fixtures\UserFixture;
use app\models\entity\User;
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

        $form = new ResetPasswordForm($user->password_reset_token);
        $form->password = '';
        expect_not($form->validate());
    }

    public function testTooShortPassword()
    {
        $user = $this->tester->grabFixture('user', 'user-1');

        $form = new ResetPasswordForm($user->password_reset_token);
        $form->password = 'qwe';
        expect_not($form->validate());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid link for reset password
     */
    public function testWrongToken()
    {
        $form = new ResetPasswordForm('notexistingtoken_1391882543');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid link for reset password
     */
    public function testEmptyToken()
    {
        $form = new ResetPasswordForm('');
    }

    public function testSuccess()
    {
        $user = $this->tester->grabFixture('user', 'user-1');

        $form = new ResetPasswordForm($user->password_reset_token);
        $form->password = 'password-new';
        $form->resetPassword();

        $user = User::find()->email($user->email)->one();
        expect($user->password_reset_token)->isEmpty();
        expect_that($user->validatePassword('password-new'));
    }
}
