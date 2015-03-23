<?php

namespace tests\codeception\unit\models;

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
        expect('token should be wrong', $form->validateToken('notexistingtoken_1391882543'))->false();
    }
    
    public function testResetPasswordEmptyToken()
    {
        $form = new ResetPasswordForm();
        expect('token should be wrong', $form->validateToken(''))->false();
    }
    
    public function testResetPasswordCorrect()
    {
        $form = new ResetPasswordForm();
        
        expect('token should be corrent', $form->validateToken($this->user[0]['password_reset_token']))->true();
        expect('password should be resetted', $form->resetPassword())->true();
        
        $user = User::findByEmail($this->user[0]['email']);
        
        expect('password_reset_token should be empty', $user->password_reset_token)->isEmpty();
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
