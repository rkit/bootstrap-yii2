<?php

namespace app\tests\unit\models\forms;

use Yii;
use app\tests\fixtures\User as UserFixture;
use app\models\User;
use app\models\forms\PasswordResetRequestForm;

class PasswordResetRequestFormTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        Yii::$app->settings->set('emailMain', 'editor@mail.com');
        Yii::$app->settings->set('emailName', 'Editor');

        $this->tester->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/user.php',
             ],
        ]);
    }

    public function testEmptyEmail()
    {
        $form = new PasswordResetRequestForm();
        $form->email = '';
        expect_not($form->validate());
        expect_not($form->sendEmail());
    }

    public function testWrongEmail()
    {
        $form = new PasswordResetRequestForm();
        $form->email = 'test_email';
        expect_not($form->validate());
        expect_not($form->sendEmail());
    }

    public function testNonExistEmail()
    {
        $form = new PasswordResetRequestForm();
        $form->email = 'test@test.com';
        expect_not($form->validate());
        expect_not($form->sendEmail());
    }

    public function testUserBlocked()
    {
        $form = new PasswordResetRequestForm();
        $form->email = 'user-3@example.com';
        expect_not($form->validate());
    }

    public function testUserDeleted()
    {
        $form = new PasswordResetRequestForm();
        $form->email = 'user-4@example.com';
        expect_not($form->validate());
    }

    public function testInvalidToken()
    {
        $form = new PasswordResetRequestForm();
        $form->email = 'user-5@example.com';
        expect_that($form->validate());
        expect_that($form->sendEmail());
    }

    public function testSuccess()
    {
        $userFixture = $this->tester->grabFixture('user', 1);

        $form = new PasswordResetRequestForm();
        $form->email = 'user-2@example.com';
        expect_that($form->validate());
        expect_that($form->sendEmail());

        $user = User::findOne(['password_reset_token' => $userFixture->password_reset_token]);
        expect($user->password_reset_token)->notNull();

        $message = $this->tester->grabLastSentEmail();
        expect('valid email is sent', $message)->isInstanceOf('yii\mail\MessageInterface');
        expect($message->getTo())->hasKey($form->email);
        expect($message->getFrom())->hasKey('editor@mail.com');
    }
}
