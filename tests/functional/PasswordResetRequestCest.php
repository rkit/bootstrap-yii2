<?php

namespace app\tests\functional;

use Yii;
use app\tests\fixtures\User as UserFixture;

class PasswordResetRequestCest
{
    protected $formName = 'PasswordResetRequestForm';
    protected $formId = '#request-password-reset-form';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->amOnRoute('/index/request-password-reset');
        $I->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
        ]);
    }

    public function openPasswordResetRequestPage($I)
    {
        $I->see('Request password reset');
        $I->see('Please fill out your email');
    }

    public function testEmptyFields($I)
    {
        $I->submitForm($this->formId, []);
        $I->see('Email cannot be blank', '.help-block-error');
    }

    public function testWrongEmail($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'test_email',
        ]);
        $I->see('Email is not a valid email address', '.help-block-error');
    }

    public function testNonExistEmail($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'test@test.com',
        ]);
        $I->see('There is no user with such email', '.help-block-error');
    }

    public function testBlockedUser($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'user-3@example.com',
        ]);
        $I->see('There is no user with such email', '.help-block-error');
    }

    public function testDeletedUser($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'user-4@example.com',
        ]);
        $I->see('There is no user with such email', '.help-block-error');
    }

    public function testFailEmail($I)
    {
        Yii::$app->settings->emailMain = null;

        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'user-2@example.com',
        ]);

        $I->see('An error occurred while sending a message to reset your password');
    }

    public function testSuccess($I)
    {
        Yii::$app->settings->emailName = 'admin';
        Yii::$app->settings->emailMain = 'admin@test.com';

        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'user-2@example.com',
        ]);
        $I->see('We\'ve sent you an email with instructions to reset your password');
    }
}
