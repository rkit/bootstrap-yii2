<?php

namespace app\tests\unit\models\forms;

use Yii;
use app\tests\fixtures\UserFixture;
use app\models\entity\User;
use app\modules\auth\models\forms\PasswordResetRequestForm;

class PasswordResetRequestFormTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => UserFixture::class,
        ]);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage User not found
     */
    public function testEmptyEmail()
    {
        $form = Yii::$container->get(PasswordResetRequestForm::class);
        $form->email = '';
        expect_not($form->validate());
        $form->sendEmail();
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage User not found
     */
    public function testWrongEmail()
    {
        $form = Yii::$container->get(PasswordResetRequestForm::class);
        $form->email = 'test_email';
        expect_not($form->validate());
        $form->sendEmail();
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage User not found
     */
    public function testNonExistEmail()
    {
        $form = Yii::$container->get(PasswordResetRequestForm::class);
        $form->email = 'test@example.com';
        expect_not($form->validate());
        $form->sendEmail();
    }

    public function testUserBlocked()
    {
        $form = Yii::$container->get(PasswordResetRequestForm::class);
        $form->email = 'user-3@example.com';
        expect_not($form->validate());
    }

    public function testUserDeleted()
    {
        $form = Yii::$container->get(PasswordResetRequestForm::class);
        $form->email = 'user-4@example.com';
        expect_not($form->validate());
    }

    public function testInvalidToken()
    {
        $form = Yii::$container->get(PasswordResetRequestForm::class);
        $form->email = 'user-5@example.com';
        expect_that($form->validate());
        $form->sendEmail();
    }

    public function testSuccess()
    {
        $user = $this->tester->grabFixture('user', 'user-1');

        $form = Yii::$container->get(PasswordResetRequestForm::class);
        $form->email = 'user-2@example.com';
        expect_that($form->validate());
        $form->sendEmail();

        $user = User::findOne(['password_reset_token' => $user->password_reset_token]);
        expect($user->password_reset_token)->notNull();

        $message = $this->tester->grabLastSentEmail();
        expect('valid email is sent', $message)->isInstanceOf('yii\mail\MessageInterface');
        expect($message->getTo())->hasKey($form->email);
    }
}
