<?php

namespace tests\codeception\unit\models\forms;

use Yii;
use yii\codeception\DbTestCase;
use tests\codeception\fixtures\UserFixture;
use app\models\User;
use app\models\forms\PasswordResetRequestForm;

class PasswordResetRequestFormTest extends DbTestCase
{
    protected function setUp()
    {
        parent::setUp();

        Yii::$app->mailer->fileTransportCallback = function ($mailer, $message) {
            return 'testing_message.eml';
        };
    }

    protected function tearDown()
    {
        @unlink($this->getMessageFile());
        parent::tearDown();
    }

    public function testPasswordResetRequestFormNonExistEmail()
    {
        $form = new PasswordResetRequestForm();
        $form->email = 'not-exist@example.com';
        $this->assertFalse($form->sendEmail());
    }

    public function testPasswordResetRequestFormUserBlocked()
    {
        $form = new PasswordResetRequestForm();
        $form->email = $this->user['3-blocked']['email'];
        $this->assertFalse($form->sendEmail());
    }

    public function testPasswordResetRequestFormUserDeleted()
    {
        $form = new PasswordResetRequestForm();
        $form->email = $this->user['4-deleted']['email'];
        $this->assertFalse($form->sendEmail());
    }

    public function testPasswordResetRequestFormInvalidToken()
    {
        $form = new PasswordResetRequestForm();
        $form->email = $this->user['5-wrong_password_reset_token']['email'];
        $this->assertTrue($form->sendEmail());
    }

    public function testPasswordResetRequestFormCorrect()
    {
        $form = new PasswordResetRequestForm();
        $form->email = $this->user['2-active']['email'];
        $this->assertTrue($form->sendEmail());

        $user = User::findOne(['password_reset_token' => $this->user['2-active']['password_reset_token']]);
        $this->assertNotNull($user->password_reset_token);
        $this->assertTrue(file_exists($this->getMessageFile()));
    }

    private function getMessageFile()
    {
        return Yii::getAlias(Yii::$app->mailer->fileTransportPath) . '/testing_message.eml';
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
