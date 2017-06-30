<?php

namespace app\tests\functional;

use app\tests\fixtures\UserFixture;
use app\tests\fixtures\UserProfileFixture;

class SignupCest
{
    protected $formName = 'SignupForm';
    protected $formId = '#form-signup';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->amOnRoute('/index/signup');
        $I->haveFixtures([
             'user' => UserFixture::class,
             'profile' => UserProfileFixture::class,
        ]);
    }

    public function openPage($I)
    {
        $I->see('Signup');
    }

    public function testEmptyFields($I)
    {
        $I->submitForm($this->formId, []);
        $I->expectTo('see validations errors');
        $I->see('Full Name cannot be blank', '.help-block-error');
        $I->see('Email cannot be blank', '.help-block-error');
        $I->see('Password cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testEmptyEmail($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[fullName]' => 'Test',
            $this->formName . '[email]' => '',
            $this->formName . '[password]' => 'fghfgh',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Email cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongFormatEmail($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[fullName]' => 'Test',
            $this->formName . '[email]' => 'test_email',
            $this->formName . '[password]' => 'test_password',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Email is not a valid email address', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testEmptyPassword($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[fullName]' => 'Test',
            $this->formName . '[email]' => 'test@example.com',
            $this->formName . '[password]' => '',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Password cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testEmptyName($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[fullName]' => '',
            $this->formName . '[email]' => 'test@example.com',
            $this->formName . '[password]' => 'fghfgh',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Full Name cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testTooShortPassword($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[fullName]' => 'Test',
            $this->formName . '[email]' => 'test@example.com',
            $this->formName . '[password]' => 'fgh',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Password should contain at least', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testExistEmail($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[fullName]' => 'Test',
            $this->formName . '[email]' => 'user-2@example.com',
            $this->formName . '[password]' => 'fghfgh',
        ]);
        $I->expectTo('see validations errors');
        $I->see('This email address has already been taken', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testSuccess($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[fullName]' => 'Test',
            $this->formName . '[email]' => 'test@example.com',
            $this->formName . '[password]' => 'fghfgh',
        ]);
        $I->amOnRoute('/');
        $I->see('Activate Your Account');
        $I->dontSee('signup');
        $I->dontSeeElement($this->formId);
    }

    public function testConfirmEmailEmptyToken($I)
    {
        $I->amOnRoute('/index/confirm-email', ['token' => '']);
        $I->see('Invalid token for activate account');
    }

    public function testConfirmEmailWrongToken($I)
    {
        $I->amOnRoute('/index/confirm-email', ['token' => 'qwe']);
        $I->see('Invalid token for activate account');
    }

    public function testConfirmRequest($I)
    {
        $I->amLoggedInAs(2);

        $I->amOnRoute('/index/confirm-request');
        $I->see('A letter for activation was sent to');

        // re send
        $I->amOnRoute('/index/confirm-request');
        $I->see('A letter for activation was sent to');
    }

    public function testSignupAndConfirm($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[fullName]' => 'Test',
            $this->formName . '[email]' => 'test@example.com',
            $this->formName . '[password]' => 'fghfgh',
        ]);
        $user = $I->grabRecord('app\models\User', ['email' => 'test@example.com']);
        $I->amOnRoute('/index/confirm-email', ['token' => $user->email_confirm_token]);
        $I->see('Your account is successfully activated');

        $I->amOnRoute('/index/confirm-request');
        $I->seeResponseCodeIs(403);
    }
}
