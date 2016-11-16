<?php

namespace app\tests\functional;

use app\tests\fixtures\User as UserFixture;

class ResetPasswordCest
{
    protected $formName = 'ResetPasswordForm';
    protected $formId = '#reset-password-form';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'user' => UserFixture::className(),
        ]);
        $user = $I->grabFixture('user', 'user-2');
        $I->amOnRoute('/index/reset-password', ['token' => $user->password_reset_token]);
    }

    public function openResetPasswordPage($I)
    {
        $I->see('Reset password');
        $I->see('Please choose your new password');
    }

    public function testEmptyFields($I)
    {
        $I->submitForm($this->formId, []);
        $I->see('Password cannot be blank', '.help-block-error');
    }

    public function testTooShortPassword($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[password]' => 'test',
        ]);
        $I->see('Password should contain at least', '.help-block-error');
    }

    public function testEmptyToken($I)
    {
        $I->amOnRoute('/index/reset-password', ['token' => '']);
        $I->see('Invalid link for reset password');
    }

    public function testWrongToken($I)
    {
        $I->amOnRoute('/index/reset-password', ['token' => 'test']);
        $I->see('Invalid link for reset password');
    }

    public function testSuccess($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[password]' => 'fghfgh',
        ]);
        $I->see('New password was saved');
    }
}
