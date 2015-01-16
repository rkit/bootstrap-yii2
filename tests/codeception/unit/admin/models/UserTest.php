<?php

namespace tests\codeception\unit\admin\models;

use Yii;
use yii\codeception\DbTestCase;
use app\models\User;
use tests\codeception\fixtures\UserFixture;
use Codeception\Specify;

class UserTest extends DbTestCase
{
    use Specify;
    
    protected function tearDown()
    {
        Yii::$app->user->logout();
        parent::tearDown();
    }
    
    public function testUserNotCorrect()
    {
        $user = new User();
        $user->username = 'one';
        $user->email = 'two';

        expect('user should be false', $user->save())->false();
    }
    
    public function testUserEmpty()
    {
        $user = new User();
        $user->scenario = 'admin-edit';

        expect('user should be false', $user->save())->false();
    }
    
    public function testUserUnconfirmed()
    {
        $user = new User();
        $user->username = 'demo2';
        $user->email = 'demo2@example.com';
        $user->setPassword('fghfgh');
        $user->save();

        $user = User::findByEmail($user->email);
        
        $this->assertInstanceOf('app\models\User', $user, 'user should be exist');
        expect('user should be confirmed', !$user->isConfirmed())->true();
        
        if ($user) {
            $user->delete();
        }
    }
    
    public function testUserNewPassword()
    {
        $user = User::findByEmail($this->user[0]['email']);
        
        $this->assertInstanceOf('app\models\User', $user, 'user should be exist');
        
        $user->passwordNew = 'password-new';
        $user->save();
        
        expect('password should be correct', $user->validatePassword('password-new'))->true();
    }

    public function testUserSaving()
    {
        $user = new User();
        $user->username = 'demo2';
        $user->email = 'demo2@example.com';
        $user->setPassword('fghfgh');
        $user->setConfirmed();
        $user->save();

        $user = User::findByEmail($user->email);
        
        $this->assertInstanceOf('app\models\User', $user, 'user should be exist');
        expect('user should be active', $user->isActive())->true();
        expect('user should be confirmed', $user->isConfirmed())->true();
        expect('email should be correct', $user->email)->equals($user->email);
        expect('password should be correct', $user->validatePassword('fghfgh'))->true();
        
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
