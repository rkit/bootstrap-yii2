<?php

namespace tests\codeception\unit\models;

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
        $model = new LoginForm([
            'email' => 'example@example.com',
            'password' => 'gw35hhbp',
        ]);

        $this->specify('user should not be able to login, when there is no identity', function () use ($model) {
            expect('model should not login user', $model->login())->false();
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        });
    }

    public function testLoginEmptyPassword()
    {
        $model = new LoginForm([
            'email' => 'example@example.com',
            'password' => '',
        ]);

        $this->specify('user should not be able to login with wrong password', function () use ($model) {
            expect('model should not login user', $model->login())->false();
            expect('error message should be set', $model->errors)->hasKey('password');
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        });
    }
    
    public function testLoginEmptyEmail()
    {
        $model = new LoginForm([
            'email' => '',
            'password' => 'gw35hhbp',
        ]);

        $this->specify('user should not be able to login with wrong email', function () use ($model) {
            expect('model should not login user', $model->login())->false();
            expect('error message should be set', $model->errors)->hasKey('email');
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        });
    }

    public function testLoginCorrect()
    {
        $model = new LoginForm([
            'email' => 'example@example.com',
            'password' => '123123',
        ]);

        $this->specify('user should be able to login with correct credentials', function () use ($model) {
            expect('model should login user', $model->login())->true();
            expect('error message should not be set', $model->errors)->hasntKey('password');
            expect('user should be logged in', Yii::$app->user->isGuest)->false();
        });
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
