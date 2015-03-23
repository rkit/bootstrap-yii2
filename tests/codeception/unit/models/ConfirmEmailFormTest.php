<?php

namespace tests\codeception\unit\models;

use Yii;
use yii\codeception\DbTestCase;
use app\models\forms\ConfirmEmailForm;
use tests\codeception\fixtures\UserFixture;
use app\models\User;
use Codeception\Specify;

class ConfirmEmailFormTest extends DbTestCase
{
    use Specify;
    
    public function testConfirmEmailWrongToken()
    {
        $form = new ConfirmEmailForm();
        expect('token should be wrong', $form->validateToken('notexistingtoken_1391882543'))->false();
    }
    
    public function testConfirmEmailEmptyToken()
    {
        $form = new ConfirmEmailForm();
        expect('token should be wrong', $form->validateToken(''))->false();
    }
    
    public function testConfirmEmailCorrect()
    {
        $form = new ConfirmEmailForm();
        
        expect('token should be corrent', $form->validateToken($this->user[0]['email_confirm_token']))->true();
        expect('confirmed token should be ok', $form->confirmEmail())->true();
        
        $user = User::findByEmail($this->user[0]['email']);
        
        expect('user should be confirmed', $user->isConfirmed())->true();
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
