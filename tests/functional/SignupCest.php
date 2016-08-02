<?php

namespace app\tests\functional;

use app\tests\fixtures\User as UserFixture;

class SignupCest
{
    protected $formId = '#form-signup';
    protected $formName = 'SignupForm';

    public function _before($I)
    {
        $I->amOnRoute('/index/signup');
        $I->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
        ]);
    }

    public function openSignupPage($I)
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
            'SignupForm[full_name]' => 'Test',
            'SignupForm[email]' => '',
            'SignupForm[password]' => 'fghfgh',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Email cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongFormatEmail($I)
    {
        $I->submitForm($this->formId, [
            'SignupForm[full_name]' => 'Test',
            'SignupForm[email]' => 'test_email',
            'SignupForm[password]' => 'test_password',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Email is not a valid email address', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testEmptyPassword($I)
    {
        $I->submitForm($this->formId, [
            'SignupForm[full_name]' => 'Test',
            'SignupForm[email]' => 'test@test.com',
            'SignupForm[password]' => '',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Password cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testEmptyName($I)
    {
        $I->submitForm($this->formId, [
            'SignupForm[full_name]' => '',
            'SignupForm[email]' => 'test@test.com',
            'SignupForm[password]' => 'fghfgh',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Full Name cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testTooShortPassword($I)
    {
        $I->submitForm($this->formId, [
            'SignupForm[full_name]' => 'Test',
            'SignupForm[email]' => 'test@test.com',
            'SignupForm[password]' => 'fgh',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Password should contain at least', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testExistEmail($I)
    {
        $I->submitForm($this->formId, [
            'SignupForm[full_name]' => 'Test',
            'SignupForm[email]' => 'user-2@example.com',
            'SignupForm[password]' => 'fghfgh',
        ]);
        $I->expectTo('see validations errors');
        $I->see('This email address has already been taken', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testSuccess($I)
    {
        $I->submitForm($this->formId, [
            'SignupForm[full_name]' => 'Test',
            'SignupForm[email]' => 'test@test.com',
            'SignupForm[password]' => 'fghfgh',
        ]);
        $I->amOnRoute('/');
        $I->see('Activate Your Account');
        $I->dontSee('signup');
        $I->dontSeeElement($this->formId);
    }

    public function testInternalSuccess($I)
    {
        $I->amLoggedInAs(1);
        $I->amOnRoute('/');
        $I->see('Logout');
    }
}
