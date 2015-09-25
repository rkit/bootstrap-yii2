<?php

namespace tests\codeception\unit\models\forms;

use Yii;
use yii\codeception\DbTestCase;
use app\models\forms\ResetPasswordForm;
use tests\codeception\fixtures\UserFixture;
use app\models\User;
use Codeception\Specify;

class ResetPasswordFormTest extends DbTestCase
{
    use Specify;

    public function testResetPasswordWrongToken()
    {
        $form = new ResetPasswordForm();
        $this->assertFalse($form->validateToken('notexistingtoken_1391882543'));
    }

    public function testResetPasswordEmptyToken()
    {
        $form = new ResetPasswordForm();
        $this->assertFalse($form->validateToken(''));
    }

    public function testResetPasswordWrongPassword()
    {
        $form = new ResetPasswordForm();
        $form->password = 'qwe';
        $this->assertTrue($form->validateToken($this->user['2-active']['password_reset_token']));
        $this->assertFalse($form->resetPassword());
    }

    public function testResetPasswordCorrect()
    {
        $form = new ResetPasswordForm();
        $form->password = 'password-new';
        $this->assertTrue($form->validateToken($this->user['2-active']['password_reset_token']));
        $this->assertTrue($form->resetPassword());

        $user = User::findByEmail($this->user['2-active']['email']);
        $this->assertEmpty($user->password_reset_token);
        $this->assertTrue($user->validatePassword('password-new'));
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
