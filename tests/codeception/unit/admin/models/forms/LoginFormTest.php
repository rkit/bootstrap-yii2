<?php

namespace tests\codeception\unit\admin\models\forms;

use Yii;
use yii\codeception\DbTestCase;
use tests\codeception\fixtures\UserFixture;
use tests\codeception\fixtures\UserProfileFixture;
use app\modules\admin\models\forms\LoginForm;

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
            'username' => '',
            'password' => '',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors['username'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginFormEmptyUsername()
    {
        $form = new LoginForm([
            'username' => '',
            'password' => 'gw35hhbp',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors['username'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginFormEmptyPassword()
    {
        $form = new LoginForm([
            'username' => $this->user['1-superuser']['username'],
            'password' => '',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors['password'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginFormTooShortPassword()
    {
        $form = new LoginForm([
            'username' => $this->user['1-superuser']['username'],
            'password' => '123',
        ]);

        $this->assertFalse($form->login());
        $this->assertNotEmpty($form->errors['password'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
        $this->assertContains('Incorrect username or password', $form->errors['password'][0]);
    }

    public function testLoginFormWrong()
    {
        $form = new LoginForm([
            'username' => $this->user['1-superuser']['username'],
            'password' => 'gw35hhbp',
        ]);

        $this->assertFalse($form->login());
        $this->assertTrue(Yii::$app->user->isGuest);
        $this->assertContains('Incorrect username or password', $form->errors['password'][0]);
    }

    public function testLoginFormUserBlocked()
    {
        $form = new LoginForm([
            'username' => $this->user['3-blocked']['username'],
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
            'username' => $this->user['4-deleted']['username'],
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
            'username' => $this->user['1-superuser']['username'],
            'password' => 'fghfgh',
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
            'profile' => [
                'class' => UserProfileFixture::className(),
                'dataFile' => '@tests/codeception/fixtures/data/user_profile.php',
            ],
        ];
    }
}
