<?php

namespace app\tests\unit\models\forms;

use Yii;
use app\tests\fixtures\{UserFixture, UserProfileFixture};
use app\modules\auth\models\forms\SignupForm;
use app\models\entity\User;

class SignupFormTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => UserFixture::class,
             'profile' => UserProfileFixture::class,
        ]);
    }

    public function testEmptyFields()
    {
        $form = Yii::$container->get(SignupForm::class);
        $form->setAttributes([
            'fullName' => '',
            'email' => '',
            'password' => '',
        ]);

        expect_not($form->validate());
        expect($form->getFirstError('fullName'))->notEmpty();
        expect($form->getFirstError('email'))->notEmpty();
        expect($form->getFirstError('password'))->notEmpty();
    }

    public function testEmptyPassword()
    {
        $form = Yii::$container->get(SignupForm::class);
        $form->setAttributes([
            'fullName' => 'Test',
            'email' => 'test@example.com',
            'password' => '',
        ]);

        expect_not($form->validate());
        expect($form->getFirstError('password'))->notEmpty();
    }

    public function testTooShortPassword()
    {
        $form = Yii::$container->get(SignupForm::class);
        $form->setAttributes([
            'fullName' => 'Test',
            'email' => 'test@example.com',
            'password' => 'test',
        ]);

        expect_not($form->validate());
        expect($form->getFirstError('password'))->notEmpty();
    }

    public function testEmptyEmail()
    {
        $form = Yii::$container->get(SignupForm::class);
        $form->setAttributes([
            'fullName' => 'Test',
            'email' => '',
            'password' => 'test_password',
        ]);

        expect_not($form->validate());
        expect($form->getFirstError('email'))->notEmpty();
    }

    public function testEmptyFullName()
    {
        $form = Yii::$container->get(SignupForm::class);
        $form->setAttributes([
            'fullName' => '',
            'email' => 'test@example.com',
            'password' => 'test_password',
        ]);

        expect_not($form->validate());
        expect($form->getFirstError('fullName'))->notEmpty();
    }

    public function testExist()
    {
        $form = Yii::$container->get(SignupForm::class);
        $form->setAttributes([
            'fullName' => 'Test',
            'email' => 'user-2@example.com',
            'password' => 'test_password',
        ]);

        expect_not($form->validate());
        expect($form->getFirstError('email'))->notEmpty();
    }

    public function testSuccess()
    {
        $form = Yii::$container->get(SignupForm::class);
        $form->setAttributes([
            'fullName' => 'Test',
            'email' => 'test@example.com',
            'password' => 'test_password',
        ]);

        expect_that($form->validate());

        $user = $form->signup();
        expect($user)->isInstanceOf('app\models\entity\User');
        expect_not($user->isConfirmed());
        expect($user->email)->equals('test@example.com');
        expect_that($user->validatePassword('test_password'));

        $user = User::find()->email('test@example.com')->one();
        expect($user->profile->full_name)->equals('Test');

        $message = $this->tester->grabLastSentEmail();
        expect('valid email is sent', $message)->isInstanceOf('yii\mail\MessageInterface');
        expect($message->getTo())->hasKey($user->email);
    }
}
