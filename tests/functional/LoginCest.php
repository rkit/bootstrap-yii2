<?php

namespace app\tests\functional;

use app\tests\fixtures\User as UserFixture;

class LoginCest
{
    protected $formId = '#login-form';

    public function _before($I)
    {
        $I->amOnRoute('/index/login');
        $I->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
        ]);
    }

    public function openLoginPage($I)
    {
        $I->see('Login');
    }

    public function testEmptyFields($I)
    {
        $I->submitForm($this->formId, []);
        $I->expectTo('see validations errors');
        $I->see('Password cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testEmptyEmail($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[password]' => 'test_password'
        ]);
        $I->expectTo('see validations errors');
        $I->see('Email cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testEmptyPassword($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[email]' => 'test@test.com'
        ]);
        $I->expectTo('see validations errors');
        $I->see('Password cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testTooShortPassword($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[email]' => 'test@test.com',
            'LoginForm[password]' => 'test',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testBlockedUser($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[email]' => 'user-blocked@example.com',
            'LoginForm[password]' => '123123',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Your account has been suspended', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testDeletedUser($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[email]' => 'user-deleted@example.com',
            'LoginForm[password]' => '123123',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Your account has been deleted', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongFields($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[email]' => 'test@test.com',
            'LoginForm[password]' => 'test_password',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongEmail($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[email]' => 'test@test.com',
            'LoginForm[password]' => '123123',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongPassword($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[email]' => 'user-2@example.com',
            'LoginForm[password]' => 'test_password',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongFormatEmail($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[email]' => 'test_email',
            'LoginForm[password]' => 'test_password',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Email is not a valid email address', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testSuccess($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[email]' => 'user-2@example.com',
            'LoginForm[password]' => '123123',
        ]);
        $I->amOnRoute('/');
        $I->see('Logout');
        $I->dontSee('login');
        $I->dontSeeElement($this->formId);
    }

    public function testInternalSuccess($I)
    {
        $I->amLoggedInAs(1);
        $I->amOnRoute('/');
        $I->see('Logout');
    }
}
