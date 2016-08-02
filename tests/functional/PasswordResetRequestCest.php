<?php

namespace app\tests\functional;

use app\tests\fixtures\User as UserFixture;

class PasswordResetRequestCest
{
    protected $formId = '#request-password-reset-form';

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
            'PasswordResetRequestForm[email]' => 'test_email',
        ]);
        $I->see('Email is not a valid email address', '.help-block-error');
    }

    public function testNonExistEmail($I)
    {
        $I->submitForm($this->formId, [
            'PasswordResetRequestForm[email]' => 'test@test.com',
        ]);
        $I->see('There is no user with such email', '.help-block-error');
    }

    public function testBlockedUser($I)
    {
        $I->submitForm($this->formId, [
            'PasswordResetRequestForm[email]' => 'user-blocked@example.com',
        ]);
        $I->see('There is no user with such email', '.help-block-error');
    }

    public function testDeletedUser($I)
    {
        $I->submitForm($this->formId, [
            'PasswordResetRequestForm[email]' => 'user-deleted@example.com',
        ]);
        $I->see('There is no user with such email', '.help-block-error');
    }

    public function testSuccess($I)
    {
        $I->submitForm($this->formId, [
            'PasswordResetRequestForm[email]' => 'user-2@example.com',
        ]);
        $I->see('We\'ve sent you an email with instructions to reset your password');
    }
}
