<?php

namespace app\tests\functional;

use app\tests\fixtures\User as UserFixture;

class SignupCest
{
    protected $formName = 'SignupForm';
    protected $formId = '#form-signup';

    // @codingStandardsIgnoreFile
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
            $this->formName . '[full_name]' => 'Test',
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
            $this->formName . '[full_name]' => 'Test',
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
            $this->formName . '[full_name]' => 'Test',
            $this->formName . '[email]' => 'test@test.com',
            $this->formName . '[password]' => '',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Password cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testEmptyName($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[full_name]' => '',
            $this->formName . '[email]' => 'test@test.com',
            $this->formName . '[password]' => 'fghfgh',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Full Name cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testTooShortPassword($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[full_name]' => 'Test',
            $this->formName . '[email]' => 'test@test.com',
            $this->formName . '[password]' => 'fgh',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Password should contain at least', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testExistEmail($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[full_name]' => 'Test',
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
            $this->formName . '[full_name]' => 'Test',
            $this->formName . '[email]' => 'test@test.com',
            $this->formName . '[password]' => 'fghfgh',
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
