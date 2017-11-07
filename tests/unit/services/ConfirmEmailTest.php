<?php

namespace app\tests\unit\services;

use Yii;
use app\tests\fixtures\UserFixture;
use app\models\entity\User;
use app\modules\auth\services\ConfirmEmail;

class ConfirmEmailTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => UserFixture::class,
        ]);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid token for activate account
     */
    public function testWrongToken()
    {
        $form = Yii::$container->get(ConfirmEmail::class);
        expect_not($form->setConfirmed('notexistingtoken_1391882543'));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid token for activate account
     */
    public function testEmptyToken()
    {
        $form = Yii::$container->get(ConfirmEmail::class);
        expect_not($form->setConfirmed(''));
    }

    public function testSuccess()
    {
        $user = User::find()->email('superuser@example.com')->one();
        expect_not($user->isConfirmed());

        $form = Yii::$container->get(ConfirmEmail::class);
        $form->setConfirmed($user->email_confirm_token);

        $user = User::find()->email($user->email)->one();
        expect($user->email_confirm_token)->isEmpty();
        expect_that($user->isConfirmed());
    }
}
