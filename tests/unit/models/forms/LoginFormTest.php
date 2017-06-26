<?php

namespace app\tests\unit\models\forms;

use Yii;
use app\tests\fixtures\UserFixture;
use app\models\forms\LoginForm;

class LoginFormTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => UserFixture::class,
        ]);
    }

    // @codingStandardsIgnoreFile
    protected function _after()
    {
        Yii::$app->user->logout();
    }

    public function testEmptyFields()
    {
        $form = new LoginForm([
            'email' => '',
            'password' => '',
        ]);

        expect_not($form->login());
        expect($form->errors['password'][0])->notEmpty();
        expect_that(Yii::$app->user->isGuest);
    }

    public function testEmptyEmail()
    {
        $form = new LoginForm([
            'email' => '',
            'password' => 'test_password',
        ]);

        expect_not($form->login());
        expect($form->errors['email'][0])->notEmpty();
        expect_that(Yii::$app->user->isGuest);
    }


    public function testEmptyPassword()
    {
        $form = new LoginForm([
            'email' => 'test@test.com',
            'password' => '',
        ]);

        expect_not($form->login());
        expect($form->errors['password'][0])->notEmpty();
        expect_that(Yii::$app->user->isGuest);
    }

    public function testTooShortPassword()
    {
        $form = new LoginForm([
            'email' => 'test@test.com',
            'password' => '123',
        ]);

        expect_not($form->login());
        expect($form->errors['password'][0])->notEmpty();
        expect_that(Yii::$app->user->isGuest);
        expect($form->errors['password'][0])->contains('Incorrect email or password');
    }

    public function testWrongFields()
    {
        $form = new LoginForm([
            'email' => 'test@test.com',
            'password' => 'test_password',
        ]);

        expect_not($form->login());
        expect_that(Yii::$app->user->isGuest);
        expect($form->errors['password'][0])->contains('Incorrect email or password');
    }

    public function testWrongEmail()
    {
        $form = new LoginForm([
            'email' => 'test@test.com',
            'password' => '123123',
        ]);

        expect_not($form->login());
        expect_that(Yii::$app->user->isGuest);
        expect($form->errors['password'][0])->contains('Incorrect email or password');
    }

    public function testWrongPassword()
    {
        $form = new LoginForm([
            'email' => 'user-2@example.com',
            'password' => 'test_password',
        ]);

        expect_not($form->login());
        expect_that(Yii::$app->user->isGuest);
        expect($form->errors['password'][0])->contains('Incorrect email or password');
    }

    public function testWrongFormatEmail()
    {
        $form = new LoginForm([
            'email' => 'test_email',
            'password' => 'test_password',
        ]);

        expect_not($form->login());
        expect_that(Yii::$app->user->isGuest);
        expect($form->errors['email'][0])->contains('Email is not a valid email address');
    }

    public function testUserBlocked()
    {
        $form = new LoginForm([
            'email' => 'user-3@example.com',
            'password' => '123123',
        ]);

        expect_not($form->login());
        expect($form->errors)->notEmpty();
        expect_that(Yii::$app->user->isGuest);
        expect($form->errors['password'][0])->contains('Your account has been suspended');
    }

    public function testUserDeleted()
    {
        $form = new LoginForm([
            'email' => 'user-4@example.com',
            'password' => '123123',
        ]);

        expect_not($form->login());
        expect($form->errors)->notEmpty();
        expect_that(Yii::$app->user->isGuest);
        expect($form->errors['password'][0])->contains('Your account has been deleted');
    }

    public function testSuccess()
    {
        $form = new LoginForm([
            'email' => 'user-2@example.com',
            'password' => '123123',
        ]);

        expect_that($form->login());
        expect($form->errors)->isEmpty();
        expect_not(Yii::$app->user->isGuest);
    }
}
