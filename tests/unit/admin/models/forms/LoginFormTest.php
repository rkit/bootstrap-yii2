<?php

namespace app\tests\unit\admin\models\forms;

use Yii;
use app\tests\fixtures\User as UserFixture;
use app\modules\admin\models\forms\LoginForm;

class LoginFormTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
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
            'username' => '',
            'password' => '',
        ]);

        expect_not($form->login());
        expect($form->errors['password'][0])->notEmpty();
        expect_that(Yii::$app->user->isGuest);
    }

    public function testEmptyUsername()
    {
        $form = new LoginForm([
            'username' => '',
            'password' => 'test_password',
        ]);

        expect_not($form->login());
        expect($form->errors['username'][0])->notEmpty();
        expect_that(Yii::$app->user->isGuest);
    }


    public function testEmptyPassword()
    {
        $form = new LoginForm([
            'username' => 'test_username',
            'password' => '',
        ]);

        expect_not($form->login());
        expect($form->errors['password'][0])->notEmpty();
        expect_that(Yii::$app->user->isGuest);
    }

    public function testTooShortPassword()
    {
        $form = new LoginForm([
            'username' => 'test_username',
            'password' => 'test',
        ]);

        expect_not($form->login());
        expect($form->errors['password'][0])->notEmpty();
        expect_that(Yii::$app->user->isGuest);
        expect($form->errors['password'][0])->contains('Incorrect username or password');
    }

    public function testWrongFields()
    {
        $form = new LoginForm([
            'username' => 'test_username',
            'password' => 'test_password',
        ]);

        expect_not($form->login());
        expect_that(Yii::$app->user->isGuest);
        expect($form->errors['password'][0])->contains('Incorrect username or password');
    }

    public function testWrongUsername()
    {
        $form = new LoginForm([
            'username' => 'test_username',
            'password' => 'fghfgh',
        ]);

        expect_not($form->login());
        expect_that(Yii::$app->user->isGuest);
        expect($form->errors['password'][0])->contains('Incorrect username or password');
    }

    public function testWrongPassword()
    {
        $form = new LoginForm([
            'username' => 'superuser',
            'password' => 'test_password',
        ]);

        expect_not($form->login());
        expect_that(Yii::$app->user->isGuest);
        expect($form->errors['password'][0])->contains('Incorrect username or password');
    }

    public function testUserBlocked()
    {
        $form = new LoginForm([
            'username' => 'user-3',
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
            'username' => 'user-4',
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
            'username' => 'superuser',
            'password' => 'fghfgh',
        ]);

        expect_that($form->login());
        expect($form->errors)->isEmpty();
        expect_not(Yii::$app->user->isGuest);
    }
}
