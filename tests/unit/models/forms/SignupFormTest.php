<?php

namespace app\tests\unit\models\forms;

use Yii;
use app\tests\fixtures\User as UserFixture;
use app\models\forms\SignupForm;
use app\models\User;

class SignupFormTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        Yii::$app->settings->set('emailMain', 'editor@mail.com');
        Yii::$app->settings->set('emailName', 'Editor');

        $this->tester->haveFixtures([
             'user' => UserFixture::class,
        ]);
    }

    public function testEmptyFields()
    {
        $form = new SignupForm([
            'fullName' => '',
            'email' => '',
            'password' => '',
        ]);

        expect_not($form->signup());
        expect($form->getFirstError('fullName'))->notEmpty();
        expect($form->getFirstError('email'))->notEmpty();
        expect($form->getFirstError('password'))->notEmpty();
    }

    public function testEmptyPassword()
    {
        $form = new SignupForm([
            'fullName' => 'Test',
            'email' => 'test@test.com',
            'password' => '',
        ]);

        expect_not($form->signup());
        expect($form->getFirstError('password'))->notEmpty();
    }

    public function testTooShortPassword()
    {
        $form = new SignupForm([
            'fullName' => 'Test',
            'email' => 'test@test.com',
            'password' => 'test',
        ]);

        expect_not($form->signup());
        expect($form->getFirstError('password'))->notEmpty();
    }

    public function testEmptyEmail()
    {
        $form = new SignupForm([
            'fullName' => 'Test',
            'email' => '',
            'password' => 'test_password',
        ]);

        expect_not($form->signup());
        expect($form->getFirstError('email'))->notEmpty();
    }

    public function testEmptyFullName()
    {
        $form = new SignupForm([
            'fullName' => '',
            'email' => 'test@test.com',
            'password' => 'test_password',
        ]);

        expect_not($form->signup());
        expect($form->getFirstError('fullName'))->notEmpty();
    }

    public function testExist()
    {
        $form = new SignupForm([
            'fullName' => 'Test',
            'email' => 'user-2@example.com',
            'password' => 'test_password',
        ]);

        expect_not($form->signup());
        expect($form->getFirstError('email'))->notEmpty();
    }

    public function testSuccess()
    {
        $form = new SignupForm([
            'fullName' => 'Test',
            'email' => 'test@test.com',
            'password' => 'test_password',
        ]);

        $user = $form->signup();
        expect($user)->isInstanceOf('app\models\User');
        expect_not($user->isConfirmed());
        expect($user->email)->equals('test@test.com');
        expect_that($user->validatePassword('test_password'));
        expect_that($form->sendEmail());

        $user = User::findByEmail('test@test.com');
        expect($user->profile->full_name)->equals('Test');

        $message = $this->tester->grabLastSentEmail();
        expect('valid email is sent', $message)->isInstanceOf('yii\mail\MessageInterface');
        expect($message->getTo())->hasKey($user->email);
        expect($message->getFrom())->hasKey('editor@mail.com');
    }
}
