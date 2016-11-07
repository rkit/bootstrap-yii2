<?php

namespace app\tests\functional;

use yii\helpers\Url;
use app\tests\fixtures\User as UserFixture;
use app\models\User;

class LoginCest
{
    protected $formName = 'LoginForm';
    protected $formId = '#login-form';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->amOnRoute('/index/login');
        $I->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/user.php',
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
            $this->formName . '[password]' => 'test_password'
        ]);
        $I->expectTo('see validations errors');
        $I->see('Email cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testEmptyPassword($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'test@test.com'
        ]);
        $I->expectTo('see validations errors');
        $I->see('Password cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testTooShortPassword($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'test@test.com',
            $this->formName . '[password]' => 'test',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testBlockedUser($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'user-3@example.com',
            $this->formName . '[password]' => '123123',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Your account has been suspended', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testDeletedUser($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'user-4@example.com',
            $this->formName . '[password]' => '123123',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Your account has been deleted', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongFields($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'test@test.com',
            $this->formName . '[password]' => 'test_password',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongEmail($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'test@test.com',
            $this->formName . '[password]' => '123123',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongPassword($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'user-2@example.com',
            $this->formName . '[password]' => 'test_password',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongFormatEmail($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'test_email',
            $this->formName . '[password]' => 'test_password',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Email is not a valid email address', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testSuccess($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'user-2@example.com',
            $this->formName . '[password]' => '123123',
        ]);
        $I->amOnRoute('/');
        $I->see('Logout');
        $I->dontSee('login');
        $I->dontSeeElement($this->formId);
    }

    public function testSuccessAndGoToLoginPage($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'user-2@example.com',
            $this->formName . '[password]' => '123123',
        ]);
        $I->amOnRoute('/index/login');
        $I->dontSee($this->formId);
    }

    public function testSuccessAndDisableAccount($I)
    {
        $I->submitForm($this->formId, [
            $this->formName . '[email]' => 'user-2@example.com',
            $this->formName . '[password]' => '123123',
        ]);
        $I->amOnRoute('/');
        $I->see('Logout');
        $I->dontSee('login');
        $I->dontSeeElement($this->formId);

        $user = User::findByUsername('user-2');
        $user->status = User::STATUS_BLOCKED;
        $user->save();

        $I->amOnRoute('/');
        $I->dontSee('Logout');
        $I->see('login');
    }

    public function testLogout($I)
    {
        $I->amLoggedInAs(1);
        $I->sendAjaxPostRequest(Url::toRoute('/index/logout'));

        $I->amOnRoute('/');
        $I->dontSee('Logout');
    }

    public function testInternalSuccess($I)
    {
        $I->amLoggedInAs(1);
        $I->amOnRoute('/');
        $I->see('Logout');
    }
}
