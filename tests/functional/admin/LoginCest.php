<?php

namespace app\tests\functional\admin;

use yii\web\ForbiddenHttpException;
use app\tests\fixtures\User as UserFixture;
use app\tests\fixtures\AuthItem as AuthItemFixture;
use app\tests\fixtures\AuthItemChild as AuthItemChildFixture;
use app\tests\fixtures\AuthAssignment as  AuthAssignmentFixture;
use app\models\User;

class LoginCest
{
    protected $formId = '#login-form';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->amOnRoute('/admin');
        $I->haveFixtures([
             'authItem' => [
                 'class' => AuthItemFixture::className(),
                 'dataFile' => codecept_data_dir() . 'auth_item.php',
             ],
             'authAssignment' => [
                 'class' => AuthAssignmentFixture::className(),
                 'dataFile' => codecept_data_dir() . 'auth_assignment.php',
             ],
             'authItemChild' => [
                 'class' => AuthItemChildFixture::className(),
                 'dataFile' => codecept_data_dir() . 'auth_item_child.php',
             ],
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

    public function testEmptyUsername($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[password]' => 'test_password'
        ]);
        $I->expectTo('see validations errors');
        $I->see('Username cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testEmptyPassword($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[username]' => 'test_username'
        ]);
        $I->expectTo('see validations errors');
        $I->see('Password cannot be blank', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testTooShortPassword($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[username]' => 'test_username',
            'LoginForm[password]' => 'test',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect username or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testBlockedUser($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[username]' => 'user-3',
            'LoginForm[password]' => '123123',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Your account has been suspended', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testDeletedUser($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[username]' => 'user-4',
            'LoginForm[password]' => '123123',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Your account has been deleted', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongFields($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[username]' => 'test@test.com',
            'LoginForm[password]' => 'testpassword',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect username or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongUsername($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[username]' => 'test_username',
            'LoginForm[password]' => 'fghfgh',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect username or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testWrongPassword($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[username]' => 'superuser',
            'LoginForm[password]' => 'test_password',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect username or password', '.help-block-error');
        $I->dontSee('logout');
    }

    public function testSuccessForEditorNews($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[username]' => 'user-5',
            'LoginForm[password]' => '123123',
        ]);

        $I->amOnRoute('/admin/news');
        $I->seeResponseCodeIs(200);

        $I->amOnRoute('/admin/users');
        $I->seeResponseCodeIs(403);

        $I->amOnRoute('/admin');
        $I->see('News', '#menu');
        $I->seeNumberOfElements('#menu li', 1);
    }

    public function testSuccess($I)
    {
        $I->submitForm($this->formId, [
            'LoginForm[username]' => 'superuser',
            'LoginForm[password]' => 'fghfgh',
        ]);
        $I->see('Exit');
        $I->see('Welcome! / Control Panel');
        $I->dontSee('login');
        $I->dontSeeElement($this->formId);
    }

    public function testEnterToPageIndexWithNoPermission($I)
    {
        try {
            $I->amLoggedInAs(User::findByUsername('user-2'));
            $I->amOnRoute('/admin');
        } catch (ForbiddenHttpException $Exception) {
        }
    }

    public function testEnterToPageNewsWithNoPermission($I)
    {
        try {
            $I->amLoggedInAs(User::findByUsername('user-2'));
            $I->amOnRoute('/admin/news');
        } catch (ForbiddenHttpException $Exception) {
        }
    }
}
