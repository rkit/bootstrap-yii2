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
    
    /**
     * @expectedException \yii\base\InvalidParamException
     */
    public function testConfirmEmailWrongToken()
    {
        new ConfirmEmailForm('notexistingtoken_1391882543');
    }
    
    /**
     * @expectedException \yii\base\InvalidParamException
     */
    public function testConfirmEmailEmptyToken()
    {
        new ConfirmEmailForm('');
    }
    
    public function testConfirmEmailCorrect()
    {
        $form = new ConfirmEmailForm($this->user[0]['email_confirm_token']);
        
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
