<?php

namespace app\tests\functional;

use app\tests\fixtures\User as UserFixture;
use app\models\User;

class ResetPasswordCest
{
    protected $formName = 'ResetPasswordForm';
    protected $formId = '#reset-password-form';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
        ]);
        $user = User::findByEmail('user-2@example.com');
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

    public function testSuccess($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[password]' => 'fghfgh',
        ]);
        $I->see('New password was saved');
    }
}
