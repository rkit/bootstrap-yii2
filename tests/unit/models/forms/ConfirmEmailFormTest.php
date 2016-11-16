<?php

namespace app\tests\unit\models\forms;

use app\tests\fixtures\User as UserFixture;
use app\models\User;
use app\models\forms\ConfirmEmailForm;

class ConfirmEmailFormTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => UserFixture::className(),
        ]);
    }

    public function testWrongToken()
    {
        $form = new ConfirmEmailForm();
        expect_not($form->validateToken('notexistingtoken_1391882543'));
    }

    public function testEmptyToken()
    {
        $form = new ConfirmEmailForm();
        expect_not($form->validateToken(''));
    }

    public function testSuccess()
    {
        $user = User::findByEmail('superuser@example.com');
        expect_not($user->isConfirmed());

        $form = new ConfirmEmailForm();
        expect_that($form->validateToken($user->email_confirm_token));
        expect_that($form->confirmEmail());

        $user = User::findByEmail($user->email);
        expect($user->email_confirm_token)->isEmpty();
        expect_that($user->isConfirmed());
    }
}
