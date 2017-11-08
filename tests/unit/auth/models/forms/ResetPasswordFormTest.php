<?php

namespace app\tests\unit\models\forms;

use Yii;
use app\tests\fixtures\UserFixture;
use app\models\entity\User;
use app\modules\auth\models\forms\ResetPasswordForm;

class ResetPasswordFormTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => UserFixture::class,
        ]);
    }

    public function testEmptyPassword()
    {
        $user = $this->tester->grabFixture('user', 'user-1');

        $form = Yii::$container->get(ResetPasswordForm::class, [$user->password_reset_token]);
        $form->password = '';
        expect_not($form->validate());
    }

    public function testTooShortPassword()
    {
        $user = $this->tester->grabFixture('user', 'user-1');

        $form = Yii::$container->get(ResetPasswordForm::class, [$user->password_reset_token]);
        $form->password = 'qwe';
        expect_not($form->validate());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid link for reset password
     */
    public function testWrongToken()
    {
        $form = Yii::$container->get(ResetPasswordForm::class, ['notexistingtoken_1391882543']);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid link for reset password
     */
    public function testEmptyToken()
    {
        $form = Yii::$container->get(ResetPasswordForm::class, ['']);
    }

    public function testSuccess()
    {
        $user = $this->tester->grabFixture('user', 'user-1');

        $form = Yii::$container->get(ResetPasswordForm::class, [$user->password_reset_token]);
        $form->password = 'password-new';
        $form->resetPassword();

        $user = User::find()->email($user->email)->one();
        expect($user->password_reset_token)->isEmpty();
        expect_that($user->validatePassword('password-new'));
    }
}
