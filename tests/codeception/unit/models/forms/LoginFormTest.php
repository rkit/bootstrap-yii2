<?php

namespace tests\codeception\unit\models\forms;

use Yii;
use yii\codeception\DbTestCase;
use tests\codeception\fixtures\UserFixture;
use app\models\forms\LoginForm;

class LoginFormTest extends DbTestCase
{
    protected function tearDown()
    {
        Yii::$app->user->logout();
        parent::tearDown();
    }

    public function testLoginFormEmptyCredentials()
    {
        $form = new LoginForm([
            'email' => '',
            'password' => '',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors['password'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginFormEmptyEmail()
    {
        $form = new LoginForm([
            'email' => '',
            'password' => 'gw35hhbp',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors['email'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
    }


    public function testLoginFormEmptyPassword()
    {
        $form = new LoginForm([
            'email' => $this->user['2-active']['email'],
            'password' => '',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors['password'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginFormTooShortPassword()
    {
        $form = new LoginForm([
            'email' => $this->user['2-active']['email'],
            'password' => '123',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors['password'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
        $this->assertContains('Incorrect email or password', $form->errors['password'][0]);
    }

    public function testLoginFormWrong()
    {
        $form = new LoginForm([
            'email' => $this->user['2-active']['email'],
            'password' => 'gw35hhbp',
        ]);

        $this->assertFalse($form->login());
        $this->assertTrue(Yii::$app->user->isGuest);
        $this->assertContains('Incorrect email or password', $form->errors['password'][0]);
    }

    public function testLoginFormUserBlocked()
    {
        $form = new LoginForm([
            'email' => $this->user['3-blocked']['email'],
            'password' => '123123',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors);
        $this->assertTrue(Yii::$app->user->isGuest);
        $this->assertContains('Your account has been suspended', $form->errors['password'][0]);
    }

    public function testLoginFormUserDeleted()
    {
        $form = new LoginForm([
            'email' => $this->user['4-deleted']['email'],
            'password' => '123123',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors);
        $this->assertTrue(Yii::$app->user->isGuest);
        $this->assertContains('Your account has been deleted', $form->errors['password'][0]);
    }

    public function testLoginFormCorrect()
    {
        $form = new LoginForm([
            'email' => $this->user['2-active']['email'],
            'password' => '123123',
        ]);

        $this->assertTrue($form->login());
        $this->assertEmpty($form->errors);
        $this->assertFalse(Yii::$app->user->isGuest);
    }

    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/fixtures/data/user.php',
            ],
        ];
    }
}
