<?php

namespace tests\codeception\unit\models;

use Yii;
use yii\codeception\DbTestCase;
use app\models\forms\SignupForm;
use app\models\User;
use tests\codeception\fixtures\UserFixture;
use Codeception\Specify;

class SignupFormTest extends DbTestCase
{
    use Specify;
    
    public function testSignupNotCorrect()
    {
        $model = new SignupForm([
            'fullName' => 'Mike',
            'email' => 'example@example.com',
            'password' => 'two',
        ]);
        
        expect('user should not be created', $model->signup())->null();
        expect('error message should be set', $model->errors)->hasKey('password');
    }
    
    public function testSignupEmptyFullName()
    {
        $model = new SignupForm([
            'fullName' => '',
            'email' => 'example@example.com',
            'password' => 'gw35hhbp',
        ]);

        expect('model should not signup user', $model->signup())->null();
        expect('error message should be set', $model->errors)->hasKey('email');
    }
    
    public function testSignupEmptyPassword()
    {
        $model = new SignupForm([
            'fullName' => 'Mike',
            'email' => 'example@example.com',
            'password' => '',
        ]);

        expect('model should not signup user', $model->signup())->null();
        expect('error message should be set', $model->errors)->hasKey('password');
    }
    
    public function testSignupEmptyEmail()
    {
        $model = new SignupForm([
            'fullName' => 'Mike',
            'email' => '',
            'password' => 'gw35hhbp',
        ]);

        expect('model should not signup user', $model->signup())->null();
        expect('error message should be set', $model->errors)->hasKey('email');
    }
    
    public function testSignupExist()
    {
        $model = new SignupForm([
            'fullName' => 'Mike',
            'email' => 'example@example.com',
            'password' => 'gw35hhbp',
        ]);
        
        expect('user should not be created', $model->signup())->null();
        expect('email exist', $model->errors)->hasKey('email');
    }
    
    public function testSignupCorrect()
    {
        $model = new SignupForm([
            'fullName' => 'Mike',
            'email' => 'demo@example.com',
            'password' => 'demodemo',
        ]);
        
        $user = $model->signup();
        
        $this->assertInstanceOf('app\models\User', $user, 'user should be valid');
        
        expect('email should be correct', $user->email)->equals('demo@example.com');
        expect('password should be correct', $user->validatePassword('demodemo'))->true();
        
        $user = User::findByEmail('demo@example.com');
        
        expect('passwordResetToken should be empty', $user->profile->fullName)->contains('Mike');
        
        if ($user) {
            $user->delete();
        }
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
