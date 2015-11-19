<?php

namespace tests\codeception\unit\models\forms;

use Yii;
use yii\codeception\DbTestCase;
use tests\codeception\fixtures\UserFixture;
use app\models\User;
use app\models\forms\ConfirmEmailForm;

class ConfirmEmailFormTest extends DbTestCase
{
    public function testConfirmEmailFormWrongToken()
    {
        $form = new ConfirmEmailForm();
        $this->assertFalse($form->validateToken('notexistingtoken_1391882543'));
    }

    public function testConfirmEmailFormEmptyToken()
    {
        $form = new ConfirmEmailForm();
        $this->assertFalse($form->validateToken(''));
    }

    public function testConfirmEmailFormCorrect()
    {
        $user = User::findByEmail($this->user['2-active']['email']);
        $this->assertFalse($user->isConfirmed());

        $form = new ConfirmEmailForm();
        $this->assertTrue($form->validateToken($user->email_confirm_token));
        $this->assertTrue($form->confirmEmail());

        $user = User::findByEmail($user->email);
        $this->assertEmpty($user->email_confirm_token);
        $this->assertTrue($user->isConfirmed());
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
