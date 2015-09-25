<?php

namespace tests\codeception\unit\admin\models;

use Yii;
use yii\codeception\DbTestCase;
use app\modules\admin\models\forms\LoginForm;
use tests\codeception\fixtures\UserFixture;
use tests\codeception\fixtures\UserProfileFixture;
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
        $model = new LoginForm([
            'username' => $this->user['1-superuser']['username'],
            'password' => 'gw35hhbp',
        ]);

        $this->assertFalse($model->login());
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginEmptyPassword()
    {
        $model = new LoginForm([
            'username' => $this->user['1-superuser']['username'],
            'password' => '',
        ]);

        $this->assertFalse($model->login());
        $this->assertNotEmpty($model->errors['password'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginEmptyUsername()
    {
        $model = new LoginForm([
            'username' => '',
            'password' => 'gw35hhbp',
        ]);

        $this->assertFalse($model->login());
        $this->assertNotEmpty($model->errors['username'][0]);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginBlocked()
    {
        $model = new LoginForm([
            'username' => $this->user['3-blocked']['username'],
            'password' => '123123',
        ]);

        $this->assertFalse($model->login());
        $this->assertNotEmpty($model->errors);
        $this->assertTrue(Yii::$app->user->isGuest);
        $this->assertContains('Your account has been suspended', $model->errors['password'][0]);
    }

    public function testLoginCorrect()
    {
        $model = new LoginForm([
            'username' => $this->user['1-superuser']['username'],
            'password' => 'fghfgh',
        ]);

        $this->assertTrue($model->login());
        $this->assertEmpty($model->errors);
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
