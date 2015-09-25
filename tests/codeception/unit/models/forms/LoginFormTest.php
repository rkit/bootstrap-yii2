<?php

namespace tests\codeception\unit\models\forms;

use Yii;
use yii\codeception\DbTestCase;
use app\models\forms\LoginForm;
use tests\codeception\fixtures\UserFixture;
use Codeception\Specify;

class LoginFormTest extends DbTestCase
{
    use Specify;

    protected function tearDown()
    {
        Yii::$app->user->logout();
        parent::tearDown();
    }

    public function testLoginNotCorrect()
    {
        $form = new LoginForm([
            'email' => $this->user['2-active']['email'],
            'password' => 'gw35hhbp',
        ]);

        $this->assertFalse($form->login());
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginEmptyPassword()
    {
        $form = new LoginForm([
            'email' => $this->user['2-active']['email'],
            'password' => '',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors['password'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginEmptyEmail()
    {
        $form = new LoginForm([
            'email' => '',
            'password' => 'gw35hhbp',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors['email'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginBlocked()
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

    public function testLoginCorrect()
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
