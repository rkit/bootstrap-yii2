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
    
    /**
     * @expectedException \yii\base\InvalidParamException
     */
    public function testResetPasswordWrongToken()
    {
        new ResetPasswordForm('notexistingtoken_1391882543');
    }
    
    /**
     * @expectedException \yii\base\InvalidParamException
     */
    public function testResetPasswordEmptyToken()
    {
        new ResetPasswordForm('');
    }
    
    public function testResetPasswordCorrect()
    {
        $form = new ResetPasswordForm($this->user[0]['password_reset_token']);
        
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
