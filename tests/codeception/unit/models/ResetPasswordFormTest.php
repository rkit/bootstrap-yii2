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
    public function testResetWrongToken()
    {
        new ResetPasswordForm('notexistingtoken_1391882543');
    }
    
    /**
     * @expectedException \yii\base\InvalidParamException
     */
    public function testResetEmptyToken()
    {
        new ResetPasswordForm('');
    }
    
    public function testResetCorrect()
    {
        $form = new ResetPasswordForm($this->user[0]['passwordResetToken']);
        
        expect('password should be resetted', $form->resetPassword())->true();
        
        $user = User::findByEmail($this->user[0]['email']);
        
        expect('passwordResetToken should be empty', $user->passwordResetToken)->isEmpty();
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
